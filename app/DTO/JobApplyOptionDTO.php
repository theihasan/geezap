<?php

declare(strict_types=1);

namespace App\DTO;

readonly class JobApplyOptionDTO
{
    public function __construct(
        public string $publisher,
        public string $applyLink,
        public bool $isDirect,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            publisher: $data['publisher'],
            applyLink: $data['apply_link'],
            isDirect: $data['is_direct'] ?? false,
        );
    }

    public function toArray(): array
    {
        return [
            'publisher' => $this->publisher,
            'apply_link' => $this->applyLink,
            'is_direct' => $this->isDirect,
        ];
    }
}