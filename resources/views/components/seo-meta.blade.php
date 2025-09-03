{{-- Basic Meta Tags --}}
<title>{{ $meta->title }}</title>
<meta name="description" content="{{ $meta->description }}">
<meta name="keywords" content="{{ $meta->keywords }}">

@if($meta->author)
<meta name="author" content="{{ $meta->author }}">
@endif

@if($meta->robots)
<meta name="robots" content="{{ $meta->robots }}">
@endif

@if($meta->canonical)
<link rel="canonical" href="{{ $meta->canonical }}">
@endif

@if($meta->viewport)
<meta name="viewport" content="{{ $meta->viewport }}">
@endif

{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="{{ $meta->og->title }}">
<meta property="og:description" content="{{ $meta->og->description }}">
<meta property="og:type" content="{{ $meta->og->type }}">

@if($meta->og->image)
<meta property="og:image" content="{{ $meta->og->image }}">
@if($meta->og->imageWidth)
<meta property="og:image:width" content="{{ $meta->og->imageWidth }}">
@endif
@if($meta->og->imageHeight)
<meta property="og:image:height" content="{{ $meta->og->imageHeight }}">
@endif
@if($meta->og->imageAlt)
<meta property="og:image:alt" content="{{ $meta->og->imageAlt }}">
@endif
@endif

@if($meta->og->url)
<meta property="og:url" content="{{ $meta->og->url }}">
@endif

@if($meta->og->siteName)
<meta property="og:site_name" content="{{ $meta->og->siteName }}">
@endif

@if($meta->og->locale)
<meta property="og:locale" content="{{ $meta->og->locale }}">
@endif

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="{{ $meta->twitter->card }}">
<meta name="twitter:title" content="{{ $meta->twitter->title }}">
<meta name="twitter:description" content="{{ $meta->twitter->description }}">

@if($meta->twitter->image)
<meta name="twitter:image" content="{{ $meta->twitter->image }}">
@if($meta->twitter->imageAlt)
<meta name="twitter:image:alt" content="{{ $meta->twitter->imageAlt }}">
@endif
@endif

@if($meta->twitter->site)
<meta name="twitter:site" content="{{ $meta->twitter->site }}">
@endif

@if($meta->twitter->creator)
<meta name="twitter:creator" content="{{ $meta->twitter->creator }}">
@endif

{{-- Structured Data --}}
@if($meta->structuredData)
<script type="application/ld+json">
{!! json_encode($meta->structuredData->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif