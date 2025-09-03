<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;

use App\DTO\TwitterCardDTO;

class TwitterCardDTOTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_twitter_card_dto_with_required_parameters()
    {
        // Act
        $twitter = new TwitterCardDTO(
            title: 'Twitter Test Title',
            description: 'Twitter test description'
        );

        // Assert
        $this->assertEquals('Twitter Test Title', $twitter->title);
        $this->assertEquals('Twitter test description', $twitter->description);
        $this->assertNull($twitter->image);
        $this->assertEquals('summary_large_image', $twitter->card);
        $this->assertNull($twitter->site);
        $this->assertNull($twitter->creator);
        $this->assertNull($twitter->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_twitter_card_dto_with_all_parameters()
    {
        // Act
        $twitter = new TwitterCardDTO(
            title: 'Complete Twitter Title',
            description: 'Complete Twitter description',
            image: 'https://example.com/twitter.jpg',
            card: 'summary',
            site: '@example',
            creator: '@author',
            imageAlt: 'Twitter image alt text'
        );

        // Assert
        $this->assertEquals('Complete Twitter Title', $twitter->title);
        $this->assertEquals('Complete Twitter description', $twitter->description);
        $this->assertEquals('https://example.com/twitter.jpg', $twitter->image);
        $this->assertEquals('summary', $twitter->card);
        $this->assertEquals('@example', $twitter->site);
        $this->assertEquals('@author', $twitter->creator);
        $this->assertEquals('Twitter image alt text', $twitter->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_uses_default_card_type()
    {
        // Act
        $twitter = new TwitterCardDTO(
            title: 'Default Card Test',
            description: 'Testing default card type'
        );

        // Assert
        $this->assertEquals('summary_large_image', $twitter->card);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_array_correctly()
    {
        // Arrange
        $twitter = new TwitterCardDTO(
            title: 'Array Test Title',
            description: 'Array test description',
            image: 'https://example.com/array.jpg',
            card: 'summary_large_image',
            site: '@arraytest',
            creator: '@arraycreator',
            imageAlt: 'Array test alt text'
        );

        // Act
        $array = $twitter->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals('Array Test Title', $array['title']);
        $this->assertEquals('Array test description', $array['description']);
        $this->assertEquals('https://example.com/array.jpg', $array['image']);
        $this->assertEquals('summary_large_image', $array['card']);
        $this->assertEquals('@arraytest', $array['site']);
        $this->assertEquals('@arraycreator', $array['creator']);
        $this->assertEquals('Array test alt text', $array['image_alt']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_correctly()
    {
        // Arrange
        $data = [
            'title' => 'From Array Title',
            'description' => 'From array description',
            'image' => 'https://example.com/from-array.jpg',
            'card' => 'summary',
            'site' => '@fromarray',
            'creator' => '@fromcreator',
            'image_alt' => 'From array alt text'
        ];

        // Act
        $twitter = TwitterCardDTO::fromArray($data);

        // Assert
        $this->assertInstanceOf(TwitterCardDTO::class, $twitter);
        $this->assertEquals('From Array Title', $twitter->title);
        $this->assertEquals('From array description', $twitter->description);
        $this->assertEquals('https://example.com/from-array.jpg', $twitter->image);
        $this->assertEquals('summary', $twitter->card);
        $this->assertEquals('@fromarray', $twitter->site);
        $this->assertEquals('@fromcreator', $twitter->creator);
        $this->assertEquals('From array alt text', $twitter->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_with_minimal_data()
    {
        // Arrange
        $data = [
            'title' => 'Minimal Twitter Title',
            'description' => 'Minimal description'
        ];

        // Act
        $twitter = TwitterCardDTO::fromArray($data);

        // Assert
        $this->assertEquals('Minimal Twitter Title', $twitter->title);
        $this->assertEquals('Minimal description', $twitter->description);
        $this->assertNull($twitter->image);
        $this->assertEquals('summary_large_image', $twitter->card);
        $this->assertNull($twitter->site);
        $this->assertNull($twitter->creator);
        $this->assertNull($twitter->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_uses_default_card_type_when_not_provided_in_array()
    {
        // Arrange
        $data = [
            'title' => 'Default Card Array Test',
            'description' => 'Testing default card from array'
        ];

        // Act
        $twitter = TwitterCardDTO::fromArray($data);

        // Assert
        $this->assertEquals('summary_large_image', $twitter->card);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_null_values_in_array_conversion()
    {
        // Arrange
        $twitter = new TwitterCardDTO(
            title: 'Null Test Title',
            description: 'Null test description',
            image: null,
            card: 'summary_large_image',
            site: null,
            creator: null,
            imageAlt: null
        );

        // Act
        $array = $twitter->toArray();

        // Assert
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayHasKey('site', $array);
        $this->assertArrayHasKey('creator', $array);
        $this->assertArrayHasKey('image_alt', $array);
        
        $this->assertNull($array['image']);
        $this->assertNull($array['site']);
        $this->assertNull($array['creator']);
        $this->assertNull($array['image_alt']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_different_card_types()
    {
        // Test different card types
        $cardTypes = ['summary', 'summary_large_image', 'app', 'player'];

        foreach ($cardTypes as $cardType) {
            $twitter = new TwitterCardDTO(
                title: "Test Title for {$cardType}",
                description: "Test description for {$cardType}",
                card: $cardType
            );

            $this->assertEquals($cardType, $twitter->card);
        }
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_serialization_and_deserialization()
    {
        // Arrange
        $originalTwitter = new TwitterCardDTO(
            title: 'Serialization Test',
            description: 'Testing Twitter card serialization',
            image: 'https://example.com/serialize.jpg',
            card: 'summary',
            site: '@serialize',
            creator: '@serializer',
            imageAlt: 'Serialization test image'
        );

        // Act
        $array = $originalTwitter->toArray();
        $reconstructedTwitter = TwitterCardDTO::fromArray($array);

        // Assert
        $this->assertEquals($originalTwitter->title, $reconstructedTwitter->title);
        $this->assertEquals($originalTwitter->description, $reconstructedTwitter->description);
        $this->assertEquals($originalTwitter->image, $reconstructedTwitter->image);
        $this->assertEquals($originalTwitter->card, $reconstructedTwitter->card);
        $this->assertEquals($originalTwitter->site, $reconstructedTwitter->site);
        $this->assertEquals($originalTwitter->creator, $reconstructedTwitter->creator);
        $this->assertEquals($originalTwitter->imageAlt, $reconstructedTwitter->imageAlt);
    }
}