<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

interface FormalityProviderInterface
{
    public function setFormality(?string $formality): self;
}
