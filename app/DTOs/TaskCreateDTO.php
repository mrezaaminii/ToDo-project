<?php

namespace App\DTOs;

class TaskCreateDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $color,
        public readonly string $due_at,
        public readonly string $user_id
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            title: $validated['title'],
            description: $validated['description'],
            color: $validated['color'],
            due_at: $validated['due_at'],
            user_id: auth()->id()
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'due_at' => $this->due_at,
            'user_id' => $this->user_id,
        ];
    }
}