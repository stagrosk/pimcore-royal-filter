<?php

use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use FOS\RestBundle\FOSRestBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use OpenDxp\Bundle\ApplicationLoggerBundle\OpenDxpApplicationLoggerBundle;
use OpenDxp\Bundle\CustomReportsBundle\OpenDxpCustomReportsBundle;
use OpenDxp\Bundle\DataHubBundle\OpenDxpDataHubBundle;
use OpenDxp\Bundle\GenericExecutionEngineBundle\OpenDxpGenericExecutionEngineBundle;
use OpenDxp\Bundle\GlossaryBundle\OpenDxpGlossaryBundle;
use OpenDxp\Bundle\SeoBundle\OpenDxpSeoBundle;
use OpenDxp\Bundle\SimpleBackendSearchBundle\OpenDxpSimpleBackendSearchBundle;
use OpenDxp\Bundle\StaticRoutesBundle\OpenDxpStaticRoutesBundle;
use OpenDxp\Bundle\TinymceBundle\OpenDxpTinymceBundle;
use OpenDxp\Bundle\UuidBundle\OpenDxpUuidBundle;
use OpenDxp\Bundle\WordExportBundle\OpenDxpWordExportBundle;
use OpenDxp\Bundle\XliffBundle\OpenDxpXliffBundle;
use OpendxpDeeplBundle\OpendxpDeeplBundle;
use OpendxpHeadlessContentBundle\OpendxpHeadlessContentBundle;
use OpendxpTranslationBundle\OpendxpTranslationBundle;
use OpendxpVendureBridgeBundle\OpendxpVendureBridgeBundle;

return [
    OpenDxpApplicationLoggerBundle::class => ['all' => true],
    OpenDxpCustomReportsBundle::class => ['all' => true],
    OpenDxpGlossaryBundle::class => ['all' => true],
    OpenDxpSeoBundle::class => ['all' => true],
    OpenDxpSimpleBackendSearchBundle::class => ['all' => true],
    OpenDxpStaticRoutesBundle::class => ['all' => true],
    OpenDxpTinymceBundle::class => ['all' => true],
    OpenDxpUuidBundle::class => ['all' => true],
    OpenDxpWordExportBundle::class => ['all' => true],
    OpenDxpXliffBundle::class => ['all' => true],
    OpenDxpGenericExecutionEngineBundle::class => ['all' => true],
    OpenDxpDataHubBundle::class => ['all' => true],
    DoctrineMigrationsBundle::class => ['all' => true],
    JMSSerializerBundle::class => ['all' => true],
    FOSRestBundle::class => ['all' => true],
    OpendxpHeadlessContentBundle::class => ['all' => true],
    OpendxpDeeplBundle::class => ['all' => true],
    OpendxpTranslationBundle::class => ['all' => true],
    OpendxpVendureBridgeBundle::class => ['all' => true],
];
