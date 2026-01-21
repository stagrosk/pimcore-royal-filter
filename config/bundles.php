<?php

use Agorate\PimcoreDeeplBundle\PimcoreDeeplBundle;
use DivanteTranslationBundle\DivanteTranslationBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use FOS\RestBundle\FOSRestBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Pimcore\Bundle\DataHubBundle\PimcoreDataHubBundle;
use Pimcore\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use Pimcore\Bundle\StaticRoutesBundle\PimcoreStaticRoutesBundle;
use Pimcore\Bundle\TinymceBundle\PimcoreTinymceBundle;
use Pimcore\Bundle\UuidBundle\PimcoreUuidBundle;
use PimcoreHeadlessContentBundle\PimcoreHeadlessContentBundle;
use PimcoreVendureBridgeBundle\PimcoreVendureBridgeBundle;

return [
    PimcoreSimpleBackendSearchBundle::class => ['all' => true],
    PimcoreStaticRoutesBundle::class => ['all' => true],
    PimcoreTinymceBundle::class => ['all' => true],
    PimcoreUuidBundle::class => ['all' => true],
    PimcoreDataHubBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    PimcoreHeadlessContentBundle::class => ['all' => true],
    PimcoreDeeplBundle::class => ['all' => true],
    DivanteTranslationBundle::class => ['all' => true],
    JMSSerializerBundle::class => ['all' => true],
    FOSRestBundle::class => ['all' => true],
    PimcoreVendureBridgeBundle::class => ['all' => true],
];
