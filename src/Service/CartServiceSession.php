<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServiceSession implements CartServiceInterface
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string
     */
    private $cartSessionName;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->cartSessionName = 'cart';
        $this->productRepository = $productRepository;
    }

    public function add(string $productId): void
    {
        $cart = $this->getCartDataFromStorage();
        $cart[] = $productId;
        $this->session->set($this->cartSessionName, $cart);
    }

    private function getCartDataFromStorage(): array
    {
        $cart = $this->session->get($this->cartSessionName);
        if (empty($cart)) {
            $cart = [];
        }
        return $cart;
    }

    public function remove(string $productId): void
    {
        $cart = $this->getCartDataFromStorage();
        foreach ($cart as $index => $productIdOnCart) {
            if ($productId === $productIdOnCart) {
                unset($cart[$index]);
                break;
            }
        }
        $this->session->set($this->cartSessionName, $cart);
    }

    public function getCart(): array
    {
        $cart = $this->getCartDataFromStorage();

        $productList = [];
        foreach ($cart as $productIdOnCart) {
            if (!isset($productList[$productIdOnCart])) {
                $productList[$productIdOnCart] = [
                    'count' => 0,
                    'details' => $this->productRepository->find($productIdOnCart)
                ];
            }
            $productList[$productIdOnCart]['count']++;
        }

        $totalCount = 0;
        $totalPrice = 0;
        foreach ($productList as $product) {
            $totalCount += $product['count'];
            $totalPrice += $product['count'] * $product['details']->getPrice();
        }

        $return = [
            'productList' => $productList,
            'count' => $totalCount,
            'price' => $totalPrice
        ];

        return $return;
    }

    public function resetCart(): void
    {
        $this->session->set($this->cartSessionName, []);
    }
}
