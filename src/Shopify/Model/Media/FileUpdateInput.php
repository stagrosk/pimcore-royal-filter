<?php

namespace App\Shopify\Model\Media;

use App\Shopify\Model\IShopifyModel;

class FileUpdateInput implements IShopifyModel
{
    /**
     * @param string $id The ID of the file to be updated.
     * @param string|null $alt The alternative text description of the file.
     * @param string|null $filename The name of the file including its extension.
     * @param string|null $originalSource The source from which to update a media image or generic file. An external URL (for images only) or a staged upload URL.
     * @param string|null $previewImageSource The source from which to update the media preview image. May be an external URL or a staged upload URL.
     * @param array $referencesToAdd The IDs of the references to add to the file. Currently only accepts product IDs.
     * @param array $referencesToRemove The IDs of the references to remove from the file. Currently only accepts product IDs.
     */
    public function __construct(
        private string $id,
        private ?string $alt = null,
        private ?string $filename = null,
        private ?string $originalSource = null,
        private ?string $previewImageSource = null,
        private array $referencesToAdd = [],
        private array $referencesToRemove = []
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [
            'id' => $this->getId(),
        ];

        if ($this->getAlt() !== null) {
            $data['alt'] = $this->getAlt();
        }

        if ($this->getFilename() !== null) {
            $data['filename'] = $this->getFilename();
        }

        if ($this->getOriginalSource() !== null) {
            $data['originalSource'] = $this->getOriginalSource();
        }

        if ($this->getPreviewImageSource() !== null) {
            $data['previewImageSource'] = $this->getPreviewImageSource();
        }

        if (!empty($this->getReferencesToAdd())) {
            $data['referencesToAdd'] = $this->getReferencesToAdd();
        }

        if (!empty($this->getReferencesToRemove())) {
            $data['referencesToRemove'] = $this->getReferencesToRemove();
        }

        return $data;
    }

    /**
     * Returns the ID of the file.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set shopify media id
     *
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
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
     * Returns the name of the file, including its extension.
     *
     * @return ?string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Sets the name of the file including its extension.
     *
     * @param ?string $filename
     * @return void
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Returns the original source of the file update.
     *
     * @return ?string
     */
    public function getOriginalSource(): ?string
    {
        return $this->originalSource;
    }

    /**
     * Sets the original source for updating the file.
     *
     * @param ?string $originalSource
     * @return void
     */
    public function setOriginalSource(?string $originalSource): void
    {
        $this->originalSource = $originalSource;
    }

    /**
     * Returns the source for updating the preview image.
     *
     * @return ?string
     */
    public function getPreviewImageSource(): ?string
    {
        return $this->previewImageSource;
    }

    /**
     * Sets the source for updating the preview image.
     *
     * @param ?string $previewImageSource
     * @return void
     */
    public function setPreviewImageSource(?string $previewImageSource): void
    {
        $this->previewImageSource = $previewImageSource;
    }

    /**
     * Returns the IDs of the references to add to the file.
     * Currently only accepts product IDs.
     *
     * @return array
     */
    public function getReferencesToAdd(): array
    {
        return $this->referencesToAdd;
    }

    /**
     * Sets the IDs of the references to add to the file.
     * Currently only accepts product IDs.
     *
     * @param array $referencesToAdd
     * @return void
     */
    public function setReferencesToAdd(array $referencesToAdd): void
    {
        $this->referencesToAdd = $referencesToAdd;
    }

    /**
     * Returns the IDs of the references to remove from the file.
     * Currently only accepts product IDs.
     *
     * @return array
     */
    public function getReferencesToRemove(): array
    {
        return $this->referencesToRemove;
    }

    /**
     * Sets the IDs of the references to remove from the file.
     * Currently only accepts product IDs.
     *
     * @param array $referencesToRemove
     * @return void
     */
    public function setReferencesToRemove(array $referencesToRemove): void
    {
        $this->referencesToRemove = $referencesToRemove;
    }
}
