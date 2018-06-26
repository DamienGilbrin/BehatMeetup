<?php

use Behat\Mink\Element\ElementInterface;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\MinkExtension\Context\MinkContext;

class CartContext extends MinkContext implements MinkAwareContext
{
    public function visit($page) {
        $host = $this->getMinkParameter('base_url');
        parent::visit($host . $page);
    }

    /**
     * @Given /^I am on home page$/
     */
    public function iAmOnHomePage()
    {
        $this->visit('/');
    }

    /**
     * @When /^I add (\d+ )?"([^"]*)" to my cart$/
     * @param string $productName
     * @throws Exception
     */
    public function iAddToMyCart(string $quantity, string $productName)
    {
        //Use default session, you can update to add "MyBrowserStackChrome" for specific test on navigator
        $session = $this->getMink()->getSession();

        //Find all article
        $articleElementList = $session->getPage()->findAll('css', '.article');
        foreach ($articleElementList as $articleElement) {
            //Is the expected article ?
            /** @var $articleElement ElementInterface */
            if ($articleElement->find('css', '.title')->getText() != $productName) {
                continue;
            }

            //Iterate for count click (add 1 time, 2...)
            $countIterate = trim($quantity) === '' ? 1 : (int) trim($quantity);
            for ($count = 0; $count < $countIterate; $count ++ ) {
                //Click on button
                $button = $articleElement->find('css', 'button');
                $button->click();

                //Wait button is not disabled (ajax request)
                $session->wait(
                    20000,
                    sprintf(
                        '!document.getElementById(\'%s\').disabled',
                        $button->getAttribute('id')
                    )
                );
            }
            //It's ok !
            return;
        }

        //Article not found
        throw new \Exception(sprintf('Article %s not found', $productName));
    }

    /**
     * @Given /^I see my cart$/
     */
    public function iSeeMyCart()
    {
        $this->visit('/cart');
    }

    /**
     * @Then /^the cart contain (\d+) "([^"]*)" product$/
     * @param int $expectedProductCount
     * @param string $expectedProductName
     * @throws Exception
     */
    public function theCartContainProduct(int $expectedProductCount, string $expectedProductName)
    {
        $session = $this->getMink()->getSession();

        $articleElementList = $session->getPage()->findAll('css', '.article');
        foreach ($articleElementList as $articleElement) {
            /** @var $articleElement ElementInterface */
            $articleQuantity = (int) $articleElement->find('css', '.quantity')->getText();
            $articleName = $articleElement->find('css', '.title')->getText();

            if ($expectedProductName !== $articleName) {
                continue;
            }

            if ($articleQuantity === $expectedProductCount) {
                //It's ok, just return, end of test
                return;
            }

            throw new \Exception(sprintf(
                'Quantity not match. Expected %d. Found %d',
                $expectedProductCount,
                $articleQuantity
            ));
        }

        //Not found
        throw new \Exception(sprintf('Product %s not found', $expectedProductName));
    }

    /**
     * @Given /^I have (\d+) article on cart$/
     * @param int $expectedCount
     * @throws Exception
     */
    public function iHaveArticleOnCart(int $expectedCount)
    {
        $session = $this->getMink()->getSession();
        $currentCount = (int) $session->getPage()->find('css', '.totalCount')->getText();
        if ($expectedCount !== $currentCount) {
            throw new \Exception(sprintf(
                'The number of article not match. Expected %d, found %d',
                $expectedCount,
                $currentCount
            ));
        }
    }

    /**
     * @Given /^the total amount is ([-+]?[0-9]*\.?[0-9]+) Euro$/
     * @param float $expectedAmount
     * @throws Exception
     */
    public function theTotalAmountIsEuro(float $expectedAmount)
    {
        $session = $this->getMink()->getSession();
        $currentAmount = (float) $session->getPage()->find('css', '.totalAmount')->getText();
        if ($expectedAmount !== $currentAmount) {
            throw new \Exception(sprintf(
                'The total amount not match. Expected %d Euro, found %d Euro',
                $expectedAmount,
                $currentAmount
            ));
        }
    }

    /**
     * @Given /^I have an empty cart$/
     */
    public function iHaveAnEmptyCart()
    {
        $this->visit('/cart');
        $session = $this->getSession();
        $session->getPage()->pressButton('emptyCart');
        $emptyTextElement = $session->getPage()->find('css', '.emptyText');
        if (!$emptyTextElement) {
            throw new \Exception('The cart is not empty');
        }
    }
}
