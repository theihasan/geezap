<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Mockery;
use Tests\TestCase;
use App\Models\Country;
use App\Helpers\CountryFlag;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryTest extends TestCase
{
    use RefreshDatabase;
    
    protected Country $country;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test country
        $this->country = Country::create([
            'name' => 'Test Country',
            'code' => 'TC',
            'is_active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    #[Test]
    public function it_has_fillable_attributes(): void
    {
        $expectedFillable = [
            'name',
            'code',
            'is_active',
        ];
        
        $this->assertEquals($expectedFillable, $this->country->getFillable());
    }
    
    #[Test]
    public function it_casts_attributes_correctly(): void
    {
        $expectedCasts = [
            'is_active' => 'boolean',
        ];
        
        $actualCasts = $this->country->getCasts();
        
        foreach ($expectedCasts as $attribute => $type) {
            $this->assertArrayHasKey($attribute, $actualCasts);
            $this->assertEquals($type, $actualCasts[$attribute]);
        }
    }
    
    #[Test]
    public function it_returns_flag_emoji_for_country_code(): void
    {
        $this->assertEquals(
            CountryFlag::getFlag('TC'),
            $this->country->getFlag()
        );
        

        $invalidCountry = Country::create([
            'name' => 'Invalid Country',
            'code' => 'XX',
            'is_active' => true,
        ]);
        
        $this->assertEquals('🌍', $invalidCountry->getFlag());
    }
    
    #[Test]
    public function it_can_be_filtered_by_active_status(): void
    {
        Country::create([
            'name' => 'Inactive Country',
            'code' => 'IC',
            'is_active' => false,
        ]);
        
        Country::create([
            'name' => 'Another Active Country',
            'code' => 'AC',
            'is_active' => true,
        ]);
        

        $activeCountries = Country::where('is_active', true)->get();
        $this->assertCount(2, $activeCountries);
        
        $inactiveCountries = Country::where('is_active', false)->get();
        $this->assertCount(1, $inactiveCountries);
    }
    
    #[Test]
    public function it_can_be_found_by_code(): void
    {
        $foundCountry = Country::where('code', 'TC')->first();
        
        $this->assertNotNull($foundCountry);
        $this->assertEquals('Test Country', $foundCountry->name);
    }
}