<?php

namespace App\GraphQL\Mutation;

use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\MutationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractMutation implements EventSubscriberInterface
{
    #[ArrayShape([MutationEvents::PRE_BUILD => 'string[]'])]
    /**
     * @return array<string, array<int,string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MutationEvents::PRE_BUILD => ['doPreBuild'],
        ];
    }

    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent $event
     *
     * @return array
     */
    abstract public function onPreBuild(MutationTypeEvent $event): array;

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
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent $event
     */
    public function doPreBuild(MutationTypeEvent $event): void
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
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent $event
     *
     * @return bool
     */
    public function isAllowed(MutationTypeEvent $event): bool
    {
        $enabledClientNames = array_merge($this->getEnabledClientNames(), \Pimcore::getContainer()->getParameter('data_hub_enabled_client_names') ?? []);

        if (!empty($enabledClientNames) && !in_array($event->getContext()['clientname'], $enabledClientNames, true)) {
            return false;
        }

        return true;
    }
}