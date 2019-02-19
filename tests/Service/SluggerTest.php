<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSlugify()
    {
        $phrase = 'Hello the World';

        // With hyphen
        $slugger = new Slugger(true);
        $result = $slugger->slugify($phrase);

        $this->assertEquals($result, 'hello-the-world');

        // Without hyphen
        $slugger = new Slugger(false);
        $result = $slugger->slugify($phrase);

        $this->assertEquals($result, 'hellotheworld');
    }
}