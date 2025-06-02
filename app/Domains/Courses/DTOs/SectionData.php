<?php

namespace App\Domains\Courses\DTOs;

use InvalidArgumentException;

class SectionData
{
    public function __construct(
        public string $title,
        public int $order = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? throw new InvalidArgumentException('Title is required'),
            order: $data['order'] ?? 0,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'order' => $this->order,
        ];
    }
}
