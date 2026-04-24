<?php

namespace OpendxpDeeplBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }
}
