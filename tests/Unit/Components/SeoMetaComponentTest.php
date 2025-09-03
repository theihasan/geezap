<?php

namespace Tests\Unit\Components;

use Tests\TestCase;

use App\View\Components\SeoMeta;
use App\DTO\MetaTagDTO;
use App\DTO\OpenGraphDTO;
use App\DTO\TwitterCardDTO;
use App\DTO\DiscordCardDTO;
use App\DTO\StructuredMetaDataDTO;
use Illuminate\View\Component;

class SeoMetaComponentTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function it_is_a_component()
    {
        // Arrange
        $meta = $this->createSampleMeta();

        // Act
        $component = new SeoMeta($meta);

        // Assert
        $this->assertInstanceOf(Component::class, $component);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_accepts_meta_tag_dto()
    {
        // Arrange
        $meta = $this->createSampleMeta();

        // Act
        $component = new SeoMeta($meta);

        // Assert
        $this->assertEquals($meta, $component->meta);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_renders_correct_view()
    {
        // Arrange
        $meta = $this->createSampleMeta();
        $component = new SeoMeta($meta);

        // Act
        $view = $component->render();

        // Assert
        $this->assertEquals('components.seo-meta', $view->name());
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_contains_basic_meta_tags()
    {
        // Arrange
        $meta = new MetaTagDTO(
            title: 'Test Page Title',
            description: 'Test page description',
            keywords: 'test, keywords',
            og: new OpenGraphDTO('OG Title', 'OG Description', 'website'),
            twitter: new TwitterCardDTO('Twitter Title', 'Twitter Description'),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description')
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        $this->assertStringContains('<title>Test Page Title</title>', $html);
        $this->assertStringContains('<meta name="description" content="Test page description">', $html);
        $this->assertStringContains('<meta name="keywords" content="test, keywords">', $html);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_contains_open_graph_tags()
    {
        // Arrange
        $meta = new MetaTagDTO(
            title: 'OG Test Title',
            description: 'OG test description',
            keywords: 'og, test',
            og: new OpenGraphDTO(
                title: 'OG Specific Title',
                description: 'OG specific description',
                type: 'article',
                image: 'https://example.com/og-image.jpg',
                url: 'https://example.com/page',
                siteName: 'Test Site',
                locale: 'en_US',
                imageWidth: 1200,
                imageHeight: 630,
                imageAlt: 'OG Image Alt'
            ),
            twitter: new TwitterCardDTO('Twitter Title', 'Twitter Description'),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description')
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        $this->assertStringContains('<meta property="og:title" content="OG Specific Title">', $html);
        $this->assertStringContains('<meta property="og:description" content="OG specific description">', $html);
        $this->assertStringContains('<meta property="og:type" content="article">', $html);
        $this->assertStringContains('<meta property="og:image" content="https://example.com/og-image.jpg">', $html);
        $this->assertStringContains('<meta property="og:url" content="https://example.com/page">', $html);
        $this->assertStringContains('<meta property="og:site_name" content="Test Site">', $html);
        $this->assertStringContains('<meta property="og:locale" content="en_US">', $html);
        $this->assertStringContains('<meta property="og:image:width" content="1200">', $html);
        $this->assertStringContains('<meta property="og:image:height" content="630">', $html);
        $this->assertStringContains('<meta property="og:image:alt" content="OG Image Alt">', $html);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_contains_twitter_card_tags()
    {
        // Arrange
        $meta = new MetaTagDTO(
            title: 'Twitter Test',
            description: 'Twitter description',
            keywords: 'twitter, test',
            og: new OpenGraphDTO('OG Title', 'OG Description', 'website'),
            twitter: new TwitterCardDTO(
                title: 'Twitter Specific Title',
                description: 'Twitter specific description',
                image: 'https://example.com/twitter-image.jpg',
                card: 'summary_large_image',
                site: '@testsite',
                creator: '@testcreator',
                imageAlt: 'Twitter Image Alt'
            ),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description')
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        $this->assertStringContains('<meta name="twitter:card" content="summary_large_image">', $html);
        $this->assertStringContains('<meta name="twitter:title" content="Twitter Specific Title">', $html);
        $this->assertStringContains('<meta name="twitter:description" content="Twitter specific description">', $html);
        $this->assertStringContains('<meta name="twitter:image" content="https://example.com/twitter-image.jpg">', $html);
        $this->assertStringContains('<meta name="twitter:site" content="@testsite">', $html);
        $this->assertStringContains('<meta name="twitter:creator" content="@testcreator">', $html);
        $this->assertStringContains('<meta name="twitter:image:alt" content="Twitter Image Alt">', $html);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_contains_structured_data_when_provided()
    {
        // Arrange
        $structuredData = new StructuredMetaDataDTO([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Test Page',
            'description' => 'A test page for structured data'
        ]);

        $meta = new MetaTagDTO(
            title: 'Structured Data Test',
            description: 'Testing structured data',
            keywords: 'structured, data, test',
            og: new OpenGraphDTO('OG Title', 'OG Description', 'website'),
            twitter: new TwitterCardDTO('Twitter Title', 'Twitter Description'),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description'),
            structuredData: $structuredData
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        $this->assertStringContains('<script type="application/ld+json">', $html);
        $this->assertStringContains('"@context": "https://schema.org"', $html);
        $this->assertStringContains('"@type": "WebPage"', $html);
        $this->assertStringContains('"name": "Test Page"', $html);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_contains_optional_meta_tags_when_provided()
    {
        // Arrange
        $meta = new MetaTagDTO(
            title: 'Optional Meta Test',
            description: 'Testing optional meta tags',
            keywords: 'optional, meta, test',
            og: new OpenGraphDTO('OG Title', 'OG Description', 'website'),
            twitter: new TwitterCardDTO('Twitter Title', 'Twitter Description'),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description'),
            structuredData: null,
            robots: 'index,follow',
            canonical: 'https://example.com/canonical',
            author: 'Test Author',
            viewport: 'width=device-width, initial-scale=1.0'
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        $this->assertStringContains('<meta name="robots" content="index,follow">', $html);
        $this->assertStringContains('<link rel="canonical" href="https://example.com/canonical">', $html);
        $this->assertStringContains('<meta name="author" content="Test Author">', $html);
        $this->assertStringContains('<meta name="viewport" content="width=device-width, initial-scale=1.0">', $html);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_skips_null_optional_tags()
    {
        // Arrange
        $meta = new MetaTagDTO(
            title: 'Null Optional Test',
            description: 'Testing null optional fields',
            keywords: 'null, test',
            og: new OpenGraphDTO('OG Title', 'OG Description', 'website'),
            twitter: new TwitterCardDTO('Twitter Title', 'Twitter Description'),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description'),
            structuredData: null,
            robots: null,
            canonical: null,
            author: null
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        $this->assertStringNotContains('<meta name="robots"', $html);
        $this->assertStringNotContains('<link rel="canonical"', $html);
        $this->assertStringNotContains('<meta name="author"', $html);
        $this->assertStringNotContains('<script type="application/ld+json">', $html);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function rendered_view_handles_special_characters_properly()
    {
        // Arrange
        $meta = new MetaTagDTO(
            title: 'Special Characters: "Quotes" & Ampersands < > Tags',
            description: 'Description with special chars: "quotes" & ampersands',
            keywords: 'special, characters, test',
            og: new OpenGraphDTO(
                'OG Title with "quotes"',
                'OG Description with & ampersands',
                'website'
            ),
            twitter: new TwitterCardDTO(
                'Twitter Title with <tags>',
                'Twitter Description with special chars'
            ),
            discord: new DiscordCardDTO('Discord Title', 'Discord Description')
        );

        // Act
        $component = new SeoMeta($meta);
        $view = $component->render();
        $html = $view->with(['meta' => $meta])->render();

        // Assert
        // Should properly escape special characters
        $this->assertStringContains('&quot;Quotes&quot; &amp; Ampersands &lt; &gt; Tags', $html);
        $this->assertStringContains('&quot;quotes&quot; &amp; ampersands', $html);
    }

    private function createSampleMeta(): MetaTagDTO
    {
        return new MetaTagDTO(
            title: 'Sample Title',
            description: 'Sample description',
            keywords: 'sample, test',
            og: new OpenGraphDTO('Sample OG Title', 'Sample OG Description', 'website'),
            twitter: new TwitterCardDTO('Sample Twitter Title', 'Sample Twitter Description'),
            discord: new DiscordCardDTO('Sample Discord Title', 'Sample Discord Description')
        );
    }
}