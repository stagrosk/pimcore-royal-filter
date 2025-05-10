<?php

namespace App\Shopify\Model\Media;

use App\Shopify\Model\IShopifyModel;

class FileCreateInput implements IShopifyModel
{
    /**
     * @param ?string $alt The alternative text description of the file.
     * @param ?FileContentTypeEnum $contentType The file content type. If omitted, then Shopify will attempt to determine the content type during file processing.
     * @param ?FileCreateInputDuplicateResolutionModeEnum $duplicateResolutionMode How to handle if filename is already in use. Defaults to APPEND_UUID.
     * @param ?string $filename When provided, the file will be created with the given filename, otherwise the filename in the originalSource will be used.
     * @param string $originalSource An external URL (for images only) or a staged upload URL.
     */
    public function __construct(
        private string $originalSource,
        private ?string $alt = null,
        private ?FileContentTypeEnum $contentType = null,
        private ?FileCreateInputDuplicateResolutionModeEnum $duplicateResolutionMode = null,
        private ?string $filename = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];

        if ($this->getAlt() !== null) {
            $data['alt'] = $this->getAlt();
        }

        if ($this->getContentType() !== null) {
            $data['contentType'] = $this->getContentType()->value;
        }

        if ($this->getDuplicateResolutionMode() !== null) {
            $data['duplicateResolutionMode'] = $this->getDuplicateResolutionMode()->value;
        }

        if ($this->getFilename() !== null) {
            $data['filename'] = $this->getFilename();
        }

        $data['originalSource'] = $this->getOriginalSource();

        return $data;
    }

    /**
     * Returns the alternative text description of the file.
     *
     * @return ?string
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * Returns the file content type.
     *
     * @return ?FileContentTypeEnum
     */
    public function getContentType(): ?FileContentTypeEnum
    {
        return $this->contentType;
    }

    /**
     * Returns how to handle if filename is already in use.
     *
     * @return ?FileCreateInputDuplicateResolutionModeEnum
     */
    public function getDuplicateResolutionMode(): ?FileCreateInputDuplicateResolutionModeEnum
    {
        return $this->duplicateResolutionMode;
    }

    /**
     * Returns the filename to be used for the created file.
     *
     * @return ?string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Returns the source URL for the file.
     *
     * @return string
     */
    public function getOriginalSource(): string
    {
        return $this->originalSource;
    }

    /**
     * Sets the alternative text description of the file.
     *
     * @param ?string $alt
     * @return void
     */
    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * Sets the file content type.
     *
     * @param ?FileContentTypeEnum $contentType
     * @return void
     */
    public function setContentType(?FileContentTypeEnum $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * Sets how to handle if filename is already in use.
     *
     * @param ?FileCreateInputDuplicateResolutionModeEnum $duplicateResolutionMode
     * @return void
     */
    public function setDuplicateResolutionMode(?FileCreateInputDuplicateResolutionModeEnum $duplicateResolutionMode): void
    {
        $this->duplicateResolutionMode = $duplicateResolutionMode;
    }

    /**
     * Sets the filename for the created file.
     *
     * @param ?string $filename
     * @return void
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Sets the source URL for the file.
     *
     * @param string $originalSource
     * @return void
     */
    public function setOriginalSource(string $originalSource): void
    {
        $this->originalSource = $originalSource;
    }
}
