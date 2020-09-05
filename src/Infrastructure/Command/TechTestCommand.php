<?php


namespace App\Infrastructure\Command;


use App\Application\Cart\CartContext;
use App\Domain\Currency\ExchangeProvider;
use App\Domain\Factory\VoucherFactory;
use App\Domain\Model\Currency;
use App\Domain\Model\Product;
use App\Domain\PriceFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TechTestCommand extends Command
{
    protected static $defaultName = 'app:test';
    private CartContext $cartContext;
    private VoucherFactory $voucherFactory;

    public function __construct(CartContext $cartContext, VoucherFactory $voucherFactory, string $name = null)
    {
        parent::__construct($name);
        $this->cartContext = $cartContext;
        $this->voucherFactory = $voucherFactory;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $eur = Currency::EUR();
        $context = $this->cartContext->clearContext($eur);

        // A product from a catalog or repository... no real logic
        $product = new Product('t-shirt', PriceFormatter::transform(10), $eur);

        // add 4 unit of the product to the cart
        $context->addProductToCart($product, 4);

        // Add a voucher o promotion to the context
        $voucher = $this->voucherFactory->create($product, PriceFormatter::transform(9), 3);
        $context->addVoucher($voucher);

        $output->writeln(sprintf('The total in the cart is %s %s', PriceFormatter::reverse($context->total()), $context->getCurrency()));

        $context->changeCurrency(Currency::EUR());
        $output->writeln(sprintf('With exchange is  %s %s', PriceFormatter::reverse($context->total()), $context->getCurrency()));

        return Command::SUCCESS;
    }


}