<?php

namespace Tests\Unit\DTO;

use Tests\TestCase;

use App\DTO\StructuredMetaDataDTO;

class StructuredMetaDataDTOTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_structured_meta_data_dto_with_array()
    {
        // Arrange
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Test Page'
        ];

        // Act
        $structured = new StructuredMetaDataDTO($data);

        // Assert
        $this->assertEquals($data, $structured->data);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_structured_meta_data_dto_with_complex_data()
    {
        // Arrange
        $complexData = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => 'Senior PHP Developer',
            'description' => 'We are looking for a senior PHP developer',
            'datePosted' => '2024-01-15T08:00:00Z',
            'employmentType' => 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => 'Tech Company',
                'logo' => 'https://example.com/logo.png'
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '123 Tech Street',
                    'addressLocality' => 'San Francisco',
                    'addressRegion' => 'CA',
                    'postalCode' => '94105',
                    'addressCountry' => 'US'
                ]
            ],
            'baseSalary' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'USD',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 120000,
                    'maxValue' => 160000,
                    'unitText' => 'YEAR'
                ]
            ]
        ];

        // Act
        $structured = new StructuredMetaDataDTO($complexData);

        // Assert
        $this->assertEquals($complexData, $structured->data);
        $this->assertEquals('JobPosting', $structured->data['@type']);
        $this->assertEquals('Senior PHP Developer', $structured->data['title']);
        $this->assertIsArray($structured->data['hiringOrganization']);
        $this->assertIsArray($structured->data['jobLocation']);
        $this->assertIsArray($structured->data['baseSalary']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_converts_to_array_correctly()
    {
        // Arrange
        $originalData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => 'Test Article',
            'author' => [
                '@type' => 'Person',
                'name' => 'John Doe'
            ]
        ];

        $structured = new StructuredMetaDataDTO($originalData);

        // Act
        $array = $structured->toArray();

        // Assert
        $this->assertEquals($originalData, $array);
        $this->assertIsArray($array);
        $this->assertEquals('https://schema.org', $array['@context']);
        $this->assertEquals('Article', $array['@type']);
        $this->assertEquals('Test Article', $array['headline']);
        $this->assertIsArray($array['author']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_creates_from_array_correctly()
    {
        // Arrange
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => 'Tech Conference 2024',
            'startDate' => '2024-06-15T09:00:00Z',
            'endDate' => '2024-06-17T17:00:00Z',
            'location' => [
                '@type' => 'Place',
                'name' => 'Convention Center',
                'address' => 'Downtown'
            ]
        ];

        // Act
        $structured = StructuredMetaDataDTO::fromArray($data);

        // Assert
        $this->assertInstanceOf(StructuredMetaDataDTO::class, $structured);
        $this->assertEquals($data, $structured->data);
        $this->assertEquals('Event', $structured->data['@type']);
        $this->assertEquals('Tech Conference 2024', $structured->data['name']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_empty_data()
    {
        // Act
        $structured = new StructuredMetaDataDTO([]);

        // Assert
        $this->assertEquals([], $structured->data);
        $this->assertEquals([], $structured->toArray());
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_nested_arrays()
    {
        // Arrange
        $nestedData = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Tech Corp',
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'telephone' => '+1-555-123-4567',
                    'contactType' => 'sales'
                ],
                [
                    '@type' => 'ContactPoint',
                    'telephone' => '+1-555-123-4568',
                    'contactType' => 'support'
                ]
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '456 Business Ave',
                'addressLocality' => 'Business City',
                'postalCode' => '12345'
            ]
        ];

        // Act
        $structured = new StructuredMetaDataDTO($nestedData);

        // Assert
        $this->assertEquals($nestedData, $structured->data);
        $this->assertCount(2, $structured->data['contactPoint']);
        $this->assertEquals('sales', $structured->data['contactPoint'][0]['contactType']);
        $this->assertEquals('support', $structured->data['contactPoint'][1]['contactType']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_preserves_data_types()
    {
        // Arrange
        $typedData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => 'Test Product',
            'price' => 99.99, // float
            'quantity' => 10, // integer
            'available' => true, // boolean
            'features' => ['feature1', 'feature2'], // array
            'metadata' => null // null
        ];

        // Act
        $structured = new StructuredMetaDataDTO($typedData);
        $array = $structured->toArray();

        // Assert
        $this->assertIsFloat($array['price']);
        $this->assertIsInt($array['quantity']);
        $this->assertIsBool($array['available']);
        $this->assertIsArray($array['features']);
        $this->assertNull($array['metadata']);
        $this->assertEquals(99.99, $array['price']);
        $this->assertEquals(10, $array['quantity']);
        $this->assertTrue($array['available']);
    }

    #[PHPUnit\Framework\Attributes\Test]
    public function it_handles_serialization_and_deserialization()
    {
        // Arrange
        $originalData = [
            '@context' => 'https://schema.org',
            '@type' => 'Recipe',
            'name' => 'Chocolate Chip Cookies',
            'recipeIngredient' => [
                '2 cups flour',
                '1 cup sugar',
                '1/2 cup butter'
            ],
            'recipeInstructions' => [
                [
                    '@type' => 'HowToStep',
                    'text' => 'Mix ingredients'
                ],
                [
                    '@type' => 'HowToStep',
                    'text' => 'Bake for 20 minutes'
                ]
            ],
            'nutrition' => [
                '@type' => 'NutritionInformation',
                'calories' => '250 calories'
            ]
        ];

        $originalStructured = new StructuredMetaDataDTO($originalData);

        // Act
        $array = $originalStructured->toArray();
        $reconstructedStructured = StructuredMetaDataDTO::fromArray($array);

        // Assert
        $this->assertEquals($originalData, $reconstructedStructured->data);
        $this->assertEquals($originalStructured->data, $reconstructedStructured->data);
        $this->assertCount(3, $reconstructedStructured->data['recipeIngredient']);
        $this->assertCount(2, $reconstructedStructured->data['recipeInstructions']);
    }
}