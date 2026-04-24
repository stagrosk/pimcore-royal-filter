<?php

namespace App\EventSubscriber;

use OpenDxp\Event\BundleManager\PathsEvent;
use OpenDxp\Event\BundleManagerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminInterfaceListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BundleManagerEvents::CSS_PATHS => 'addCssFiles',
            BundleManagerEvents::JS_PATHS => 'addJsFiles',
        ];
    }

    /**
     * @param \OpenDxp\Event\BundleManager\PathsEvent $event
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
     * @param \OpenDxp\Event\BundleManager\PathsEvent $event
     */
    public function addJsFiles(PathsEvent $event): void
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [
                    '/admin-static/js/notification-override.js',
                    '/admin-static/js/filter-config-dependent-select.js',
                    '/admin-static/js/translation-override.js',
                ]
            )
        );
    }
}
