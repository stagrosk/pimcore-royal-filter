<?php

declare(strict_types=1);

namespace App\Service;

use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Data\BlockElement;
use OpenDxp\Model\DataObject\Data\Hotspotimage;
use OpenDxp\Model\DataObject\Data\Link;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\AbstractData as FieldcollectionItem;
use OpenDxp\Model\DataObject\Fieldcollection\Definition as FieldcollectionDefinition;
use OpenDxp\Model\Element\AbstractElement;

/**
 * Serializes any Pimcore DataObject (Concrete) into a portable, environment-agnostic
 * JSON-friendly array. Walks the class definition recursively and handles:
 *   scalar fields, localizedfields, blocks, fieldcollections, image/hotspotimage assets,
 *   link fields, manyToOne / manyToMany relations.
 *
 * Internal references (Link.internal, ManyTo* relations, Image/Hotspotimage assets) are
 * exported by fullpath so the importer can re-resolve them on the target environment.
 */
class ObjectExporter
{
    public const int FORMAT_VERSION = 1;

    /** @var string[] */
    private array $languages;

    /**
     * @param string[] $languages valid system languages
     */
    public function __construct(array $languages = ['sk', 'cs', 'en', 'de', 'pl', 'hu'])
    {
        $this->languages = $languages;
    }

