<?php

declare(strict_types=1);

namespace App\Migrations;

use App\Component\BatchProcessing\BatchListing;
use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use OpenDxp\Model\DataObject\PaperCartridge;
use OpenDxp\Model\DataObject\Whirlpool;
use Psr\Log\LoggerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325195058 extends AbstractMigration
{
    public const CLASSIFICATION_STORE_ID = 1;

    private const BATCH_SIZE = 100;

    private ClassificationStoreService $classificationStoreService;

    private ClassificationStoreHelper $classificationStoreHelper;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Connection                                  $connection,
        readonly LoggerInterface                    $logger
    ) {
        parent::__construct($connection, $logger);


        $this->classificationStoreService = \OpenDxp::getContainer()->get(ClassificationStoreService::class);
        $this->classificationStoreHelper = \OpenDxp::getContainer()->get(ClassificationStoreHelper::class);
    }

    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @throws \Exception
     * @return void
     */
    public function up(Schema $schema): void
    {
        $classesToProcess = [
            PaperCartridge::class,
            Whirlpool::class,
        ];

        foreach ($classesToProcess as $class) {
            $list = $class::getList();
            $list->setUnpublished(true);
            $totalCount = $list->getTotalCount();
            $this->write('<info> Class: ' . $class . ' -> Total count to be processed: ' . $totalCount . '</info>');

            $iterator = 1;
            $batchListing = new BatchListing($list, self::BATCH_SIZE);
            foreach ($batchListing as $object) {
                if ($class === Whirlpool::class) {
                    $this->migrateParametersToClassificationStoreForWhirlpool($object);
                } else {
                    $this->migrateParametersToClassificationStoreForPaperCartridge($object);
                }

                if (($iterator % self::BATCH_SIZE) === 0) {
                    \OpenDxp::collectGarbage();
                    $this->write('-------- BATCH CLEANUP --------');
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool $whirlpool
     *
     * @throws \Exception
     * @return void
     */
    private function migrateParametersToClassificationStoreForWhirlpool(Whirlpool $whirlpool): void
    {
        // map parameters to array to be processed
        $properties = $whirlpool->getWhirlpoolProperties()?->getWhirlpoolProperties();
        if (!$properties) {
            return;
        }

        $jetsType = $properties->getJetsType();

        $mappedProperties = [
            'heatingPerformance' => str_replace(",", ".", str_replace(" kW", "", $properties->getHeatingPerformance() ?? '')),
            'dimensions' => $properties->getDimensions(),
            'waterHeatingRate' => $properties->getWaterHeatingRate(),
            'construction' => $properties->getConstruction(),
            'volumeOfWater' => $properties->getVolumeOfWater(),
            'weightWithoutWater' => $properties->getWeightWithoutWater(),
            'weightWithWater' => $properties->getWeightWithWater(),
            'jetsType' => $jetsType ? reset($jetsType) : null,
            'amountOfJets' => $properties->getAmountOfJets(),
            'numberOfPersons' => $properties->getNumberOfPersons(),
            'solinator' => $properties->getSolinator(),
            'ozonator' => $properties->getOzonator(),
            'uvLamp' => $properties->getUvLamp(),
        ];

        // get group config
        $groupConfig = $this->classificationStoreService->processGroupConfig(self::CLASSIFICATION_STORE_ID, 'whirlpoolProperties');

        // load keyConfig by name in array
        foreach ($mappedProperties as $propertyName => $value) {
            $mappedProperties[$propertyName] = [
                'value' => $value,
                'groupConfig' => $groupConfig,
                'keyConfig' => $this->classificationStoreService->processKeyConfig(self::CLASSIFICATION_STORE_ID, $propertyName),
                'language' => 'default'
            ];
        }

        $this->classificationStoreHelper->fillObjectDataOnClassificationStore($whirlpool, $mappedProperties);
    }

    /**
     * @param \Pimcore\Model\DataObject\PaperCartridge $cartridge
     *
     * @throws \Exception
     * @return void
     */
    private function migrateParametersToClassificationStoreForPaperCartridge(PaperCartridge $cartridge): void
    {
        $mappedPropertiesBase = [
            'onTop' => $cartridge->getTop() ? ucfirst($cartridge->getTop()) : null,
            'onBottom' => $cartridge->getBottom() ? ucfirst($cartridge->getBottom()) : null,
            'height' => $cartridge->getLength(),
            'diameter' => $cartridge->getDiameter(),
            'centerDiameterTop' => $cartridge->getCenterDiameterTop(),
            'centerDiameterBottom' => $cartridge->getCenterDiameterBottom(),
        ];

        $groupConfigFilter = $this->classificationStoreService->processGroupConfig(self::CLASSIFICATION_STORE_ID, 'filterDimensions');
        foreach ($mappedPropertiesBase as $propertyName => $value) {
            $mappedProperties[$propertyName] = [
                'value' => $value,
                'groupConfig' => $groupConfigFilter,
                'keyConfig' => $this->classificationStoreService->processKeyConfig(self::CLASSIFICATION_STORE_ID, $propertyName),
                'language' => 'default'
            ];
        }

        // fill thread only if is filled in
        if ($mappedPropertiesBase['onTop'] === 'Thread' || $mappedPropertiesBase['onBottom'] === 'Thread') {
            $mappedPropertiesThread = [
                'threadPosition' => $cartridge->getThreadPosition() ? ucfirst($cartridge->getThreadPosition()) : null,
                'pitch' => $cartridge->getPitch(),
                'ribHeight' => $cartridge->getRibHeight(),
                'diameterWithThread' => $cartridge->getDiameterWithThread(),
                'diameterWithoutThread' => $cartridge->getDiameterWithoutThread(),
            ];

            $groupConfigThread = $this->classificationStoreService->processGroupConfig(self::CLASSIFICATION_STORE_ID, 'threadDimensions');
            foreach ($mappedPropertiesThread as $propertyName => $value) {
                $mappedProperties[$propertyName] = [
                    'value' => $value,
                    'groupConfig' => $groupConfigThread,
                    'keyConfig' => $this->classificationStoreService->processKeyConfig(self::CLASSIFICATION_STORE_ID, $propertyName),
                    'language' => 'default'
                ];
            }
        }

        $this->classificationStoreHelper->fillObjectDataOnClassificationStore($cartridge, $mappedProperties);
    }
}
