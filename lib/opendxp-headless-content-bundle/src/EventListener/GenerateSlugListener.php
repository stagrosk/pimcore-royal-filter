<?php

namespace OpendxpHeadlessContentBundle\EventListener;

use OpendxpHeadlessContentBundle\Helper\SlugGenerator;
use OpendxpHeadlessContentBundle\Model\SlugAwareInterface;
use OpenDxp\Event\DataObjectEvents;
use OpenDxp\Event\Model\DataObjectEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GenerateSlugListener implements EventSubscriberInterface
{
    private SlugGenerator $slugGenerator;

    /**
     * @param \OpendxpHeadlessContentBundle\Helper\SlugGenerator $slugGenerator
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
            DataObjectEvents::PRE_UPDATE => [
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
