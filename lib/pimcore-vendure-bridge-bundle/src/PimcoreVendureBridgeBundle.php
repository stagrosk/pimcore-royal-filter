<?php

namespace PimcoreVendureBridgeBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Pimcore\Routing\RouteReferenceInterface;

class PimcoreVendureBridgeBundle extends AbstractPimcoreBundle
{
    /**
     * @return string
     */
    protected function getModelNamespace(): string
    {
        return 'PimcorePimBundle\Model';
    }

    /**
     * @inheritDoc
     */
    public function getNiceName(): string
    {
        return 'Pimcore <-> Vendure bridge';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Pimcore Vendure bridge bundle to connect data from pimcore to vendure and back';
    }

    /**
     * @inheritDoc
     */
    public function getInstaller(): ?InstallerInterface
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAdminIframePath() //: RouteReferenceInterface|string|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getJsPaths(): array
    {
        return [
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCssPaths(): array
    {
        return [
        ];
    }

    /**
     * @inheritDoc
     */
    public function getEditmodeJsPaths(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getEditmodeCssPaths(): array
    {
        return [];
    }
}
