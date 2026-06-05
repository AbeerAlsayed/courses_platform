<?php

namespace App\Domains\Courses\DTOs;

final class CourseData
{
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly int $categoryId,
        public readonly float $price,
        public readonly ?string $description = null,
        public readonly ?int $duration = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'],
            categoryId: $data['category_id'],
            price: (float) ($data['price'] ?? 0),
            description: $data['description'] ?? null,
            duration: isset($data['duration'])
                ? (int) $data['duration']
                : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'description' => $this->description,
            'duration' => $this->duration,
        ], static fn ($value) => $value !== null);
    }
}
