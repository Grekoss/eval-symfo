<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/cgu'];
        yield ['/login'];
        yield ['/search'];
        yield ['/search/tag/histoire'];
//        yield ['/question/situpouvaischangerunechosechez'];
    }

    /**
     * @dataProvider urlProviderUser
     */
    public function testPageIsSuccessfullUser($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'user',
        ));
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProviderUser()
    {
        yield ['/new/question'];
        yield ['/register'];
//        yield ['/reponse/23/vote'];
//        yield ['/search/author/5'];
//        yield ['/profil/3/edit'];
        yield ['/profil/3/question'];
        yield ['/profil/3/reponse'];
//        yield ['/question/2/edit'];
//        yield ['/question/15/like'];
    }

    /**
     * @dataProvider urlProviderModerator
     */
    public function testPageIsSuccessfullModerateur($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'moderateur',
            'PHP_AUTH_PW' => 'moderateur',
        ));
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProviderModerator()
    {
        yield ['/backend/question/list/'];
//        yield ['/backend/question/5'];
//        yield ['/backend/question/6/edit'];
//        yield ['/backend/question/7/active'];
//        yield ['/backend/question/2/reponses'];
        yield ['/backend/user/list/'];
//        yield ['/backend/user/15'];
//        yield ['/backend/user/20/edit'];
        yield ['/backend/tag/'];
        yield ['/backend/tag/new'];
//        yield ['/backend/tag/8'];
//        yield ['/backend/tag/1/edit'];
//        yield ['/backend/reponse/12/edit'];
//        yield ['/backend/reponse/10/active'];
    }
}