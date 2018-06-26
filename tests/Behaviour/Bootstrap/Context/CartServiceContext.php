<?php

use App\Service\CartServiceInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

class CartServiceContext implements Context, KernelAwareContext
{
    /** @var KernelInterface */
    private $kernel;
    private $selectedProductIdList;

    /** @BeforeScenario
     * @param BeforeScenarioScope $beforeScenarioScope
     */
    public function resetSelectedProduct(BeforeScenarioScope $beforeScenarioScope)
    {
        $this->selectedProductIdList = [];

        /** @var CartServiceInterface $cartService */
        $cartService = $this->getCartService();
        $cartService->resetCart();
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I select the "([^"]*)" product id$/
     * @param string $productId
     */
    public function iSelectTheProductId(string $productId)
    {
        $this->selectedProductIdList[] = $productId;
    }

    /**
     * @When /^I add the selected product to cart$/
     */
    public function iAddTheProductToCart()
    {
        $cartService = $this->getCartService();
        foreach ($this->selectedProductIdList as $selectedProductId) {
            $cartService->add($selectedProductId);
        }
    }

    /**
     * @Then /^the cart contain (\d+) "([^"]*)" product$/
     * @param int $expectedCount
     * @param string $productName
     * @throws Exception
     */
    public function theCartContainProduct(int $expectedCount, string $productName)
    {
        $cartService = $this->getCartService();
        $cartData = $cartService->getCart();
        $count = 0;
        foreach ($cartData['productList'] as $product) {
            if ($productName === $product['details']->getTitle()) {
                $count += $product['count'];
            }
        }
        if ($expectedCount !== $count) {
            throw new \Exception(sprintf('%d product found. Expected only %d', $count, $expectedCount));
        }
    }

    /**
     * @Given /^the total amount is ([-+]?[0-9]*\.?[0-9]+) Euro$/
     * @param float $expectedAmount
     * @throws Exception
     */
    public function theTotalAmountIsEuro(float $expectedAmount)
    {
        $cartService = $this->getCartService();
        $cartData = $cartService->getCart();
        if ($expectedAmount !== $cartData['price']) {
            throw new \Exception(sprintf('%s euro found. Expected %s euro', $cartData['price'], $expectedAmount));
        }
    }

    /**
     * @Given /^I have (\d+) article on cart$/
     * @param int $expectedCount
     * @throws Exception
     */
    public function iHaveArticleOnCart(int $expectedCount)
    {
        $cartService = $this->getCartService();
        $cartData = $cartService->getCart();
        if ($expectedCount !== $cartData['count']) {
            throw new \Exception(sprintf('%s product found. Expected %s', $cartData['count'], $expectedCount));
        }
    }

    private function getCartService(): CartServiceInterface
    {
        return $this->kernel->getContainer()->get(CartServiceInterface::class);
    }

    /**
     * @Given /^I select this product :$/
     * @param \Behat\Gherkin\Node\TableNode $table
     */
    public function iSelectThisProduct(\Behat\Gherkin\Node\TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $quantity = (int) $row['quantity'];
            for ($q = 0; $q < $quantity; $q++) {
                $this->selectedProductIdList[] = $row['product id'];
            }
        }
    }
}
