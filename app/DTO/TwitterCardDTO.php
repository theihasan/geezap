<?php

namespace App\DTO;

readonly class TwitterCardDTO
{
    public function __construct(
        public string  $title,
        public string  $description,
        public ?string $image = null,
        public string  $card = 'summary_large_image',
        public ?string $site = null,
        public ?string $creator = null,
        public ?string $imageAlt = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            image: $data['image'] ?? null,
            card: $data['card'] ?? 'summary_large_image',
            site: $data['site'] ?? null,
            creator: $data['creator'] ?? null,
            imageAlt: $data['image_alt'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'card' => $this->card,
            'site' => $this->site,
            'creator' => $this->creator,
            'image_alt' => $this->imageAlt,
        ];
    }
}

