<?php
/**
 * @author Piotr Rugała <piotr@isedo.pl>
 * @copyright Copyright (c) 2021 Divante Ltd. (https://divante.co)
 */

declare(strict_types=1);

namespace Tests\OpendxpTranslationBundle\Provider;

use OpendxpTranslationBundle\Exception\TranslationException;
use OpendxpTranslationBundle\Provider\GoogleProvider;
use OpendxpTranslationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class GoogleProviderTest extends TestCase
{
    public function testTranslate(): void
    {
        $response = [
            'data' => [
                'translations' => [
                    [
                        'translatedText' => 'test'
                    ],
                ],
            ],
        ];

        $this->assertSame('test', $this->createProvider($response)->translate('test', 'en'));
    }

    public function testTranslateError(): void
    {
        $this->expectException(TranslationException::class);

        $response = ['error' => 'error text'];

        $this->createProvider($response)->translate('test_error', 'en');
    }

    private function createProvider(array $response): ProviderInterface
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode($response)),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $provider = $this->getMockBuilder(GoogleProvider::class)
            ->onlyMethods(['getHttpClient'])
            ->getMock();
        $provider->method('getHttpClient')->willReturn($client);
        $provider->setApiKey('test');

        return $provider;
    }
}
