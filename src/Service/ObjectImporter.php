<?php

declare(strict_types=1);

namespace App\Service;

use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Data\BlockElement;
use OpenDxp\Model\DataObject\Data\Hotspotimage;
use OpenDxp\Model\DataObject\Data\Link;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Definition as FieldcollectionDefinition;
use OpenDxp\Model\Document;

/**
 * Reverse of {@see ObjectExporter} — applies a portable export to any target Pimcore DataObject.
 *
 * Internal references (Link.internal, ManyTo* relations, Asset/Hotspotimage assets) are resolved
 * by their fullpath. Missing targets emit a warning into $warnings; the field is then left null
 * (or skipped) so the import succeeds even on partially synced environments.
 *
 * The target object's classId/className must match the export source.
 */
class ObjectImporter
{
    /** @var string[] */
    public array $warnings = [];

    public function import(Concrete $object, array $data): Concrete
    {
        $this->warnings = [];

        $sourceClass = $data['source']['className'] ?? null;
        if (is_string($sourceClass) && $sourceClass !== '' && $sourceClass !== $object->getClassName()) {
            $this->warnings[] = sprintf(
                'Source className "%s" does not match target "%s" — proceeding anyway, mismatched fields will be skipped.',
                $sourceClass,
                $object->getClassName()
            );
        }

        $fields = $data['fields'] ?? [];

        foreach ($object->getClass()->getFieldDefinitions() as $name => $fd) {
            if (!array_key_exists($name, $fields)) {
                continue;
            }
            if ($fd->getFieldtype() === 'localizedfields') {
                $this->applyLocalizedfields($fields[$name], $object);

                continue;
            }
            $value = $this->materializeField($fd, $fields[$name], $object);
            $object->set($name, $value);
        }

        return $object;
    }

    /** @return mixed */
    private function materializeField(Data $fd, mixed $value, ?Concrete $owner = null): mixed
    {
        return match ($fd->getFieldtype()) {
            'localizedfields' => null, // handled separately via applyLocalizedfields
            'fieldcollections' => $this->materializeFieldcollection($value),
            'block' => $this->materializeBlock($fd, $value),
            'image' => $this->materializeAsset($value),
            'hotspotimage' => $this->materializeHotspotimage($value),
            'link' => $this->materializeLink($value),
            'manyToOneRelation' => $this->materializeRelation($value),
            'manyToManyRelation' => $this->materializeRelations($value),
            'date', 'datetime' => $this->materializeDate($value),
            default => $this->materializeScalar($value),
        };
    }

    /** Walk an exported localizedfields payload and write each language separately. */
    private function applyLocalizedfields(mixed $value, Concrete $owner): void
    {
        if (!is_array($value) || !isset($value['languages']) || !is_array($value['languages'])) {
            return;
        }

        // Find the localizedfields definition to introspect children
        $lfDef = null;
        foreach ($owner->getClass()->getFieldDefinitions() as $fd) {
            if ($fd->getFieldtype() === 'localizedfields') {
                $lfDef = $fd;

                break;
            }
        }
        if (!$lfDef || !method_exists($lfDef, 'getFieldDefinitions')) {
            return;
        }
        $children = $lfDef->getFieldDefinitions();

        foreach ($value['languages'] as $lang => $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach ($row as $childName => $childValue) {
                $childFd = $children[$childName] ?? null;
                if (!$childFd) {
                    continue;
                }
                $materialized = $this->materializeField($childFd, $childValue, $owner);
                $owner->set($childName, $materialized, $lang);
            }
        }
    }

    /** Materialize a localizedfields payload for a fieldcollection item (item has getLocalizedfields). */
    private function applyLocalizedfieldsForFcItem(Data $lfDef, mixed $value, $item): void
    {
        if (!is_array($value) || !isset($value['languages']) || !is_array($value['languages'])) {
            return;
        }
        if (!method_exists($lfDef, 'getFieldDefinitions')) {
            return;
        }
        $children = $lfDef->getFieldDefinitions();
        $localizedfields = $item->getLocalizedfields() ?: new DataObject\Localizedfield();
        if (!$item->getLocalizedfields()) {
            $item->setLocalizedfields($localizedfields);
        }

        foreach ($value['languages'] as $lang => $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach ($row as $childName => $childValue) {
                $childFd = $children[$childName] ?? null;
                if (!$childFd) {
                    continue;
                }
                $materialized = $this->materializeField($childFd, $childValue);
                $localizedfields->setLocalizedValue($childName, $materialized, $lang);
            }
        }
    }

