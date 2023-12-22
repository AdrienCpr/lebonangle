<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $client->getCrawler()->filter('form')->count(),
            'Html element'
        );
    }
}
