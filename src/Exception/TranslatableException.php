<?php

namespace App\Exception;

use GraphQL\Error\Error;
use GraphQL\Language\Source;

class TranslatableException extends Error
{
    private string $translationKey;

    private string $errorCode;

    /**
     * @param string $message
     * @param string $translationKey
     * @param string $errorCode
     * @param $nodes
     * @param \GraphQL\Language\Source|null $source
     * @param array $positions
     * @param $path
     * @param $previous
     * @param array $extensions
     */
    public function __construct(string $message, string $translationKey, string $errorCode, $nodes = null, ?Source $source = null, array $positions = [], $path = null, $previous = null, array $extensions = [])
    {
        $this->translationKey = $translationKey;
        $this->errorCode = $errorCode;

        parent::__construct($message, $nodes, $source, $positions, $path, $previous, $extensions);
    }

    /**
     * @return string
     */
    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
