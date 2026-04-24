<?php

namespace PimcoreHeadlessContentBundle\EventListener;

use PimcoreHeadlessContentBundle\Helper\SlugGenerator;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;
use OpenDxp\Event\Model\DataObjectEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GenerateSlugListener implements EventSubscriberInterface
{
    private SlugGenerator $slugGenerator;

    /**
     * @param \PimcoreHeadlessContentBundle\Helper\SlugGenerator $slugGenerator
     */
    public function __construct(
        SlugGenerator $slugGenerator
    ) {
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'pimcore.dataobject.preUpdate' => [
                'onPreUpdate',
            ],
        ];
    }

    /**
     * @param \OpenDxp\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     */
    public function onPreUpdate(DataObjectEvent $event): void
    {
        $object = $event->getObject();
        if (!$object instanceof SlugAwareInterface) {
            return;
        }

        $this->slugGenerator->updateSlug($object);
        $this->slugGenerator->updateHandle($object);
    }
}
