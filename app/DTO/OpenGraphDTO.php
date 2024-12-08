<?php

namespace App\DTO;

class OpenGraphDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $type,
        public readonly ?string $image = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            type: $data['type'],
            image: $data['image'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'image' => $this->image,
        ];
    }
}
