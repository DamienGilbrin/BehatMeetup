<?php

namespace App\Service;

interface CartServiceInterface
{
    public function add(string $productId): void;
    public function remove(string $productId): void;
    public function getCart(): array;
    public function resetCart(): void;
}
