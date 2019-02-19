<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\CssSelector\CssSelectorConverter;

class ProfilControllerTest extends WebTestCase
{
    public function testCreateUser()
    {
        // HomePage
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('a:contains("Inscription")')
            ->link();
        ;

        // Go in the login page
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('img.mb-4')->count());

        // Submit the Form object with erreur
        $form = $crawler->selectButton('S\'inscrire')->form(array(
            'user[username]' => 'Test',
            'user[email]' => 'test@phpunit.com',
            'user[password]' => 'password',
            'user[passwordConfirm]' => 'password2'
        ));

        $client->submit($form);
        $crawler = $client->reload();

        //Show the erreur about != password
        $this->assertNotSame(1,$crawler->filter('div:contains("La confirmation de votre mot de passe a échoué")')->count());

        // Submit the Form object
        $form = $crawler->selectButton('S\'inscrire')->form(array(
            'user[username]' => 'Test',
            'user[email]' => 'test@phpunit.com',
            'user[password]' => 'password',
            'user[passwordConfirm]' => 'password'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        //Redirect to homepage
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1,$crawler->filter('h1:contains("FAQ - O\'clock")')->count());
    }

    public function testConnectedUserTestAndUpdateProfile()
    {
        // LoginPage
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Test for know if you are in the good page
        $this->assertSame(1, $crawler->filter('h2:contains("Veuillez vous connecter")')->count());

        $form = $crawler->selectButton('Se connecter')->form(array(
            '_username' => 'test',
            '_password' => 'password'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Redirect to homepage
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('div:contains("Welcome Test")')->count());

        //Go in the profile page
        $link = $crawler->filter('a:contains("Profil")')->link();
        $crawler = $client->click($link);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('h2:contains("Modification")')->count());
    }

    public function testDeleteUser()
    {
        // To be Admin
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin',
            '_password' => 'admin',
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Go backendPage User
        $crawler = $client->request('GET', '/backend/user/list/');

        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Liste des membres")')->count());

        // Check if user Test is here
        $this->assertGreaterThan(0,$crawler->filter('td:contains("test")')->count());

        // Search the first <tr> with the user : test
        $userTest = $crawler->filter('tr:first-child');
        $form = $userTest->selectButton('')->form();

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Recheck if user test is here for confirm the DeleteUser
        $this->assertSame(0, $crawler->filter('td:contains("test")')->count());
    }
}
