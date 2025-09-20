<?php

namespace App\Helpers;

class CountryFlag
{
    /**
     * Cached country data to avoid recreation on every call
     */
    private static ?array $countryData = null;

    /**
     * Get flag emoji for a country code
     */
    public static function getFlag(string $countryCode): string
    {
        $countryData = self::getCountryData();
        return $countryData[strtoupper($countryCode)]['flag'] ?? '🌍';
    }

    /**
     * Get country name for a country code
     */
    public static function getCountry(string $countryCode): string
    {
        $countryData = self::getCountryData();
        return $countryData[strtoupper($countryCode)]['name'] ?? '';
    }

    /**
     * Check if a country code has a flag
     */
    public static function hasFlag(string $countryCode): bool
    {
        $countryData = self::getCountryData();
        return isset($countryData[strtoupper($countryCode)]);
    }

    /**
     * Get all country flags
     * @return array
     */
    public static function getAllFlags(): array
    {
        $countryData = self::getCountryData();
        $flags = [];
        foreach ($countryData as $code => $data) {
            $flags[$code] = $data['flag'];
        }
        return $flags;
    }

    /**
     * Get all countries
     * @return array
     */
    public static function getAllCountries(): array
    {
        $countryData = self::getCountryData();
        $countries = [];
        foreach ($countryData as $code => $data) {
            $countries[$code] = $data['name'];
        }
        return $countries;
    }

