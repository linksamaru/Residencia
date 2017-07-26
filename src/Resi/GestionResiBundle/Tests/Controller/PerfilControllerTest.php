<?php

namespace Resi\GestionResiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PerfilControllerTest extends WebTestCase
{
    public function testVerperfil()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/verPerfil');
    }

    public function testVerhistorialhabitaciones()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/verHistorialHabitaciones');
    }

    public function testVerfactura()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/verFactura');
    }

    public function testMostrarpagopendiente()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/mostrarPagoPendiente');
    }

    public function testPagarfactura()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/pagarFactura');
    }

}
