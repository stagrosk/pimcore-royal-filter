<?php

namespace PimcoreVendureBridgeBundle\Command;

use Pimcore\Model\DataObject\AbstractObject;
use PimcoreVendureBridgeBundle\Component\BatchProcessing\BatchListing;
use PimcoreVendureBridgeBundle\Service\AmqpService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddObjectsToQueueCommand extends Command
{
    public const BATCH_SIZE = 100;
    const AMQP_QUEUE_UPDATE = 'update';
    const AMQP_QUEUE_DELETE = 'delete';
    const ARG_OBJECT_TYPE = 'objectType';
    const ARG_PATH = 'path';

    private array $allowedObjectTypes = [];

    private AmqpService $amqpService;

    /**
     * @param array $allowedObjectTypes
     * @param \PimcoreVendureBridgeBundle\Service\AmqpService $amqpService
     */
    public function __construct(
        array       $allowedObjectTypes,
        AmqpService $amqpService
    ) {
        parent::__construct();
        $this->allowedObjectTypes = $allowedObjectTypes;
        $this->amqpService = $amqpService;
    }

    protected function configure()
    {
        $this
            ->setName('pimcore-vendure:queue:add-all-objects')
            ->setDescription('Add all objects to queue to be exported to vendure')
            ->addOption(
                self::ARG_OBJECT_TYPE,
                'o',
                InputOption::VALUE_REQUIRED,
                'Define one of enabled object types'
            )->addOption(
                self::ARG_PATH,
                'p',
                InputOption::VALUE_OPTIONAL,
                'Only from a specific path'
            );
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $objectType = $input->getOption(self::ARG_OBJECT_TYPE);
        $path = $input->getOption(self::ARG_PATH);

        if (empty($objectType)) {
            $output->writeln('Object type cannot be empty. Use: ' . implode('|', $this->allowedObjectTypes));
            return Command::FAILURE;
        }

        if (!in_array($objectType, $this->allowedObjectTypes)) {
            $output->writeln('Object type: ' . $objectType . ' not supported. Use: ' . implode('|', $this->allowedObjectTypes));
            return Command::FAILURE;
        }

        $output->writeln('Starting to process objects type: ' . $objectType);

        try {
            $className = 'Pimcore\\Model\\DataObject\\' . $objectType;
            $list = $className::getList();
            $list->setObjectTypes([AbstractObject::OBJECT_TYPE_OBJECT, AbstractObject::OBJECT_TYPE_VARIANT]);

            if(!empty($path)) {
                $list->setCondition('o_path LIKE ?', ['%' .  $path . '%']);
            }

            $totalCount = $list->getTotalCount();
            if ($totalCount > 0) {
                $iterator = 1;
                $batchListing = new BatchListing($list, self::BATCH_SIZE);

                /** @var \Pimcore\Bundle\EcommerceFrameworkBundle\Model\ProductInterface $object */
                foreach ($batchListing as $object) {
                    $output->writeln(sprintf('[QUEUE ADD - %s] Processing item %s/%s => (%s) %s', $objectType, $iterator++, $totalCount, $object->getId(), $object->getFullPath()));
                    if (!$object->isPublished()) {
                        $this->amqpService->sendToAmqp(self::AMQP_QUEUE_DELETE, [
                            'class' => get_class($object),
                            'type' => $object->getType(),
                            'id' => $object->getId()
                        ]);
                    } else {
                        $this->amqpService->sendToAmqp(self::AMQP_QUEUE_UPDATE, [
                            'class' => get_class($object),
                            'type' => $object->getType(),
                            'id' => $object->getId()
                        ]);
                    }
                }
            } else {
                $output->writeln('Nothing to process ...');
            }

            $output->writeln('Finish');

        } catch (\Exception $exception) {
            $output->writeln($exception);
        }

        return Command::SUCCESS;
    }
}
