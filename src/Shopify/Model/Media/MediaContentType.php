<?php

namespace App\Shopify\Model\Media;

use InvalidArgumentException;

class MediaContentType {
    public const MEDIA_TYPE_EXTERNAL_VIDEO = 'EXTERNAL_VIDEO';
    public const MEDIA_TYPE_IMAGE = 'IMAGE';
    public const MEDIA_TYPE_MODEL_3D = 'MODEL_3D';
    public const MEDIA_TYPE_VIDEO = 'VIDEO';

    public const AVAILABLE_TYPES = [
        self::MEDIA_TYPE_EXTERNAL_VIDEO,
        self::MEDIA_TYPE_IMAGE,
        self::MEDIA_TYPE_MODEL_3D,
        self::MEDIA_TYPE_VIDEO,
    ];

    /**
     * @var string
     */
    private string $type = self::MEDIA_TYPE_IMAGE;

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setType(string $type): void {
        if (in_array($type, self::AVAILABLE_TYPES, true)) {
            $this->type = $type;
        } else {
            throw new InvalidArgumentException("Invalid MediaContentType: " . $type);
        }
    }
}
