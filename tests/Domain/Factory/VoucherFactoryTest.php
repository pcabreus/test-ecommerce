<?php


namespace App\Tests\Domain\Factory;


use App\Domain\Factory\VoucherFactory;
use App\Domain\Model\Currency;
use App\Domain\Model\Product;
use App\Domain\Model\Voucher;
use PHPUnit\Framework\TestCase;

class VoucherFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new VoucherFactory();

        $voucher = $factory->create(new Product('A', 10, Currency::EUR()), 9, 3, 1);

        $this->assertInstanceOf(Voucher::class, $voucher);
        $this->assertEquals('A', $voucher->getProduct()->getCode());
        $this->assertEquals(9, $voucher->getAmount());
        $this->assertEquals(3, $voucher->getMin());
        $this->assertEquals(1, $voucher->getUsageLimit());
    }
}