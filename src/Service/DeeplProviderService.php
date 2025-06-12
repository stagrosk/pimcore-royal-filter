<?php

declare(strict_types=1);

namespace App\Service;

use DivanteTranslationBundle\Exception\TranslationException;
use DivanteTranslationBundle\Provider\AbstractProvider;
use DivanteTranslationBundle\Provider\FormalityProviderInterface;
use Pimcore\Tool;

class DeeplProviderService extends AbstractProvider implements FormalityProviderInterface
{
    /**
     * @param string $authKey
     * @param string $glossaryKey
     * @param string $endpoint
     */
    public function __construct(
        private readonly string $authKey,
        private readonly string $glossaryKey,
        private readonly string $endpoint
    ) {
    }

    /**
     * @param string $data
     * @param string $targetLanguage
     * @throws \DivanteTranslationBundle\Exception\TranslationException
     * @return string
     */
    public function translate(string $data, string $targetLanguage): string
    {
        try {
            $response = $this->getHttpClient()->request('POST', $this->endpoint, [
                'form_params' => [
                    'auth_key' => $this->authKey,
                    'text' => $data,
                    'source_lang' => Tool::getDefaultLanguage(),
                    'target_lang' => substr($targetLanguage, 0, 2),
                    'formality' => 'default',
                    'glossary_id' => $this->glossaryKey
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $exception) {
            throw new TranslationException($exception->getMessage());
        }

        return $data['translations'][0]['text'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'deepl';
    }

    /**
     * @param string|null $formality
     * @return \DivanteTranslationBundle\Provider\FormalityProviderInterface
     */
    public function setFormality(?string $formality): FormalityProviderInterface
    {
        return $this;
    }
}
