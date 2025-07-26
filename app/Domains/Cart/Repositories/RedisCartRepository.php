<?php
// Domains/Cart/Repositories/RedisCartRepository.php
namespace App\Domains\Cart\Repositories;

use App\Domains\Cart\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class RedisCartRepository implements CartRepositoryInterface
{
    protected function key(int $userId): string
    {
        return "cart:{$userId}";
    }

    public function add(int $userId, int $courseId): void
    {
        Redis::sadd($this->key($userId), $courseId);
    }

    public function remove(int $userId, int $courseId): void
    {
        Redis::srem($this->key($userId), $courseId);
    }

    public function get(int $userId): array
    {
        return Redis::smembers($this->key($userId));
    }

    public function has(int $userId, int $courseId): bool
    {
        return Redis::sismember($this->key($userId), $courseId);
    }

    public function clear(int $userId): void
    {
        Redis::del($this->key($userId));
    }
}
