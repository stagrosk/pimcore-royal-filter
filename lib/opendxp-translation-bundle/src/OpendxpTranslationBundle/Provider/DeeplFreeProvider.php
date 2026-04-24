<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

class DeeplFreeProvider extends DeeplProvider
{
    protected string $url = 'https://api-free.deepl.com/';

    public function getName(): string
    {
        return 'deepl_free';
    }
}
