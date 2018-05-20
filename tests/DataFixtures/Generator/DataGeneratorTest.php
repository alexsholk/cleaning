<?php

namespace App\Tests\DataFixtures\Generator;

use App\DataFixtures\Generator\DataGenerator;
use PHPUnit\Framework\TestCase;

class DataGeneratorTest extends TestCase
{
    public function testGenerateEmail()
    {
        $email = DataGenerator::generateEmail();
        $this->assertRegExp('/^.+\@\S+\.\S+$/', $email);
    }
}