    /**
     * Get cached country data (flags + names combined)
     * This method uses lazy loading and caching for optimal performance
     */
    private static function getCountryData(): array
    {
        if (self::$countryData === null) {
            self::$countryData = [
            'AD' => ['flag' => '🇦🇩', 'name' => 'Andorra'],
            'AE' => ['flag' => '🇦🇪', 'name' => 'United Arab Emirates'],
            'AF' => ['flag' => '🇦🇫', 'name' => 'Afghanistan'],
            'AG' => ['flag' => '🇦🇬', 'name' => 'Antigua and Barbuda'],
            'AI' => ['flag' => '🇦🇮', 'name' => 'Anguilla'],
            'AL' => ['flag' => '🇦🇱', 'name' => 'Albania'],
            'AM' => ['flag' => '🇦🇲', 'name' => 'Armenia'],
            'AO' => ['flag' => '🇦🇴', 'name' => 'Angola'],
            'AQ' => ['flag' => '🇦🇶', 'name' => 'Antarctica'],
            'AR' => ['flag' => '🇦🇷', 'name' => 'Argentina'],
            'AS' => ['flag' => '🇦🇸', 'name' => 'American Samoa'],
            'AT' => ['flag' => '🇦🇹', 'name' => 'Austria'],
            'AU' => ['flag' => '🇦🇺', 'name' => 'Australia'],
            'AW' => ['flag' => '🇦🇼', 'name' => 'Aruba'],
            'AX' => ['flag' => '🇦🇽', 'name' => 'Åland Islands'],
            'AZ' => ['flag' => '🇦🇿', 'name' => 'Azerbaijan'],
            'BA' => ['flag' => '🇧🇦', 'name' => 'Bosnia and Herzegovina'],
            'BB' => ['flag' => '🇧🇧', 'name' => 'Barbados'],
            'BD' => ['flag' => '🇧🇩', 'name' => 'Bangladesh'],
            'BE' => ['flag' => '🇧🇪', 'name' => 'Belgium'],
            'BF' => ['flag' => '🇧🇫', 'name' => 'Burkina Faso'],
            'BG' => ['flag' => '🇧🇬', 'name' => 'Bulgaria'],
            'BH' => ['flag' => '🇧🇭', 'name' => 'Bahrain'],
            'BI' => ['flag' => '🇧🇮', 'name' => 'Burundi'],
            'BJ' => ['flag' => '🇧🇯', 'name' => 'Benin'],
            'BL' => ['flag' => '🇧🇱', 'name' => 'Saint Barthélemy'],
            'BM' => ['flag' => '🇧🇲', 'name' => 'Bermuda'],
            'BN' => ['flag' => '🇧🇳', 'name' => 'Brunei'],
            'BO' => ['flag' => '🇧🇴', 'name' => 'Bolivia'],
            'BQ' => ['flag' => '🇧🇶', 'name' => 'Caribbean Netherlands'],
            'BR' => ['flag' => '🇧🇷', 'name' => 'Brazil'],
            'BS' => ['flag' => '🇧🇸', 'name' => 'Bahamas'],
            'BT' => ['flag' => '🇧🇹', 'name' => 'Bhutan'],
            'BV' => ['flag' => '🇧🇻', 'name' => 'Bouvet Island'],
            'BW' => ['flag' => '🇧🇼', 'name' => 'Botswana'],
            'BY' => ['flag' => '🇧🇾', 'name' => 'Belarus'],
            'BZ' => ['flag' => '🇧🇿', 'name' => 'Belize'],
            'CA' => ['flag' => '🇨🇦', 'name' => 'Canada'],
            'CC' => ['flag' => '🇨🇨', 'name' => 'Cocos Islands'],
            'CD' => ['flag' => '🇨🇩', 'name' => 'Democratic Republic of the Congo'],
            'CF' => ['flag' => '🇨🇫', 'name' => 'Central African Republic'],
            'CG' => ['flag' => '🇨🇬', 'name' => 'Republic of the Congo'],
            'CH' => ['flag' => '🇨🇭', 'name' => 'Switzerland'],
            'CI' => ['flag' => '🇨🇮', 'name' => 'Côte d\'Ivoire'],
            'CK' => ['flag' => '🇨🇰', 'name' => 'Cook Islands'],
            'CL' => ['flag' => '🇨🇱', 'name' => 'Chile'],
            'CM' => ['flag' => '🇨🇲', 'name' => 'Cameroon'],
            'CN' => ['flag' => '🇨🇳', 'name' => 'China'],
            'CO' => ['flag' => '🇨🇴', 'name' => 'Colombia'],
            'CR' => ['flag' => '🇨🇷', 'name' => 'Costa Rica'],
            'CU' => ['flag' => '🇨🇺', 'name' => 'Cuba'],
            'CV' => ['flag' => '🇨🇻', 'name' => 'Cape Verde'],
            'CW' => ['flag' => '🇨🇼', 'name' => 'Curaçao'],
            'CX' => ['flag' => '🇨🇽', 'name' => 'Christmas Island'],
            'CY' => ['flag' => '🇨🇾', 'name' => 'Cyprus'],
            'CZ' => ['flag' => '🇨🇿', 'name' => 'Czech Republic'],
            'DE' => ['flag' => '🇩🇪', 'name' => 'Germany'],
            'DJ' => ['flag' => '🇩🇯', 'name' => 'Djibouti'],
            'DK' => ['flag' => '🇩🇰', 'name' => 'Denmark'],
            'DM' => ['flag' => '🇩🇲', 'name' => 'Dominica'],
            'DO' => ['flag' => '🇩🇴', 'name' => 'Dominican Republic'],
            'DZ' => ['flag' => '🇩🇿', 'name' => 'Algeria'],
            'EC' => ['flag' => '🇪🇨', 'name' => 'Ecuador'],
            'EE' => ['flag' => '🇪🇪', 'name' => 'Estonia'],
            'EG' => ['flag' => '🇪🇬', 'name' => 'Egypt'],
            'EH' => ['flag' => '🇪🇭', 'name' => 'Western Sahara'],
            'ER' => ['flag' => '🇪🇷', 'name' => 'Eritrea'],
            'ES' => ['flag' => '🇪🇸', 'name' => 'Spain'],
            'ET' => ['flag' => '🇪🇹', 'name' => 'Ethiopia'],
            'FI' => ['flag' => '🇫🇮', 'name' => 'Finland'],
            'FJ' => ['flag' => '🇫🇯', 'name' => 'Fiji'],
            'FK' => ['flag' => '🇫🇰', 'name' => 'Falkland Islands'],
            'FM' => ['flag' => '🇫🇲', 'name' => 'Micronesia'],
            'FO' => ['flag' => '🇫🇴', 'name' => 'Faroe Islands'],
            'FR' => ['flag' => '🇫🇷', 'name' => 'France'],
            'GA' => ['flag' => '🇬🇦', 'name' => 'Gabon'],
            'GB' => ['flag' => '🇬🇧', 'name' => 'United Kingdom'],
            'GD' => ['flag' => '🇬🇩', 'name' => 'Grenada'],
            'GE' => ['flag' => '🇬🇪', 'name' => 'Georgia'],
            'GF' => ['flag' => '🇬🇫', 'name' => 'French Guiana'],
            'GG' => ['flag' => '🇬🇬', 'name' => 'Guernsey'],
            'GH' => ['flag' => '🇬🇭', 'name' => 'Ghana'],
            'GI' => ['flag' => '🇬🇮', 'name' => 'Gibraltar'],
            'GL' => ['flag' => '🇬🇱', 'name' => 'Greenland'],
            'GM' => ['flag' => '🇬🇲', 'name' => 'Gambia'],
            'GN' => ['flag' => '🇬🇳', 'name' => 'Guinea'],
            'GP' => ['flag' => '🇬🇵', 'name' => 'Guadeloupe'],
            'GQ' => ['flag' => '🇬🇶', 'name' => 'Equatorial Guinea'],
            'GR' => ['flag' => '🇬🇷', 'name' => 'Greece'],
            'GS' => ['flag' => '🇬🇸', 'name' => 'South Georgia and the South Sandwich Islands'],
            'GT' => ['flag' => '🇬🇹', 'name' => 'Guatemala'],
            'GU' => ['flag' => '🇬🇺', 'name' => 'Guam'],
            'GW' => ['flag' => '🇬🇼', 'name' => 'Guinea-Bissau'],
            'GY' => ['flag' => '🇬🇾', 'name' => 'Guyana'],
            'HK' => ['flag' => '🇭🇰', 'name' => 'Hong Kong'],
            'HM' => ['flag' => '🇭🇲', 'name' => 'Heard Island and McDonald Islands'],
            'HN' => ['flag' => '🇭🇳', 'name' => 'Honduras'],
            'HR' => ['flag' => '🇭🇷', 'name' => 'Croatia'],
            'HT' => ['flag' => '🇭🇹', 'name' => 'Haiti'],
            'HU' => ['flag' => '🇭🇺', 'name' => 'Hungary'],
            'ID' => ['flag' => '🇮🇩', 'name' => 'Indonesia'],
            'IE' => ['flag' => '🇮🇪', 'name' => 'Ireland'],
            'IL' => ['flag' => '🇮🇱', 'name' => 'Israel'],
            'IM' => ['flag' => '🇮🇲', 'name' => 'Isle of Man'],
            'IN' => ['flag' => '🇮🇳', 'name' => 'India'],
            'IO' => ['flag' => '🇮🇴', 'name' => 'British Indian Ocean Territory'],
            'IQ' => ['flag' => '🇮🇶', 'name' => 'Iraq'],
            'IR' => ['flag' => '🇮🇷', 'name' => 'Iran'],
            'IS' => ['flag' => '🇮🇸', 'name' => 'Iceland'],
            'IT' => ['flag' => '🇮🇹', 'name' => 'Italy'],
            'JE' => ['flag' => '🇯🇪', 'name' => 'Jersey'],
            'JM' => ['flag' => '🇯🇲', 'name' => 'Jamaica'],
            'JO' => ['flag' => '🇯🇴', 'name' => 'Jordan'],
            'JP' => ['flag' => '🇯🇵', 'name' => 'Japan'],
            'KE' => ['flag' => '🇰🇪', 'name' => 'Kenya'],
            'KG' => ['flag' => '🇰🇬', 'name' => 'Kyrgyzstan'],
            'KH' => ['flag' => '🇰🇭', 'name' => 'Cambodia'],
            'KI' => ['flag' => '🇰🇮', 'name' => 'Kiribati'],
            'KM' => ['flag' => '🇰🇲', 'name' => 'Comoros'],
            'KN' => ['flag' => '🇰🇳', 'name' => 'Saint Kitts and Nevis'],
            'KP' => ['flag' => '🇰🇵', 'name' => 'North Korea'],
            'KR' => ['flag' => '🇰🇷', 'name' => 'South Korea'],
            'KW' => ['flag' => '🇰🇼', 'name' => 'Kuwait'],
            'KY' => ['flag' => '🇰🇾', 'name' => 'Cayman Islands'],
            'KZ' => ['flag' => '🇰🇿', 'name' => 'Kazakhstan'],
            'LA' => ['flag' => '🇱🇦', 'name' => 'Laos'],
            'LB' => ['flag' => '🇱🇧', 'name' => 'Lebanon'],
            'LC' => ['flag' => '🇱🇨', 'name' => 'Saint Lucia'],
            'LI' => ['flag' => '🇱🇮', 'name' => 'Liechtenstein'],
            'LK' => ['flag' => '🇱🇰', 'name' => 'Sri Lanka'],
            'LR' => ['flag' => '🇱🇷', 'name' => 'Liberia'],
            'LS' => ['flag' => '🇱🇸', 'name' => 'Lesotho'],
            'LT' => ['flag' => '🇱🇹', 'name' => 'Lithuania'],
            'LU' => ['flag' => '🇱🇺', 'name' => 'Luxembourg'],
            'LV' => ['flag' => '🇱🇻', 'name' => 'Latvia'],
            'LY' => ['flag' => '🇱🇾', 'name' => 'Libya'],
            'MA' => ['flag' => '🇲🇦', 'name' => 'Morocco'],
            'MC' => ['flag' => '🇲🇨', 'name' => 'Monaco'],
            'MD' => ['flag' => '🇲🇩', 'name' => 'Moldova'],
            'ME' => ['flag' => '🇲🇪', 'name' => 'Montenegro'],
            'MF' => ['flag' => '🇲🇫', 'name' => 'Saint Martin'],
            'MG' => ['flag' => '🇲🇬', 'name' => 'Madagascar'],
            'MH' => ['flag' => '🇲🇭', 'name' => 'Marshall Islands'],
            'MK' => ['flag' => '🇲🇰', 'name' => 'North Macedonia'],
            'ML' => ['flag' => '🇲🇱', 'name' => 'Mali'],
            'MM' => ['flag' => '🇲🇲', 'name' => 'Myanmar'],
            'MN' => ['flag' => '🇲🇳', 'name' => 'Mongolia'],
            'MO' => ['flag' => '🇲🇴', 'name' => 'Macao'],
            'MP' => ['flag' => '🇲🇵', 'name' => 'Northern Mariana Islands'],
            'MQ' => ['flag' => '🇲🇶', 'name' => 'Martinique'],
            'MR' => ['flag' => '🇲🇷', 'name' => 'Mauritania'],
            'MS' => ['flag' => '🇲🇸', 'name' => 'Montserrat'],
            'MT' => ['flag' => '🇲🇹', 'name' => 'Malta'],
            'MU' => ['flag' => '🇲🇺', 'name' => 'Mauritius'],
            'MV' => ['flag' => '🇲🇻', 'name' => 'Maldives'],
            'MW' => ['flag' => '🇲🇼', 'name' => 'Malawi'],
            'MX' => ['flag' => '🇲🇽', 'name' => 'Mexico'],
            'MY' => ['flag' => '🇲🇾', 'name' => 'Malaysia'],
            'MZ' => ['flag' => '🇲🇿', 'name' => 'Mozambique'],
            'NA' => ['flag' => '🇳🇦', 'name' => 'Namibia'],
            'NC' => ['flag' => '🇳🇨', 'name' => 'New Caledonia'],
            'NE' => ['flag' => '🇳🇪', 'name' => 'Niger'],
            'NF' => ['flag' => '🇳🇫', 'name' => 'Norfolk Island'],
            'NG' => ['flag' => '🇳🇬', 'name' => 'Nigeria'],
            'NI' => ['flag' => '🇳🇮', 'name' => 'Nicaragua'],
            'NL' => ['flag' => '🇳🇱', 'name' => 'Netherlands'],
            'NO' => ['flag' => '🇳🇴', 'name' => 'Norway'],
            'NP' => ['flag' => '🇳🇵', 'name' => 'Nepal'],
            'NR' => ['flag' => '🇳🇷', 'name' => 'Nauru'],
            'NU' => ['flag' => '🇳🇺', 'name' => 'Niue'],
            'NZ' => ['flag' => '🇳🇿', 'name' => 'New Zealand'],
            'OM' => ['flag' => '🇴🇲', 'name' => 'Oman'],
            'PA' => ['flag' => '🇵🇦', 'name' => 'Panama'],
            'PE' => ['flag' => '🇵🇪', 'name' => 'Peru'],
            'PF' => ['flag' => '🇵🇫', 'name' => 'French Polynesia'],
            'PG' => ['flag' => '🇵🇬', 'name' => 'Papua New Guinea'],
            'PH' => ['flag' => '🇵🇭', 'name' => 'Philippines'],
            'PK' => ['flag' => '🇵🇰', 'name' => 'Pakistan'],
            'PL' => ['flag' => '🇵🇱', 'name' => 'Poland'],
            'PM' => ['flag' => '🇵🇲', 'name' => 'Saint Pierre and Miquelon'],
            'PN' => ['flag' => '🇵🇳', 'name' => 'Pitcairn'],
            'PR' => ['flag' => '🇵🇷', 'name' => 'Puerto Rico'],
            'PS' => ['flag' => '🇵🇸', 'name' => 'Palestine'],
            'PT' => ['flag' => '🇵🇹', 'name' => 'Portugal'],
            'PW' => ['flag' => '🇵🇼', 'name' => 'Palau'],
            'PY' => ['flag' => '🇵🇾', 'name' => 'Paraguay'],
            'QA' => ['flag' => '🇶🇦', 'name' => 'Qatar'],
            'RE' => ['flag' => '🇷🇪', 'name' => 'Réunion'],
            'RO' => ['flag' => '🇷🇴', 'name' => 'Romania'],
            'RS' => ['flag' => '🇷🇸', 'name' => 'Serbia'],
            'RU' => ['flag' => '🇷🇺', 'name' => 'Russia'],
            'RW' => ['flag' => '🇷🇼', 'name' => 'Rwanda'],
            'SA' => ['flag' => '🇸🇦', 'name' => 'Saudi Arabia'],
            'SB' => ['flag' => '🇸🇧', 'name' => 'Solomon Islands'],
            'SC' => ['flag' => '🇸🇨', 'name' => 'Seychelles'],
            'SD' => ['flag' => '🇸🇩', 'name' => 'Sudan'],
            'SE' => ['flag' => '🇸🇪', 'name' => 'Sweden'],
            'SG' => ['flag' => '🇸🇬', 'name' => 'Singapore'],
            'SH' => ['flag' => '🇸🇭', 'name' => 'Saint Helena'],
            'SI' => ['flag' => '🇸🇮', 'name' => 'Slovenia'],
            'SJ' => ['flag' => '🇸🇯', 'name' => 'Svalbard and Jan Mayen'],
            'SK' => ['flag' => '🇸🇰', 'name' => 'Slovakia'],
            'SL' => ['flag' => '🇸🇱', 'name' => 'Sierra Leone'],
            'SM' => ['flag' => '🇸🇲', 'name' => 'San Marino'],
            'SN' => ['flag' => '🇸🇳', 'name' => 'Senegal'],
            'SO' => ['flag' => '🇸🇴', 'name' => 'Somalia'],
            'SR' => ['flag' => '🇸🇷', 'name' => 'Suriname'],
            'SS' => ['flag' => '🇸🇸', 'name' => 'South Sudan'],
            'ST' => ['flag' => '🇸🇹', 'name' => 'São Tomé and Príncipe'],
            'SV' => ['flag' => '🇸🇻', 'name' => 'El Salvador'],
            'SX' => ['flag' => '🇸🇽', 'name' => 'Sint Maarten'],
            'SY' => ['flag' => '🇸🇾', 'name' => 'Syria'],
            'SZ' => ['flag' => '🇸🇿', 'name' => 'Eswatini'],
            'TC' => ['flag' => '🇹🇨', 'name' => 'Turks and Caicos Islands'],
            'TD' => ['flag' => '🇹🇩', 'name' => 'Chad'],
            'TF' => ['flag' => '🇹🇫', 'name' => 'French Southern Territories'],
            'TG' => ['flag' => '🇹🇬', 'name' => 'Togo'],
            'TH' => ['flag' => '🇹🇭', 'name' => 'Thailand'],
            'TJ' => ['flag' => '🇹🇯', 'name' => 'Tajikistan'],
            'TK' => ['flag' => '🇹🇰', 'name' => 'Tokelau'],
            'TL' => ['flag' => '🇹🇱', 'name' => 'Timor-Leste'],
            'TM' => ['flag' => '🇹🇲', 'name' => 'Turkmenistan'],
            'TN' => ['flag' => '🇹🇳', 'name' => 'Tunisia'],
            'TO' => ['flag' => '🇹🇴', 'name' => 'Tonga'],
            'TR' => ['flag' => '🇹🇷', 'name' => 'Turkey'],
            'TT' => ['flag' => '🇹🇹', 'name' => 'Trinidad and Tobago'],
            'TV' => ['flag' => '🇹🇻', 'name' => 'Tuvalu'],
            'TW' => ['flag' => '🇹🇼', 'name' => 'Taiwan'],
            'TZ' => ['flag' => '🇹🇿', 'name' => 'Tanzania'],
            'UA' => ['flag' => '🇺🇦', 'name' => 'Ukraine'],
            'UG' => ['flag' => '🇺🇬', 'name' => 'Uganda'],
            'UM' => ['flag' => '🇺🇲', 'name' => 'United States Minor Outlying Islands'],
            'US' => ['flag' => '🇺🇸', 'name' => 'United States'],
            'UY' => ['flag' => '🇺🇾', 'name' => 'Uruguay'],
            'UZ' => ['flag' => '🇺🇿', 'name' => 'Uzbekistan'],
            'VA' => ['flag' => '🇻🇦', 'name' => 'Vatican City'],
            'VC' => ['flag' => '🇻🇨', 'name' => 'Saint Vincent and the Grenadines'],
            'VE' => ['flag' => '🇻🇪', 'name' => 'Venezuela'],
            'VG' => ['flag' => '🇻🇬', 'name' => 'British Virgin Islands'],
            'VI' => ['flag' => '🇻🇮', 'name' => 'U.S. Virgin Islands'],
            'VN' => ['flag' => '🇻🇳', 'name' => 'Vietnam'],
            'VU' => ['flag' => '🇻🇺', 'name' => 'Vanuatu'],
            'WF' => ['flag' => '🇼🇫', 'name' => 'Wallis and Futuna'],
            'WS' => ['flag' => '🇼🇸', 'name' => 'Samoa'],
            'YE' => ['flag' => '🇾🇪', 'name' => 'Yemen'],
            'YT' => ['flag' => '🇾🇹', 'name' => 'Mayotte'],
            'ZA' => ['flag' => '🇿🇦', 'name' => 'South Africa'],
            'ZM' => ['flag' => '🇿🇲', 'name' => 'Zambia'],
            'ZW' => ['flag' => '🇿🇼', 'name' => 'Zimbabwe'],
        ];
        }
        return self::$countryData;
    }

    /**
     * Get multiple flags at once
     * @param $countryCode
     * @return array
     */
    public static function getMultiple(array $countryCodes): array
    {
        $result = [];
        foreach ($countryCodes as $code) {
            $result[$code] = self::getFlag($code);
        }
        return $result;
    }
}