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
        $this->addSql("
            UPDATE settings_store
               SET id = REPLACE(id, 'BUNDLE_INSTALLED__Pimcore', 'BUNDLE_INSTALLED__OpenDxp')
             WHERE id LIKE 'BUNDLE_INSTALLED__Pimcore%'
        ");

        // OpenDXP removed all migrations from Pimcore\Bundle\CoreBundle\Migrations namespace.
        // Legacy entries in migration_versions would make Doctrine try to re-run them.
        $this->addSql("
            DELETE FROM migration_versions
             WHERE version LIKE 'Pimcore\\\\Bundle\\\\CoreBundle\\\\Migrations%'
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            UPDATE settings_store
               SET id = REPLACE(id, 'BUNDLE_INSTALLED__OpenDxp', 'BUNDLE_INSTALLED__Pimcore')
             WHERE id LIKE 'BUNDLE_INSTALLED__OpenDxp%'
        ");
    }
}
