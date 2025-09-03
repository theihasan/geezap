<?php

namespace App\View\Components;

use App\DTO\MetaTagDTO;
use Illuminate\View\Component;
use Illuminate\View\View;

class SeoMeta extends Component
{
    public function __construct(
        public MetaTagDTO $meta
    ) {}

    public function render(): View
    {
        return view('components.seo-meta');
    }
}