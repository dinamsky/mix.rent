<?php

namespace AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminCardControllerControllerTest extends WebTestCase
{
    public function testAdmincards()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/adminCards');
    }

}
