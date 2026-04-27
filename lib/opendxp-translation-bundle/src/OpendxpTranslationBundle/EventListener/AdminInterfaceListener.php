<?php

namespace OpendxpTranslationBundle\EventListener;

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
                    '/bundles/opendxptranslation/js/pimcore/startup.js',
                    '/bundles/opendxptranslation/js/pimcore/object/elementservice.js',
                    '/bundles/opendxptranslation/js/pimcore/object/tags/input.js',
                    '/bundles/opendxptranslation/js/pimcore/object/tags/textarea.js',
                    '/bundles/opendxptranslation/js/pimcore/object/tags/wysiwyg.js',
                ]
            )
        );
    }
}
