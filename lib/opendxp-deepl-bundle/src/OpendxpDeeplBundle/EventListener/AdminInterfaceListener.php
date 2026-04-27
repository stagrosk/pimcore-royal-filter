<?php

namespace OpendxpDeeplBundle\EventListener;

use OpenDxp\Event\BundleManager\PathsEvent;

class AdminInterfaceListener
{
    /**
     * @param \OpenDxp\Event\BundleManager\PathsEvent $event
     */
    public function addJsFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/bundles/opendxpdeepl/js/deepl-translation/startup.js',
                ]
            )
        );
    }
}
