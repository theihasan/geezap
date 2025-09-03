<?php

namespace App\DTO;

use App\DTO\StructuredMetaDataDTO;
readonly class MetaTagDTO
{
    public function __construct(
        public string                 $title,
        public string                 $description,
        public string                 $keywords,
        public OpenGraphDTO           $og,
        public TwitterCardDTO         $twitter,
        public DiscordCardDTO         $discord,
        public ?StructuredMetaDataDTO $structuredData = null,
        public ?string                $robots = null,
        public ?string                $canonical = null,
        public ?string                $author = null,
        public ?string                $viewport = 'width=device-width, initial-scale=1'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            keywords: $data['keywords'],
            og: OpenGraphDTO::fromArray($data['og']),
            twitter: TwitterCardDTO::fromArray($data['twitter']),
            discord: DiscordCardDTO::fromArray($data['discord']),
            structuredData: isset($data['structured_data'])
                ? StructuredMetaDataDTO::fromArray($data['structured_data'])
                : null,
            robots: $data['robots'] ?? null,
            canonical: $data['canonical'] ?? null,
            author: $data['author'] ?? null,
            viewport: $data['viewport'] ?? 'width=device-width, initial-scale=1'
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'og' => $this->og->toArray(),
            'twitter' => $this->twitter->toArray(),
            'discord' => $this->discord->toArray(),
            'structured_data' => $this->structuredData?->toArray(),
            'robots' => $this->robots,
            'canonical' => $this->canonical,
            'author' => $this->author,
            'viewport' => $this->viewport,
        ];
    }
}
