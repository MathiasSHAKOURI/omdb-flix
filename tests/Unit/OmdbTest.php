<?php

namespace App\Tests\Unit;

use App\Entity\Movie;
use App\Service\OmdbApiService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbTest extends KernelTestCase
{
    private const TITLES = [
        "gladiator",
        "the mask",
        "matrix",
        "inception"
    ];

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        $omdbApiService = static::getContainer()->get(OmdbApiService::class);

        foreach (self::TITLES as $title){
            $movie = new Movie();
            $movie->setTitle($title);

            $result = $omdbApiService->fetch($movie);

            $this->assertIsArray($result);
            $this->assertNotEmpty($result);
            $this->assertArrayHasKey("Title", $result);
            $this->assertEqualsIgnoringCase($movie->getTitle(),$result["Title"]); 
        };
    }
}
