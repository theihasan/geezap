<?php

namespace App\DTO;

class OpenGraphDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $type,
        public readonly ?string $image = null,
        public readonly ?string $url = null,
        public readonly ?string $siteName = null,
        public readonly ?string $locale = null,
        public readonly ?int $imageWidth = null,
        public readonly ?int $imageHeight = null,
        public readonly ?string $imageAlt = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            type: $data['type'],
            image: $data['image'] ?? null,
            url: $data['url'] ?? null,
            siteName: $data['site_name'] ?? null,
            locale: $data['locale'] ?? null,
            imageWidth: $data['image_width'] ?? null,
            imageHeight: $data['image_height'] ?? null,
            imageAlt: $data['image_alt'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'image' => $this->image,
            'url' => $this->url,
            'site_name' => $this->siteName,
            'locale' => $this->locale,
            'image_width' => $this->imageWidth,
            'image_height' => $this->imageHeight,
            'image_alt' => $this->imageAlt,
        ];
    }
}
