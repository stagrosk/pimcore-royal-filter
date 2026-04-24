<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

interface ProviderInterface
{
    public function translate(string $data, string $targetLanguage): string;
    public function setApiKey(string $apiKey): self;
    public function getName(): string;
}
