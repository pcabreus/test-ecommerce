<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\Units;
use PHPUnit\Framework\TestCase;

class UnitsTest extends TestCase
{
    public function testPositiveValue()
    {
        $units = Units::create(10);

        // create units
        $this->assertInstanceOf(Units::class, $units);
        $this->assertEquals(10, $units->value());

        // Add more units
        $units->addUnits(Units::create(100));
        $this->assertEquals(110, $units->value());
    }

    public function testNegativeValue()
    {
        $this->expectException(\InvalidArgumentException::class);

        Units::create(-10);
    }
}
