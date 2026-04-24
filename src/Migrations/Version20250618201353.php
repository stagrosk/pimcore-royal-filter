<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use OpenDxp\Model\DataObject\Adapter;
use OpenDxp\Model\DataObject\Equipment;
use OpenDxp\Model\DataObject\Service;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618201353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert equipment to adapter object';
    }

    public function up(Schema $schema): void
    {
        $list = Equipment::getList();
        $list->setUnpublished(true);
        foreach ($list as $equipment) {
            $adapter = new Adapter();

            $adapter->setPublished($equipment->isPublished());
            $adapter->setKey($equipment->getKey());
            $parentFullPath = $equipment->getParent()->getFullPath();
            $adapter->setParent(Service::createFolderByPath(str_replace('Equipments', 'Adapters', $parentFullPath)));

            $adapter->setTitle($equipment->getTitle());
            $adapter->setCode($equipment->getCode());
            $adapter->setMetadata($equipment->getMetadata());
            $adapter->setImageGallery($equipment->getImageGallery());
            $adapter->setDrawingImageGallery($equipment->getDrawingImageGallery());
            $adapter->save();
        }
    }

    public function down(Schema $schema): void
    {
    }
}
