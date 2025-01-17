<?php

namespace App\DTO;

readonly class JobResponseDTO
{
    public function __construct(
        public array  $data,
        public int    $jobCategory,
        public string $categoryImage
    ) {}

    public static function fromResponse(array $response, int $categoryId, string $categoryImage): self
    {
        return new static($response['data'], $categoryId, $categoryImage);
    }
}
