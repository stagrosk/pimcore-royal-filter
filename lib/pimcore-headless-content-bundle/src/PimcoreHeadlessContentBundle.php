<?php

namespace PimcoreHeadlessContentBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;

class PimcoreHeadlessContentBundle extends AbstractPimcoreBundle
{
    /**
     * @inheritDoc
     */
    public function getNiceName(): string
    {
        return 'Pimcore Headless CMS';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Extends Pimcore to act as a Headless CMS';
    }

    /**
     * @inheritDoc
     */
    public function getInstaller(): ?InstallerInterface
    {
        return null;
    }
}
