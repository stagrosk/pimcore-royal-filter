<?php

namespace App\Command;

use App\Service\Generator\FilterToProductGenerator;
use App\Service\Generator\WhirlpoolToProductGenerator;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\RoyalFilter;
use Pimcore\Model\DataObject\Whirlpool;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateShopifyProductsCommand extends Command
{
    protected static $defaultName = 'app:generate-shopify-products';

    public const ALLOWED_CLASS_FILTER = RoyalFilter::class;
    public const ALLOWED_CLASS_WHIRLPOOL = Whirlpool::class;
    public const ALLOWED_CLASSES = [
        'filter' => self::ALLOWED_CLASS_FILTER,
        'whirlpool' => self::ALLOWED_CLASS_WHIRLPOOL,
    ];

    /**
     * @param \App\Service\Generator\FilterToProductGenerator $filterToProductGenerator
     * @param \App\Service\Generator\WhirlpoolToProductGenerator $whirlpoolToProductGenerator
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private readonly FilterToProductGenerator $filterToProductGenerator,
        private readonly WhirlpoolToProductGenerator $whirlpoolToProductGenerator,
        private readonly LoggerInterface      $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Run product generate from objects whirlpool and royal-filter')
            ->addOption(
                'class',
                'c',
                InputOption::VALUE_REQUIRED,
                'Class name, Allowed values are ' . implode(', ', array_keys(self::ALLOWED_CLASSES))
            )
            ->addOption(
                'object-id',
                null,
                InputOption::VALUE_OPTIONAL,
                'Optional object id'
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \App\Service\Generator\Exception\NothingToExportException
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
     * @throws \Exception
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $objectId = $input->getOption('object-id');
        if ($objectId) {
            $object = DataObject::getById($objectId);

            if ($object instanceof RoyalFilter) {
                $this->filterToProductGenerator->generateProductForObject($object);
            } else if ($object instanceof Whirlpool) {
                $this->whirlpoolToProductGenerator->generateProductForObject($object);
            } else {
                $this->logger->error(sprintf('Mapper for data object id: %s | type: %s not found!', $object->getId(), $object->getType()));

                return Command::FAILURE;
            }

        } else {
            $class = $input->getOption('class');
            if (!in_array($class, array_keys(self::ALLOWED_CLASSES))) {
                $this->logger->error('Class: ' . $class . ' is not allowed!');

                return Command::FAILURE;
            } else {
                if ($class === self::ALLOWED_CLASS_FILTER) {
                    $this->filterToProductGenerator->setLogger($this->logger);

                    return $this->whirlpoolToProductGenerator->runGenerate();
                } else if ($class === self::ALLOWED_CLASS_WHIRLPOOL) {
                    $this->whirlpoolToProductGenerator->setLogger($this->logger);

                    return $this->whirlpoolToProductGenerator->runGenerate();
                }
            }
        }

        return Command::SUCCESS;
    }
}
