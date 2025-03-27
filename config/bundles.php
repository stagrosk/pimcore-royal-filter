<?php

use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Pimcore\Bundle\DataHubBundle\PimcoreDataHubBundle;
use Pimcore\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use Pimcore\Bundle\StaticRoutesBundle\PimcoreStaticRoutesBundle;
use Pimcore\Bundle\TinymceBundle\PimcoreTinymceBundle;
use Pimcore\Bundle\UuidBundle\PimcoreUuidBundle;
use PimcoreHeadlessContentBundle\PimcoreHeadlessContentBundle;

return [
    PimcoreSimpleBackendSearchBundle::class => ['all' => true],
    PimcoreStaticRoutesBundle::class => ['all' => true],
    PimcoreTinymceBundle::class => ['all' => true],
    PimcoreUuidBundle::class => ['all' => true],
    PimcoreDataHubBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    PimcoreHeadlessContentBundle::class => ['all' => true],
];
