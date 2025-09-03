<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;

use App\DTO\OpenGraphDTO;

class OpenGraphDTOTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_open_graph_dto_with_required_parameters()
    {
        // Act
        $og = new OpenGraphDTO(
            title: 'OG Test Title',
            description: 'OG test description',
            type: 'website'
        );

        // Assert
        $this->assertEquals('OG Test Title', $og->title);
        $this->assertEquals('OG test description', $og->description);
        $this->assertEquals('website', $og->type);
        $this->assertNull($og->image);
        $this->assertNull($og->url);
        $this->assertNull($og->siteName);
        $this->assertNull($og->locale);
        $this->assertNull($og->imageWidth);
        $this->assertNull($og->imageHeight);
        $this->assertNull($og->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_open_graph_dto_with_all_parameters()
    {
        // Act
        $og = new OpenGraphDTO(
            title: 'Complete OG Title',
            description: 'Complete OG description',
            type: 'article',
            image: 'https://example.com/image.jpg',
            url: 'https://example.com/page',
            siteName: 'Test Site',
            locale: 'en_US',
            imageWidth: 1200,
            imageHeight: 630,
            imageAlt: 'Test image alt text'
        );

        // Assert
        $this->assertEquals('Complete OG Title', $og->title);
        $this->assertEquals('Complete OG description', $og->description);
        $this->assertEquals('article', $og->type);
        $this->assertEquals('https://example.com/image.jpg', $og->image);
        $this->assertEquals('https://example.com/page', $og->url);
        $this->assertEquals('Test Site', $og->siteName);
        $this->assertEquals('en_US', $og->locale);
        $this->assertEquals(1200, $og->imageWidth);
        $this->assertEquals(630, $og->imageHeight);
        $this->assertEquals('Test image alt text', $og->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_array_correctly()
    {
        // Arrange
        $og = new OpenGraphDTO(
            title: 'Array Test Title',
            description: 'Array test description',
            type: 'website',
            image: 'https://example.com/test.jpg',
            url: 'https://example.com/test',
            siteName: 'Array Test Site',
            locale: 'en_GB',
            imageWidth: 800,
            imageHeight: 600,
            imageAlt: 'Array test alt'
        );

        // Act
        $array = $og->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals('Array Test Title', $array['title']);
        $this->assertEquals('Array test description', $array['description']);
        $this->assertEquals('website', $array['type']);
        $this->assertEquals('https://example.com/test.jpg', $array['image']);
        $this->assertEquals('https://example.com/test', $array['url']);
        $this->assertEquals('Array Test Site', $array['site_name']);
        $this->assertEquals('en_GB', $array['locale']);
        $this->assertEquals(800, $array['image_width']);
        $this->assertEquals(600, $array['image_height']);
        $this->assertEquals('Array test alt', $array['image_alt']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_correctly()
    {
        // Arrange
        $data = [
            'title' => 'From Array Title',
            'description' => 'From array description',
            'type' => 'article',
            'image' => 'https://example.com/from-array.jpg',
            'url' => 'https://example.com/from-array',
            'site_name' => 'From Array Site',
            'locale' => 'fr_FR',
            'image_width' => 1000,
            'image_height' => 500,
            'image_alt' => 'From array alt text'
        ];

        // Act
        $og = OpenGraphDTO::fromArray($data);

        // Assert
        $this->assertInstanceOf(OpenGraphDTO::class, $og);
        $this->assertEquals('From Array Title', $og->title);
        $this->assertEquals('From array description', $og->description);
        $this->assertEquals('article', $og->type);
        $this->assertEquals('https://example.com/from-array.jpg', $og->image);
        $this->assertEquals('https://example.com/from-array', $og->url);
        $this->assertEquals('From Array Site', $og->siteName);
        $this->assertEquals('fr_FR', $og->locale);
        $this->assertEquals(1000, $og->imageWidth);
        $this->assertEquals(500, $og->imageHeight);
        $this->assertEquals('From array alt text', $og->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_with_minimal_data()
    {
        // Arrange
        $data = [
            'title' => 'Minimal OG Title',
            'description' => 'Minimal description',
            'type' => 'website'
        ];

        // Act
        $og = OpenGraphDTO::fromArray($data);

        // Assert
        $this->assertEquals('Minimal OG Title', $og->title);
        $this->assertEquals('Minimal description', $og->description);
        $this->assertEquals('website', $og->type);
        $this->assertNull($og->image);
        $this->assertNull($og->url);
        $this->assertNull($og->siteName);
        $this->assertNull($og->locale);
        $this->assertNull($og->imageWidth);
        $this->assertNull($og->imageHeight);
        $this->assertNull($og->imageAlt);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_null_values_in_array_conversion()
    {
        // Arrange
        $og = new OpenGraphDTO(
            title: 'Null Test Title',
            description: 'Null test description',
            type: 'website',
            image: null,
            url: null,
            siteName: null,
            locale: null,
            imageWidth: null,
            imageHeight: null,
            imageAlt: null
        );

        // Act
        $array = $og->toArray();

        // Assert
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertArrayHasKey('site_name', $array);
        $this->assertArrayHasKey('locale', $array);
        $this->assertArrayHasKey('image_width', $array);
        $this->assertArrayHasKey('image_height', $array);
        $this->assertArrayHasKey('image_alt', $array);
        
        $this->assertNull($array['image']);
        $this->assertNull($array['url']);
        $this->assertNull($array['site_name']);
        $this->assertNull($array['locale']);
        $this->assertNull($array['image_width']);
        $this->assertNull($array['image_height']);
        $this->assertNull($array['image_alt']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_serialization_and_deserialization()
    {
        // Arrange
        $originalOg = new OpenGraphDTO(
            title: 'Serialization Test',
            description: 'Testing OG serialization',
            type: 'article',
            image: 'https://example.com/serialize.jpg',
            url: 'https://example.com/serialize',
            siteName: 'Serialize Site',
            locale: 'de_DE',
            imageWidth: 1920,
            imageHeight: 1080,
            imageAlt: 'Serialization test image'
        );

        // Act
        $array = $originalOg->toArray();
        $reconstructedOg = OpenGraphDTO::fromArray($array);

        // Assert
        $this->assertEquals($originalOg->title, $reconstructedOg->title);
        $this->assertEquals($originalOg->description, $reconstructedOg->description);
        $this->assertEquals($originalOg->type, $reconstructedOg->type);
        $this->assertEquals($originalOg->image, $reconstructedOg->image);
        $this->assertEquals($originalOg->url, $reconstructedOg->url);
        $this->assertEquals($originalOg->siteName, $reconstructedOg->siteName);
        $this->assertEquals($originalOg->locale, $reconstructedOg->locale);
        $this->assertEquals($originalOg->imageWidth, $reconstructedOg->imageWidth);
        $this->assertEquals($originalOg->imageHeight, $reconstructedOg->imageHeight);
        $this->assertEquals($originalOg->imageAlt, $reconstructedOg->imageAlt);
    }
}