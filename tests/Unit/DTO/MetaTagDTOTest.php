<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;

use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\TwitterCardDTO;
use App\DTO\DiscordCardDTO;
use App\DTO\StructuredMetaDataDTO;

class MetaTagDTOTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_meta_tag_dto_with_required_parameters()
    {
        // Arrange
        $og = new OpenGraphDTO('OG Title', 'OG Description', 'website');
        $twitter = new TwitterCardDTO('Twitter Title', 'Twitter Description');
        $discord = new DiscordCardDTO('Discord Title', 'Discord Description');

        // Act
        $meta = new MetaTagDTO(
            title: 'Test Title',
            description: 'Test Description',
            keywords: 'test, keywords',
            og: $og,
            twitter: $twitter,
            discord: $discord
        );

        // Assert
        $this->assertEquals('Test Title', $meta->title);
        $this->assertEquals('Test Description', $meta->description);
        $this->assertEquals('test, keywords', $meta->keywords);
        $this->assertInstanceOf(OpenGraphDTO::class, $meta->og);
        $this->assertInstanceOf(TwitterCardDTO::class, $meta->twitter);
        $this->assertInstanceOf(DiscordCardDTO::class, $meta->discord);
        $this->assertNull($meta->structuredData);
        $this->assertNull($meta->robots);
        $this->assertNull($meta->canonical);
        $this->assertNull($meta->author);
        $this->assertEquals('width=device-width, initial-scale=1', $meta->viewport);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_meta_tag_dto_with_all_parameters()
    {
        // Arrange
        $og = new OpenGraphDTO('OG Title', 'OG Description', 'website');
        $twitter = new TwitterCardDTO('Twitter Title', 'Twitter Description');
        $discord = new DiscordCardDTO('Discord Title', 'Discord Description');
        $structuredData = new StructuredMetaDataDTO(['@type' => 'WebPage']);

        // Act
        $meta = new MetaTagDTO(
            title: 'Complete Title',
            description: 'Complete Description',
            keywords: 'complete, keywords',
            og: $og,
            twitter: $twitter,
            discord: $discord,
            structuredData: $structuredData,
            robots: 'index,follow',
            canonical: 'https://example.com/page',
            author: 'Test Author',
            viewport: 'width=device-width, initial-scale=1.0'
        );

        // Assert
        $this->assertEquals('Complete Title', $meta->title);
        $this->assertEquals('Complete Description', $meta->description);
        $this->assertEquals('complete, keywords', $meta->keywords);
        $this->assertInstanceOf(StructuredMetaDataDTO::class, $meta->structuredData);
        $this->assertEquals('index,follow', $meta->robots);
        $this->assertEquals('https://example.com/page', $meta->canonical);
        $this->assertEquals('Test Author', $meta->author);
        $this->assertEquals('width=device-width, initial-scale=1.0', $meta->viewport);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_array_correctly()
    {
        // Arrange
        $og = new OpenGraphDTO('OG Title', 'OG Description', 'website');
        $twitter = new TwitterCardDTO('Twitter Title', 'Twitter Description');
        $discord = new DiscordCardDTO('Discord Title', 'Discord Description');
        $structuredData = new StructuredMetaDataDTO(['@type' => 'WebPage']);

        $meta = new MetaTagDTO(
            title: 'Array Title',
            description: 'Array Description',
            keywords: 'array, test',
            og: $og,
            twitter: $twitter,
            discord: $discord,
            structuredData: $structuredData,
            robots: 'noindex,nofollow',
            canonical: 'https://example.com/array-test',
            author: 'Array Author'
        );

        // Act
        $array = $meta->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals('Array Title', $array['title']);
        $this->assertEquals('Array Description', $array['description']);
        $this->assertEquals('array, test', $array['keywords']);
        $this->assertIsArray($array['og']);
        $this->assertIsArray($array['twitter']);
        $this->assertIsArray($array['discord']);
        $this->assertIsArray($array['structured_data']);
        $this->assertEquals('noindex,nofollow', $array['robots']);
        $this->assertEquals('https://example.com/array-test', $array['canonical']);
        $this->assertEquals('Array Author', $array['author']);
        $this->assertEquals('width=device-width, initial-scale=1', $array['viewport']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_correctly()
    {
        // Arrange
        $data = [
            'title' => 'From Array Title',
            'description' => 'From Array Description',
            'keywords' => 'from, array',
            'og' => [
                'title' => 'OG Title',
                'description' => 'OG Description',
                'type' => 'website',
                'image' => null,
                'url' => null,
                'site_name' => null,
                'locale' => null,
                'image_width' => null,
                'image_height' => null,
                'image_alt' => null
            ],
            'twitter' => [
                'title' => 'Twitter Title',
                'description' => 'Twitter Description',
                'image' => null,
                'card' => 'summary_large_image',
                'site' => null,
                'creator' => null,
                'image_alt' => null
            ],
            'discord' => [
                'title' => 'Discord Title',
                'description' => 'Discord Description',
                'image' => null
            ],
            'structured_data' => ['@type' => 'WebPage'],
            'robots' => 'index,follow',
            'canonical' => 'https://example.com/from-array',
            'author' => 'From Array Author',
            'viewport' => 'width=device-width, initial-scale=1'
        ];

        // Act
        $meta = MetaTagDTO::fromArray($data);

        // Assert
        $this->assertInstanceOf(MetaTagDTO::class, $meta);
        $this->assertEquals('From Array Title', $meta->title);
        $this->assertEquals('From Array Description', $meta->description);
        $this->assertEquals('from, array', $meta->keywords);
        $this->assertInstanceOf(OpenGraphDTO::class, $meta->og);
        $this->assertInstanceOf(TwitterCardDTO::class, $meta->twitter);
        $this->assertInstanceOf(DiscordCardDTO::class, $meta->discord);
        $this->assertInstanceOf(StructuredMetaDataDTO::class, $meta->structuredData);
        $this->assertEquals('index,follow', $meta->robots);
        $this->assertEquals('https://example.com/from-array', $meta->canonical);
        $this->assertEquals('From Array Author', $meta->author);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_without_optional_fields()
    {
        // Arrange
        $data = [
            'title' => 'Minimal Title',
            'description' => 'Minimal Description',
            'keywords' => 'minimal',
            'og' => [
                'title' => 'OG Title',
                'description' => 'OG Description',
                'type' => 'website',
                'image' => null,
                'url' => null,
                'site_name' => null,
                'locale' => null,
                'image_width' => null,
                'image_height' => null,
                'image_alt' => null
            ],
            'twitter' => [
                'title' => 'Twitter Title',
                'description' => 'Twitter Description',
                'image' => null,
                'card' => 'summary_large_image',
                'site' => null,
                'creator' => null,
                'image_alt' => null
            ],
            'discord' => [
                'title' => 'Discord Title',
                'description' => 'Discord Description',
                'image' => null
            ]
        ];

        // Act
        $meta = MetaTagDTO::fromArray($data);

        // Assert
        $this->assertEquals('Minimal Title', $meta->title);
        $this->assertNull($meta->structuredData);
        $this->assertNull($meta->robots);
        $this->assertNull($meta->canonical);
        $this->assertNull($meta->author);
        $this->assertEquals('width=device-width, initial-scale=1', $meta->viewport);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_serialization_and_deserialization()
    {
        // Arrange
        $og = new OpenGraphDTO('OG Title', 'OG Description', 'website');
        $twitter = new TwitterCardDTO('Twitter Title', 'Twitter Description');
        $discord = new DiscordCardDTO('Discord Title', 'Discord Description');

        $originalMeta = new MetaTagDTO(
            title: 'Serialization Test',
            description: 'Testing serialization',
            keywords: 'serialize, test',
            og: $og,
            twitter: $twitter,
            discord: $discord
        );

        // Act
        $array = $originalMeta->toArray();
        $reconstructedMeta = MetaTagDTO::fromArray($array);

        // Assert
        $this->assertEquals($originalMeta->title, $reconstructedMeta->title);
        $this->assertEquals($originalMeta->description, $reconstructedMeta->description);
        $this->assertEquals($originalMeta->keywords, $reconstructedMeta->keywords);
        $this->assertEquals($originalMeta->og->title, $reconstructedMeta->og->title);
        $this->assertEquals($originalMeta->twitter->title, $reconstructedMeta->twitter->title);
        $this->assertEquals($originalMeta->discord->title, $reconstructedMeta->discord->title);
    }
}