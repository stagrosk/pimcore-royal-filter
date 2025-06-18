<?php

namespace App\GraphQL\Query;

use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\QueryEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractQuery implements EventSubscriberInterface
{
    #[ArrayShape([QueryEvents::PRE_BUILD => 'string[]'])]
    /**
     * @return array<string, array<int,string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            QueryEvents::PRE_BUILD => ['doPreBuild'],
        ];
    }

    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @return array
     */
    abstract public function onPreBuild(QueryTypeEvent $event): array;

    /**
     * @return string
     */
    abstract public function getOperationName(): string;

    /**
     * @return array<string>
     */
    public function getEnabledClientNames(): array
    {
        return [];
    }

    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     */
    public function doPreBuild(QueryTypeEvent $event): void
    {
        $config = $event->getConfig();
        $opName = $this->getOperationName();

        if (!$this->isAllowed($event)) {
            return;
        }

        $config['fields'][$opName] = fn () => $this->onPreBuild($event);

        $event->setConfig($config);
    }

    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @return bool
     */
    public function isAllowed(QueryTypeEvent $event): bool
    {
        $enabledClientNames = array_merge($this->getEnabledClientNames(), \Pimcore::getContainer()->getParameter('data_hub_enabled_client_names') ?? []);

        if (!empty($enabledClientNames) && !in_array($event->getContext()['clientname'], $enabledClientNames, true)) {
            return false;
        }

        return true;
    }
}
