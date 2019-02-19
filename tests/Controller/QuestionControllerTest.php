<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuestionControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        // Test Homepage
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1,$crawler->filter('h1:contains("FAQ - O\'clock")')->count());
    }

    public function testFirstQuestionOfTheHomepage()
    {
        // Go to question show
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('#container-questions-index a')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h2:contains("à la question")')->count());
    }
}
