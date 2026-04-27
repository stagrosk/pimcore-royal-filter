<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use OpenDxp\Model\DataObject\Adapter;
use OpenDxp\Model\DataObject\Classificationstore;
use OpenDxp\Model\DataObject\Equipment;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Tool;

final class Version20250618201353 extends AbstractMigration
{
    private const TARGET_FOLDER = '/Components/Adapters';

    public function getDescription(): string
    {
        return 'Copy all Equipment objects 1:1 into /Components/Adapters as Adapter';
    }

    public function up(Schema $schema): void
    {
        $targetFolder = Service::createFolderByPath(self::TARGET_FOLDER);

        $list = Equipment::getList();
        $list->setUnpublished(true);

        foreach ($list as $equipment) {
            $adapter = new Adapter();
            $adapter->setParent($targetFolder);
            $adapter->setKey($this->resolveUniqueKey($equipment));
            $adapter->setPublished($equipment->isPublished());

            foreach (Tool::getValidLanguages() as $language) {
                $adapter->setTitle($equipment->getTitle($language), $language);
            }

            $adapter->setCode($equipment->getCode());
            $this->copyMetadata($equipment, $adapter);
            $adapter->setImageGallery($equipment->getImageGallery());
            $adapter->setDrawingImageGallery($equipment->getDrawingImageGallery());
            $adapter->save();
        }
    }

    public function down(Schema $schema): void
    {
    }

    /**
     * If an adapter with the same key already exists under the target folder,
     * suffix with the source equipment id so the migration never fails on duplicates.
     */
    private function resolveUniqueKey(Equipment $equipment): string
    {
        $key = Service::getValidKey($equipment->getKey(), 'object');
        $candidatePath = self::TARGET_FOLDER . '/' . $key;

        if (Adapter::getByPath($candidatePath) instanceof Adapter) {
            return Service::getValidKey($key . '-' . $equipment->getId(), 'object');
        }

        return $key;
    }

    /**
     * Copy classificationstore items + active groups onto the adapter's own,
     * freshly bound store. Assigning the source store directly leaves it bound
     * to the Equipment class id, so the data ends up in Equipment's CS table.
     */
    private function copyMetadata(Equipment $equipment, Adapter $adapter): void
    {
        $source = $equipment->getMetadata();
        if (!$source instanceof Classificationstore) {
            return;
        }

        $target = $adapter->getMetadata();
        if (!$target instanceof Classificationstore) {
            return;
        }

        $target->setItems($source->getItems());
        $target->setActiveGroups($source->getActiveGroups());
    }
}
