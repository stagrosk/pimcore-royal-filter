<?php

namespace App\Service\Generator;

use App\Component\BatchProcessing\BatchListing;
use App\Service\Generator\Exception\NothingToExportException;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\Document\Listing;
use Psr\Log\LoggerInterface;

class BaseProductGenerator
{
    public const BATCH_SIZE = 100;

    protected ?LoggerInterface $logger = null;

    private string $className;

    /**
     * @throws \App\Service\Generator\Exception\NothingToExportException
     * @return bool
     */
    public function runGenerate(): bool
    {
        // prepare list
        $list = $this->prepareList();

        // process list
        return $this->processList($list);
    }

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Product
     */
    public function generateProductForObject(Concrete $object): Product
    {
        throw new \Exception('Implement custom generator instead of base one');
    }

    /**
     * @param \Pimcore\Model\DataObject\Listing|\Pimcore\Model\Document\Listing $list
     *
     * @throws \App\Service\Generator\Exception\NothingToExportException
     * @return bool
     */
    public function processList(\Pimcore\Model\DataObject\Listing|Listing $list): bool
    {
        $totalCount = $list->getTotalCount();
        $this->getLogger()->notice('Total items to be processed: ' . $totalCount);
        if ($totalCount > 0) {
            $iterator = 1;
            $batchListing = new BatchListing($list, self::BATCH_SIZE);
            foreach ($batchListing as $object) {
                $startMicroTime = microtime(true);
                $this->generateProductForObject($object);

                $this->getLogger()->notice('[Export] Processing object ' . $iterator . '/' . $totalCount . ' TIME: ' . round(microtime(true) - $startMicroTime, 3) . 's');

                if (($iterator++ % self::BATCH_SIZE) === 0) {
                    $this->getLogger()->notice('[Export] Commit data (batch)');
                }
            }

            // final commit data
            $this->getLogger()->notice('[Export] Commit data (final)');
        } else {
            $message = '[Export] Nothing to be exported!';
            $this->getLogger()->notice($message);

            throw new NothingToExportException($message);
        }

        return true;
    }

    /**
     * @return \Pimcore\Model\DataObject\Listing|\Pimcore\Model\Document\Listing
     */
    public function prepareList(): \Pimcore\Model\DataObject\Listing|Listing {
        // list
        $list = $this->className::getList();

        // prepare conditions
        $this->prepareListConditions($list);

        return $list;
    }

    /**
     * @param \Pimcore\Model\DataObject\Listing|\Pimcore\Model\Document\Listing $list
     */
    public function prepareListConditions(\Pimcore\Model\DataObject\Listing|Listing $list): void
    {
        $list->setUnpublished(false);
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }
}
