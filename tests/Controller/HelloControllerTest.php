<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{
    public function testHello(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello');
        $this->assertResponseIsSuccessful();
        //$this->assertEquals('Test', $crawler->html());
        $this->assertSelectorTextContains('body', 'Hello World!');
    }

    /**
     * @dataProvider nameProvider
     */
    public function testHelloName(string $name): void
    {
        $client = static::createClient();
        $client->request('GET', "/hello/$name");

        $this->assertResponseIsSuccessful();
        $this->assertEquals("Hello $name", $client->getResponse()->getContent());
    }

    public function nameProvider(): array
    {
        return [
            'First name' => ["Ivan"],
            'Second name' => ["James"],
            'Third name' => ["Bob"],
        ];
    }
}
