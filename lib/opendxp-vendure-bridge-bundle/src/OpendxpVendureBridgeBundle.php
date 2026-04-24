<?php

namespace OpendxpVendureBridgeBundle;

use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\Installer\InstallerInterface;
use OpenDxp\Routing\RouteReferenceInterface;

class OpendxpVendureBridgeBundle extends AbstractOpenDxpBundle
{
    /**
     * @return string
     */
    protected function getModelNamespace(): string
    {
        return 'OpendxpPimBundle\Model';
    }

    /**
     * @inheritDoc
     */
    public function getNiceName(): string
    {
        return 'OpenDXP <-> Vendure bridge';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'OpenDXP Vendure bridge bundle to connect data from opendxp to vendure and back';
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
