<?php

namespace App\Domains\Cart\Repositories;

use App\Domains\Cart\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class HybridCartRepository implements CartRepositoryInterface
{
    protected function isUser($id): bool
    {
        return is_int($id);
    }

    protected function redisKey(int $userId): string
    {
        return "cart:{$userId}";
    }

    public function add(int|string $id, int $courseId): void
    {
        if ($this->isUser($id)) {
            Redis::sadd($this->redisKey($id), $courseId);
        } else {
            $items = Session::get('cart', []);
            $items[$courseId] = true;
            Session::put('cart', $items);
        }
    }

    public function remove(int|string $id, int $courseId): void
    {
        if ($this->isUser($id)) {
            Redis::srem($this->redisKey($id), $courseId);
        } else {
            $items = Session::get('cart', []);
            unset($items[$courseId]);
            Session::put('cart', $items);
        }
    }

    public function get(int|string $id): array
    {
        if ($this->isUser($id)) {
            return Redis::smembers($this->redisKey($id));
        }

        return array_keys(Session::get('cart', []));
    }

    public function has(int|string $id, int $courseId): bool
    {
        if ($this->isUser($id)) {
            return Redis::sismember($this->redisKey($id), $courseId);
        }

        return array_key_exists($courseId, Session::get('cart', []));
    }

    public function clear(int|string $id): void
    {
        if ($this->isUser($id)) {
            Redis::del($this->redisKey($id));
        } else {
            Session::forget('cart');
        }
    }
}
