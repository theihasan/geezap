<?php

namespace App\DTO;

readonly class StructuredMetaDataDTO
{
    public function __construct(
        public array $data
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(data: $data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
