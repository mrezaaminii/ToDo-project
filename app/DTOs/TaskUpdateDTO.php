<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class TaskUpdateDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $color = null,
        public ?string $due_at = null,
        public ?bool $is_done = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            description: $data['description'] ?? null,
            color: $data['color'] ?? null,
            due_at: $data['due_at'] ?? null,
            is_done: $data['is_done'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'due_at' => $this->due_at,
            'is_done' => $this->is_done,
        ], fn ($value) => !is_null($value));
    }
}
