<?php

namespace Fx\SchoolBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TempControllerTest extends WebTestCase
{
    public function testSelectcarrera()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/selectCarrera');
    }

}
