<?php
// Domains/Cart/Contracts/CartRepositoryInterface.php
namespace App\Domains\Cart\Contracts;

interface CartRepositoryInterface
{
    public function add(int|string $id, int $courseId): void;
    public function remove(int|string $id, int $courseId): void;
    public function get(int|string $id): array;
    public function has(int|string $id, int $courseId): bool;
    public function clear(int|string $id): void;
}
