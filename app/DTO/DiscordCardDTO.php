<?php

namespace App\DTO;

readonly class DiscordCardDTO
{
    public function __construct(
        public string  $title,
        public string  $description,
        public ?string $image = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            image: $data['image'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
        ];
    }
}
