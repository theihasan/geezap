<?php

namespace Tests\Feature;

use App\Helpers\CountryFlag;
use PHPUnit\Framework\TestCase;

class CountryFlagNullHandlingTest extends TestCase
{
    public function test_handles_null_country_code_in_get_country_method(): void
    {
        $this->assertEquals('', CountryFlag::getCountry(null));
        $this->assertEquals('', CountryFlag::getCountry(''));
        $this->assertEquals('United States', CountryFlag::getCountry('US'));
    }

    public function test_handles_null_country_code_in_get_flag_method(): void
    {
        $this->assertEquals('🌍', CountryFlag::getFlag(null));
        $this->assertEquals('🌍', CountryFlag::getFlag(''));
        $this->assertEquals('🇺🇸', CountryFlag::getFlag('US'));
    }

    public function test_handles_null_country_code_in_has_flag_method(): void
    {
        $this->assertFalse(CountryFlag::hasFlag(null));
        $this->assertFalse(CountryFlag::hasFlag(''));
        $this->assertTrue(CountryFlag::hasFlag('US'));
        $this->assertFalse(CountryFlag::hasFlag('INVALID'));
    }

    public function test_handles_mixed_null_and_valid_codes_in_get_multiple_method(): void
    {
        $result = CountryFlag::getMultiple(['US', null, 'CA', '']);

        $expected = [
            'US' => '🇺🇸',
            null => '🌍',
            'CA' => '🇨🇦',
            '' => '🌍',
        ];

        $this->assertEquals($expected, $result);
    }
}
