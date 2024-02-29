<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('button-search')->form();

        $form["search"] = "Matrix";
        $crawler = $client->submit($form);

        $this->assertEquals(1,$crawler->filter('html:contains("Aucuns")')->count());
    }
}
