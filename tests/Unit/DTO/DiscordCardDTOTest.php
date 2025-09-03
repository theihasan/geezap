<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;

use App\DTO\DiscordCardDTO;

class DiscordCardDTOTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_discord_card_dto_with_required_parameters()
    {
        // Act
        $discord = new DiscordCardDTO(
            title: 'Discord Test Title',
            description: 'Discord test description'
        );

        // Assert
        $this->assertEquals('Discord Test Title', $discord->title);
        $this->assertEquals('Discord test description', $discord->description);
        $this->assertNull($discord->image);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_discord_card_dto_with_image()
    {
        // Act
        $discord = new DiscordCardDTO(
            title: 'Discord with Image',
            description: 'Discord description with image',
            image: 'https://example.com/discord.jpg'
        );

        // Assert
        $this->assertEquals('Discord with Image', $discord->title);
        $this->assertEquals('Discord description with image', $discord->description);
        $this->assertEquals('https://example.com/discord.jpg', $discord->image);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_array_correctly()
    {
        // Arrange
        $discord = new DiscordCardDTO(
            title: 'Array Test Title',
            description: 'Array test description',
            image: 'https://example.com/array.jpg'
        );

        // Act
        $array = $discord->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals('Array Test Title', $array['title']);
        $this->assertEquals('Array test description', $array['description']);
        $this->assertEquals('https://example.com/array.jpg', $array['image']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_correctly()
    {
        // Arrange
        $data = [
            'title' => 'From Array Title',
            'description' => 'From array description',
            'image' => 'https://example.com/from-array.jpg'
        ];

        // Act
        $discord = DiscordCardDTO::fromArray($data);

        // Assert
        $this->assertInstanceOf(DiscordCardDTO::class, $discord);
        $this->assertEquals('From Array Title', $discord->title);
        $this->assertEquals('From array description', $discord->description);
        $this->assertEquals('https://example.com/from-array.jpg', $discord->image);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_with_minimal_data()
    {
        // Arrange
        $data = [
            'title' => 'Minimal Discord Title',
            'description' => 'Minimal description'
        ];

        // Act
        $discord = DiscordCardDTO::fromArray($data);

        // Assert
        $this->assertEquals('Minimal Discord Title', $discord->title);
        $this->assertEquals('Minimal description', $discord->description);
        $this->assertNull($discord->image);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_null_image_in_array_conversion()
    {
        // Arrange
        $discord = new DiscordCardDTO(
            title: 'Null Image Test',
            description: 'Testing null image',
            image: null
        );

        // Act
        $array = $discord->toArray();

        // Assert
        $this->assertArrayHasKey('image', $array);
        $this->assertNull($array['image']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_serialization_and_deserialization()
    {
        // Arrange
        $originalDiscord = new DiscordCardDTO(
            title: 'Serialization Test',
            description: 'Testing Discord card serialization',
            image: 'https://example.com/serialize.jpg'
        );

        // Act
        $array = $originalDiscord->toArray();
        $reconstructedDiscord = DiscordCardDTO::fromArray($array);

        // Assert
        $this->assertEquals($originalDiscord->title, $reconstructedDiscord->title);
        $this->assertEquals($originalDiscord->description, $reconstructedDiscord->description);
        $this->assertEquals($originalDiscord->image, $reconstructedDiscord->image);
    }
}