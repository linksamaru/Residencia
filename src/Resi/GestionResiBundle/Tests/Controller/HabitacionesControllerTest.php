<?php

namespace Resi\GestionResiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HabitacionesControllerTest extends WebTestCase
{
    public function testVerhabitaciones()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/verHabitaciones');
    }

    public function testAlquilarhabitacion()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/alquilarHabitacion');
    }

}
