<?php

use Agorate\PimcoreDeeplBundle\PimcoreDeeplBundle;
use DivanteTranslationBundle\DivanteTranslationBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use FOS\RestBundle\FOSRestBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use OpenDxp\Bundle\DataHubBundle\PimcoreDataHubBundle;
use OpenDxp\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use OpenDxp\Bundle\StaticRoutesBundle\PimcoreStaticRoutesBundle;
use OpenDxp\Bundle\TinymceBundle\PimcoreTinymceBundle;
use OpenDxp\Bundle\UuidBundle\PimcoreUuidBundle;
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
