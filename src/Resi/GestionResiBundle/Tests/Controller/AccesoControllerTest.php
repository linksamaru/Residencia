<?php

namespace Resi\GestionResiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccesoControllerTest extends WebTestCase
{
    public function testAcceder()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Acceder');
    }

    public function testRegistro()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Registro');
    }

}
