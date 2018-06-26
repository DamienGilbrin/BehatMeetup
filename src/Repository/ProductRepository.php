<?php

namespace App\Repository;

use App\Entity\Product;

class ProductRepository
{
    public function getAllProducts(): array
    {
        return [
            new Product('art_1', 'Article A', 10.0),
            new Product('art_2', 'Article B', 15.0),
            new Product('art_3', 'Article C', 3.0)
        ];
    }

    public function find(string $productId): ?Product
    {
        foreach ($this->getAllProducts() as $product) {
            /** @var Product $product */
            if ($product->getId() === $productId) {
                return $product;
            }
        }
        return null;
    }
}
