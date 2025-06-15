<?php

namespace App\EventSubscriber;

use Pimcore\Event\BundleManager\PathsEvent;

class AdminInterfaceListener
{
    /**
     * @param \Pimcore\Event\BundleManager\PathsEvent $event
     */
    public function addCssFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                ]
            )
        );
    }

    /**
     * @param \Pimcore\Event\BundleManager\PathsEvent $event
     */
    public function addJsFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/admin-static/js/notification-override.js',
                ]
            )
        );
    }
}
