<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CountryIndicator extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $jobCountry = null,
        public ?string $userCountry = null,
        public string $type = 'badge' // badge, icon, text
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string
    {
        return view('components.country-indicator');
    }

    /**
     * Determine if the job is from user's country
     */
    public function isFromUserCountry(): bool
    {
        return $this->jobCountry && $this->userCountry && 
               strtoupper($this->jobCountry) === strtoupper($this->userCountry);
    }

    /**
     * Get country flag emoji
     */
    public function getCountryFlag(): string
    {
        if (!$this->jobCountry) {
            return '🌍';
        }

        // Map some common countries to flags
        $flags = [
            'BD' => '🇧🇩',
            'US' => '🇺🇸',
            'UK' => '🇬🇧',
            'GB' => '🇬🇧',
            'CA' => '🇨🇦',
            'AU' => '🇦🇺',
            'IN' => '🇮🇳',
            'DE' => '🇩🇪',
            'FR' => '🇫🇷',
            'NL' => '🇳🇱',
            'SG' => '🇸🇬',
            'AE' => '🇦🇪',
        ];

        return $flags[strtoupper($this->jobCountry)] ?? '🌍';
    }

    /**
     * Get indicator text
     */
    public function getIndicatorText(): string
    {
        if ($this->isFromUserCountry()) {
            return 'Local Job';
        }

        return 'International';
    }

    /**
     * Get indicator CSS classes
     */
    public function getIndicatorClasses(): string
    {
        if ($this->isFromUserCountry()) {
            return 'bg-green-100 text-green-800 border-green-200';
        }

        return 'bg-blue-100 text-blue-800 border-blue-200';
    }
} 