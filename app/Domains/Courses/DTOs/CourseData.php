<?php

namespace App\Domains\Courses\DTOs;

class CourseData
{
    public function __construct(
        public string $title,
        public string $slug,
        public ?string $description = null,
        public int $category_id,
        public ?int $instructor_id = null,
        public float $price = 0,
        public ?int $duration = null,
        public ?string $status = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'],
            description: $data['description'] ?? null,
            category_id: $data['category_id'],
            instructor_id: $data['instructor_id'] ?? null,
            price: $data['price'] ?? 0,
            duration: $data['duration'] ?? null,
            status: $data['status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title'         => $this->title,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'category_id'   => $this->category_id,
            'instructor_id' => $this->instructor_id,
            'price'         => $this->price,
            'duration'      => $this->duration,
            'status'        => $this->status,
        ], fn ($value) => !is_null($value));
    }
}
