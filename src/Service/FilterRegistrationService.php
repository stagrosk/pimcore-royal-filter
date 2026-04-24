<?php

declare(strict_types=1);

namespace App\Service;

use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject\Data\Hotspotimage;
use OpenDxp\Model\DataObject\Data\ImageGallery;
use OpenDxp\Model\DataObject\FilterRegistration;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Model\DataObject\Whirlpool;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilterRegistrationService
{
    private const BASE_PATH = '/FilterRegistrations';

    public const STATUS_NEW = 'new';

    public const ALLOWED_TOP_TYPES = ['throughHole', 'thread', 'stub'];
    public const ALLOWED_BOTTOM_TYPES = ['throughHole', 'thread', 'stub'];

    public const PHOTO_ZONES = [
        'sideView', 'top', 'bottom', 'withRuler', 'labelSticker',
        'hotTubExterior', 'filterChamber', 'filterSideView', 'filterBottom', 'otherPhotos',
    ];

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function createRegistration(array $data, array $uploadedFiles): FilterRegistration
    {
        $datePath = sprintf('%s/%s/%s', date('Y'), date('n'), date('j'));
        $dateFolder = self::BASE_PATH . '/' . $datePath;

        $registration = new FilterRegistration();
        $registration->setPublished(true);
        $registration->setParent(Service::createFolderByPath($dateFolder));
        $registration->setKey(Service::getValidKey(
            sprintf('%s-%s', $data['email'] ?? 'no-email', date('His')),
            'object'
        ));

        // General
        $registration->setStatus(self::STATUS_NEW);

        // Link to existing whirlpool if provided
        if (!empty($data['hotTubId'])) {
            $whirlpool = Whirlpool::getById((int) $data['hotTubId']);
            if ($whirlpool instanceof Whirlpool) {
                $registration->setHotTubRef($whirlpool);
            }
        }

        // Hot tub info
        $registration->setHotTubBrand($data['brand'] ?? null);
        $registration->setHotTubModel($data['model'] ?? null);
        $registration->setYearOfManufacture($data['yearOfManufacture'] ?? null);
        $registration->setVolume($data['volume'] ?? null);
        $registration->setJets($data['jets'] ?? null);
        $registration->setDimensionsLength($data['dimensionsLength'] ?? null);
        $registration->setDimensionsWidth($data['dimensionsWidth'] ?? null);
        $registration->setDimensionsHeight($data['dimensionsHeight'] ?? null);
        $registration->setHotTubNote($data['hotTubNote'] ?? null);

        // Filter info
        $registration->setOriginalLabel($data['originalLabel'] ?? null);
        $registration->setFilterHeight($data['filterHeight'] ?? null);
        $registration->setFilterDiameter($data['filterDiameter'] ?? null);
        $registration->setTopType($data['topType'] ?? null);
        $registration->setTopHoleDiameter($data['topHoleDiameter'] ?? null);
        $registration->setTopThreadOuterDiameter($data['topThreadOuterDiameter'] ?? null);
        $registration->setTopThreadInnerDiameter($data['topThreadInnerDiameter'] ?? null);
        $registration->setTopThreadPitch($data['topThreadPitch'] ?? null);
        $registration->setTopThreadRibHeight($data['topThreadRibHeight'] ?? null);
        $registration->setTopStubDescription($data['topStubDescription'] ?? null);
        $registration->setBottomType($data['bottomType'] ?? null);
        $registration->setBottomHoleDiameter($data['bottomHoleDiameter'] ?? null);
        $registration->setBottomThreadOuterDiameter($data['bottomThreadOuterDiameter'] ?? null);
        $registration->setBottomThreadInnerDiameter($data['bottomThreadInnerDiameter'] ?? null);
        $registration->setBottomThreadPitch($data['bottomThreadPitch'] ?? null);
        $registration->setBottomThreadRibHeight($data['bottomThreadRibHeight'] ?? null);
        $registration->setBottomStubDescription($data['bottomStubDescription'] ?? null);
        $registration->setFilterNote($data['filterNote'] ?? null);

        // Contact
        $registration->setEmail($data['email'] ?? null);
        $registration->setPhone($data['phone'] ?? null);

        // Save first to get ID for asset folder
        $registration->save();

        // Upload photos
        if (!empty($uploadedFiles)) {
            $photos = $this->uploadPhotos($registration, $uploadedFiles, $datePath);
            $registration->setPhotos($photos);
            $registration->save();
        }

        $this->logger->info(sprintf(
            '[FilterRegistration] Created registration #%d for %s',
            $registration->getId(),
            $data['email'] ?? 'no-email'
        ));

        return $registration;
    }

    private function uploadPhotos(FilterRegistration $registration, array $uploadedFiles, string $datePath): ImageGallery
    {
        $assetFolder = Asset\Service::createFolderByPath(
            sprintf('%s/%s/%d', self::BASE_PATH, $datePath, $registration->getId())
        );

        $hotspotImages = [];

        foreach ($uploadedFiles as $zone => $files) {
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $index => $file) {
                if (!$file instanceof UploadedFile || !$file->isValid()) {
                    $this->logger->warning(sprintf(
                        '[FilterRegistration] Skipping invalid file in zone "%s" for registration #%d',
                        $zone, $registration->getId()
                    ));
                    continue;
                }

                $filename = Service::getValidKey(
                    sprintf('%s-%d-%s', $zone, $index + 1, $file->getClientOriginalName()),
                    'asset'
                );

                $asset = new Asset\Image();
                $asset->setParent($assetFolder);
                $asset->setFilename($filename);
                $asset->setData(file_get_contents($file->getPathname()));
                $asset->save();

                $hotspotImages[] = new Hotspotimage($asset);
            }
        }

        return new ImageGallery($hotspotImages);
    }
}
