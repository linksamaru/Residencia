<?php

namespace Resi\GestionResiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GestionAdminControllerTest extends WebTestCase
{
    public function testGestionarhabitaciones()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gestionarHabitaciones');
    }

    public function testGestionarfacturas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gestionarFacturas');
    }

    public function testGestionarresidentes()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gestionarResidentes');
    }

    public function testDarbajaresidente()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/darBajaResidente');
    }

}
