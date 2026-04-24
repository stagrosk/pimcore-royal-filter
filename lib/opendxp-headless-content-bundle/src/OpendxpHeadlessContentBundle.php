<?php

namespace OpendxpHeadlessContentBundle;

use OpenDxp\Extension\Bundle\AbstractOpenDxpBundle;
use OpenDxp\Extension\Bundle\Installer\InstallerInterface;

class OpendxpHeadlessContentBundle extends AbstractOpenDxpBundle
{
    /**
     * @inheritDoc
     */
    public function getNiceName(): string
    {
        return 'OpenDXP Headless CMS';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Extends OpenDXP to act as a Headless CMS';
    }

    /**
     * @inheritDoc
     */
    public function getInstaller(): ?InstallerInterface
    {
        return null;
    }
}