    /**
     * @return array<string, mixed>
     */
    public function export(Concrete $object): array
    {
        return [
            'version' => self::FORMAT_VERSION,
            'exportedAt' => (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
            'source' => [
                'id' => $object->getId(),
                'key' => $object->getKey(),
                'fullpath' => $object->getFullPath(),
                'className' => $object->getClassName(),
            ],
            'fields' => $this->serializeObjectFields($object),
        ];
    }

    /** @return array<string, mixed> */
    private function serializeObjectFields(Concrete $object): array
    {
        $out = [];
        foreach ($object->getClass()->getFieldDefinitions() as $name => $fd) {
            $value = $object->get($name);
            $out[$name] = $this->serializeField($fd, $value, $object);
        }

        return $out;
    }

    private function serializeField(Data $fd, mixed $value, ?Concrete $owner = null): mixed
    {
        return match ($fd->getFieldtype()) {
            'localizedfields' => $this->serializeLocalizedfields($fd, $owner),
            'fieldcollections' => $this->serializeFieldcollection($value),
            'block' => $this->serializeBlock($fd, $value),
            'image' => $this->serializeAsset($value),
            'hotspotimage' => $this->serializeHotspotimage($value),
            'link' => $this->serializeLink($value),
            'manyToOneRelation' => $this->serializeRelation($value),
            'manyToManyRelation', 'manyToManyObjectRelation' => $this->serializeRelations($value),
            'date', 'datetime' => $this->serializeDate($value),
            default => $this->serializeScalar($value),
        };
    }

    /** @return array<string, mixed> */
    private function serializeLocalizedfields(Data $fd, ?Concrete $owner): array
    {
        $children = method_exists($fd, 'getFieldDefinitions') ? $fd->getFieldDefinitions() : [];
        $byLang = [];
        if (!$owner) {
            return ['languages' => $byLang];
        }
        foreach ($this->languages as $lang) {
            $row = [];
            foreach ($children as $childName => $childFd) {
                $val = $owner->get($childName, $lang);
                $row[$childName] = $this->serializeField($childFd, $val, $owner);
            }
            $byLang[$lang] = $row;
        }

        return ['languages' => $byLang];
    }

    /** @return array<int, array<string, mixed>> */
    private function serializeFieldcollection(mixed $value): array
    {
        if (!$value instanceof Fieldcollection) {
            return [];
        }

        $items = [];
        foreach ($value->getItems() as $item) {
            $items[] = $this->serializeFieldcollectionItem($item);
        }

        return $items;
    }

    /** @return array<string, mixed> */
    private function serializeFieldcollectionItem(FieldcollectionItem $item): array
    {
        $type = $item->getType();
        $def = FieldcollectionDefinition::getByKey($type);
        if (!$def) {
            return ['_type' => $type, 'fields' => []];
        }

        $fields = [];
        foreach ($def->getFieldDefinitions() as $name => $fd) {
            // FC item methods don't always accept language; for localizedfields we delegate.
            if ($fd->getFieldtype() === 'localizedfields') {
                $fields[$name] = $this->serializeLocalizedfieldsForItem($fd, $item);
            } else {
                $value = $item->get($name);
                $fields[$name] = $this->serializeField($fd, $value, null);
            }
        }

        return ['_type' => $type, 'fields' => $fields];
    }

    /** @return array<string, mixed> */
    private function serializeLocalizedfieldsForItem(Data $fd, FieldcollectionItem $item): array
    {
        $children = method_exists($fd, 'getFieldDefinitions') ? $fd->getFieldDefinitions() : [];
        $byLang = [];
        foreach ($this->languages as $lang) {
            $row = [];
            foreach ($children as $childName => $childFd) {
                $localizedfields = $item->getLocalizedfields();
                $val = $localizedfields ? $localizedfields->getLocalizedValue($childName, $lang) : null;
                $row[$childName] = $this->serializeField($childFd, $val, null);
            }
            $byLang[$lang] = $row;
        }

        return ['languages' => $byLang];
    }

    /** @return array<int, array<string, mixed>> */
    private function serializeBlock(Data $fd, mixed $value): array
    {
        if (!is_array($value) || $value === []) {
            return [];
        }

        $children = method_exists($fd, 'getChildren') ? $fd->getChildren() : [];
        $childByName = [];
        foreach ($children as $child) {
            if ($child instanceof Data) {
                $childByName[$child->getName()] = $child;
            }
        }

        $rows = [];
        foreach ($value as $row) {
            if (!is_array($row)) {
                continue;
            }
            $serializedRow = [];
            foreach ($row as $name => $blockEl) {
                $childFd = $childByName[$name] ?? null;
                $rawValue = $blockEl instanceof BlockElement ? $blockEl->getData() : $blockEl;
                $serializedRow[$name] = $childFd
                    ? $this->serializeField($childFd, $rawValue, null)
                    : $this->serializeScalar($rawValue);
            }
            $rows[] = $serializedRow;
        }

        return $rows;
    }

    /** @return array<string, mixed>|null */
    private function serializeAsset(mixed $value): ?array
    {
        if ($value instanceof Asset) {
            return ['fullpath' => $value->getFullPath(), 'id' => $value->getId()];
        }

        return null;
    }

    /** @return array<string, mixed>|null */
    private function serializeHotspotimage(mixed $value): ?array
    {
        if (!$value instanceof Hotspotimage) {
            return null;
        }
        $image = $value->getImage();

        return [
            'asset' => $image instanceof Asset ? ['fullpath' => $image->getFullPath(), 'id' => $image->getId()] : null,
            'hotspots' => $value->getHotspots() ?: [],
            'marker' => $value->getMarker() ?: [],
            'crop' => $value->getCrop() ?: [],
        ];
    }

    /** @return array<string, mixed>|null */
    private function serializeLink(mixed $value): ?array
    {
        if (!$value instanceof Link) {
            return null;
        }

        $internalPath = null;
        $internalType = $value->getInternalType();
        if ($value->getLinktype() === 'internal' && $internalType) {
            $element = $value->getElement();
            if ($element instanceof AbstractElement) {
                $internalPath = $element->getFullPath();
            }
        }

        return [
            'linktype' => $value->getLinktype(),
            'internalType' => $internalType,
            'internalPath' => $internalPath,
            'direct' => $value->getDirect(),
            'text' => $value->getText(),
            'target' => $value->getTarget(),
            'anchor' => $value->getAnchor(),
            'title' => $value->getTitle(),
            'accesskey' => $value->getAccesskey(),
            'rel' => $value->getRel(),
            'class' => $value->getClass(),
            'attributes' => $value->getAttributes(),
            'tabindex' => $value->getTabindex(),
            'parameters' => $value->getParameters(),
        ];
    }

    /** @return array<string, mixed>|null */
    private function serializeRelation(mixed $value): ?array
    {
        if ($value instanceof AbstractElement) {
            return [
                'elementType' => $this->elementType($value),
                'fullpath' => $value->getFullPath(),
                'id' => $value->getId(),
            ];
        }

        return null;
    }

    /** @return array<int, array<string, mixed>> */
    private function serializeRelations(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }
        $out = [];
        foreach ($value as $element) {
            $serialized = $this->serializeRelation($element);
            if ($serialized) {
                $out[] = $serialized;
            }
        }

        return $out;
    }

    private function serializeDate(mixed $value): ?int
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->getTimestamp();
        }

        return null;
    }

    private function serializeScalar(mixed $value): mixed
    {
        if (is_scalar($value) || $value === null) {
            return $value;
        }
        if (is_array($value)) {
            return $value;
        }

        return null;
    }

    private function elementType(AbstractElement $el): string
    {
        if ($el instanceof Concrete) {
            return 'object';
        }
        if ($el instanceof Asset) {
            return 'asset';
        }

        return 'document';
    }
}
