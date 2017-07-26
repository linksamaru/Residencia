<?php

namespace Resi\GestionResiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FacturasControllerTest extends WebTestCase
{
    public function testVerfactura()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/verFactura');
    }

    public function testPagarfactura()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/pagarFactura');
    }

    public function testGenerarfactura()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generarFactura');
    }

    public function testEnviarnotificacionpago()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/enviarNotificacionPago');
    }

    public function testEnviarmensajefactura()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/enviarMensajeFactura');
    }

}
