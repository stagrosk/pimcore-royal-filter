<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260424OpenDxpRename extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename Pimcore → OpenDxp identifiers in settings_store + migration_versions';
    }

    public function up(Schema $schema): void
    {
        // 1) Rename installed-bundle markers; OpenDXP will reconcile the class-name suffix on next boot.
        $this->addSql(
            "UPDATE settings_store
                SET id = REPLACE(id, 'BUNDLE_INSTALLED__Pimcore', 'BUNDLE_INSTALLED__OpenDxp')
              WHERE id LIKE 'BUNDLE_INSTALLED__Pimcore%'"
        );

        // 2) Delete leftover stale rows whose class suffix still says PimcoreXxxBundle —
        //    OpenDXP registers OpenDxpXxxBundle by itself, the legacy markers point to gone classes.
        $this->addSql(
            "DELETE FROM settings_store
              WHERE id LIKE 'BUNDLE_INSTALLED__OpenDxp%Pimcore%Bundle'"
        );

        // 3) OpenDXP removed all Pimcore\Bundle\{Core,DataHub,Uuid}Bundle\Migrations classes.
        //    Use REGEXP so we don't have to chase MySQL backslash-escape semantics.
        $this->addSql(
            "DELETE FROM migration_versions
              WHERE version REGEXP '^Pimcore\\\\\\\\Bundle\\\\\\\\(Core|DataHub|Uuid)Bundle\\\\\\\\'"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "UPDATE settings_store
                SET id = REPLACE(id, 'BUNDLE_INSTALLED__OpenDxp', 'BUNDLE_INSTALLED__Pimcore')
              WHERE id LIKE 'BUNDLE_INSTALLED__OpenDxp%'"
        );
    }
}
