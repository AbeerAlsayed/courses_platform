<?php

namespace App\Domains\Courses\DTOs;

class LessonData
{
    public function __construct(
        public string $title,
        public string $description,
        public int $order,
        public ?int $duration = null,
        public bool $is_free = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['description'] ?? '',
            $data['order'] ?? 0,
            $data['duration'] ?? null,
            $data['is_free'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'duration' => $this->duration,
            'is_free' => $this->is_free,
        ];
    }
}
