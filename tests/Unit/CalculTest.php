<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class CalculTest extends TestCase
{
    private const TEST_CASES =[
        [
            "numbers" => [10,20],
            "result" => 30
        ],
        [
            "numbers" => [44,44],
            "result" => 88
        ],
        [
            "numbers" => [1,2],
            "result" => 3
        ],
        [
            "numbers" => [777,700],
            "result" => 1477
        ],
    ];


    // public function testAddition(): void
    // {
    //     $this->assertTrue(14 < 25);
    // }

    public function testAddition()
    {
        foreach (self::TEST_CASES as $test)
        {
            $result = $this->addition($test["numbers"][0], $test["numbers"][1]);
            $this->assertEquals($test["result"],$result);
        }        
    }

    private function addition(int $num1, int $num2): int
    {
        return $num1 + $num2;
    }
}