    private function materializeFieldcollection(mixed $value): ?Fieldcollection
    {
        if (!is_array($value)) {
            return null;
        }
        $fc = new Fieldcollection();
        foreach ($value as $entry) {
            if (!is_array($entry) || !isset($entry['_type'])) {
                continue;
            }
            $type = $entry['_type'];
            $def = FieldcollectionDefinition::getByKey($type);
            if (!$def) {
                $this->warnings[] = sprintf('Skipped unknown fieldcollection type "%s"', $type);

                continue;
            }
            $itemClass = '\\OpenDxp\\Model\\DataObject\\Fieldcollection\\Data\\' . ucfirst($type);
            if (!class_exists($itemClass)) {
                $this->warnings[] = sprintf('Skipped fieldcollection item — class not found: %s', $itemClass);

                continue;
            }
            $item = new $itemClass();

            $fields = $entry['fields'] ?? [];
            foreach ($def->getFieldDefinitions() as $name => $fd) {
                if (!array_key_exists($name, $fields)) {
                    continue;
                }
                if ($fd->getFieldtype() === 'localizedfields') {
                    $this->applyLocalizedfieldsForFcItem($fd, $fields[$name], $item);

                    continue;
                }
                $materialized = $this->materializeField($fd, $fields[$name]);
                $item->set($name, $materialized);
            }

            $fc->add($item);
        }

        return $fc;
    }

    /** @return array<int, array<string, BlockElement>> */
    private function materializeBlock(Data $fd, mixed $value): array
    {
        if (!is_array($value)) {
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
            $blockRow = [];
            foreach ($row as $name => $rawValue) {
                $childFd = $childByName[$name] ?? null;
                $type = $childFd?->getFieldtype() ?? 'input';
                $materialized = $childFd
                    ? $this->materializeField($childFd, $rawValue)
                    : $rawValue;
                $blockRow[$name] = new BlockElement($name, $type, $materialized);
            }
            $rows[] = $blockRow;
        }

        return $rows;
    }

    private function materializeAsset(mixed $value): ?Asset
    {
        if (!is_array($value)) {
            return null;
        }
        $path = $value['fullpath'] ?? null;
        if (!is_string($path) || $path === '') {
            return null;
        }
        $asset = Asset::getByPath($path);
        if (!$asset) {
            $this->warnings[] = sprintf('Asset not found by path: %s', $path);

            return null;
        }

        return $asset;
    }

    private function materializeHotspotimage(mixed $value): ?Hotspotimage
    {
        if (!is_array($value)) {
            return null;
        }
        $asset = $this->materializeAsset($value['asset'] ?? null);
        if (!$asset) {
            return null;
        }

        return new Hotspotimage(
            $asset,
            $value['hotspots'] ?? [],
            $value['marker'] ?? [],
            $value['crop'] ?? []
        );
    }

    private function materializeLink(mixed $value): ?Link
    {
        if (!is_array($value)) {
            return null;
        }
        $linktype = $value['linktype'] ?? null;
        if ($linktype === null) {
            // Empty link - return new Link with no values to clear it
            return null;
        }

        $link = new Link();
        $link->setLinktype($linktype);
        $link->setText((string) ($value['text'] ?? ''));
        $link->setTarget((string) ($value['target'] ?? ''));
        $link->setAnchor((string) ($value['anchor'] ?? ''));
        $link->setTitle((string) ($value['title'] ?? ''));
        $link->setAccesskey((string) ($value['accesskey'] ?? ''));
        $link->setRel((string) ($value['rel'] ?? ''));
        $link->setClass((string) ($value['class'] ?? ''));
        $link->setAttributes((string) ($value['attributes'] ?? ''));
        $link->setTabindex((string) ($value['tabindex'] ?? ''));
        $link->setParameters((string) ($value['parameters'] ?? ''));

        if ($linktype === 'internal') {
            $internalPath = $value['internalPath'] ?? null;
            $internalType = $value['internalType'] ?? null;
            if (is_string($internalPath) && $internalPath !== '' && is_string($internalType)) {
                $element = $this->resolveElement($internalType, $internalPath);
                if ($element) {
                    $link->setElement($element);
                } else {
                    $this->warnings[] = sprintf('Link internal target not found: %s "%s"', $internalType, $internalPath);
                    $link->setLinktype('direct');
                }
            }
        } else {
            $link->setDirect((string) ($value['direct'] ?? ''));
        }

        return $link;
    }

    private function materializeRelation(mixed $value): ?object
    {
        if (!is_array($value)) {
            return null;
        }
        $type = $value['elementType'] ?? null;
        $path = $value['fullpath'] ?? null;
        if (!is_string($type) || !is_string($path) || $path === '') {
            return null;
        }
        $element = $this->resolveElement($type, $path);
        if (!$element) {
            $this->warnings[] = sprintf('Relation target not found: %s "%s"', $type, $path);
        }

        return $element;
    }

    /** @return array<int, object> */
    private function materializeRelations(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }
        $out = [];
        foreach ($value as $entry) {
            $element = $this->materializeRelation($entry);
            if ($element) {
                $out[] = $element;
            }
        }

        return $out;
    }

    private function materializeDate(mixed $value): ?\DateTimeImmutable
    {
        if (is_int($value)) {
            return (new \DateTimeImmutable())->setTimestamp($value);
        }

        return null;
    }

    private function materializeScalar(mixed $value): mixed
    {
        if (is_scalar($value) || $value === null || is_array($value)) {
            return $value;
        }

        return null;
    }

    private function resolveElement(string $type, string $path): ?object
    {
        return match ($type) {
            'object' => Concrete::getByPath($path),
            'asset' => Asset::getByPath($path),
            'document' => Document::getByPath($path),
            default => null,
        };
    }
}
