<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('generates sitemap without path traversal vulnerability', function () {
    // Mock the public disk
    Storage::fake('public');

    // Run the command
    $this->artisan('sitemap:generate')
        ->assertExitCode(0);

    // Assert sitemap was created in the correct location (not with ../)
    Storage::disk('public')->assertExists('sitemap.xml');
    
    // Assert the sitemap contains expected content
    $sitemap = Storage::disk('public')->get('sitemap.xml');
    
    expect($sitemap)
        ->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->toContain('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"')
        ->toContain('</urlset>');
});

it('does not allow path traversal in sitemap filename', function () {
    // This test ensures the sitemap is created in the correct location only
    Storage::fake('public');

    // Run the command
    $this->artisan('sitemap:generate')
        ->assertExitCode(0);

    // Verify sitemap is in the correct location
    Storage::disk('public')->assertExists('sitemap.xml');
    
    // Verify the content is valid XML and contains expected structure
    $sitemap = Storage::disk('public')->get('sitemap.xml');
    expect($sitemap)
        ->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->toContain('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"')
        ->toContain('</urlset>');
        
    // Verify only one sitemap.xml file exists in the public storage
    $files = Storage::disk('public')->allFiles();
    $sitemapFiles = array_filter($files, fn($file) => str_ends_with($file, 'sitemap.xml'));
    expect($sitemapFiles)->toHaveCount(1);
    expect($sitemapFiles)->toContain('sitemap.xml');
});