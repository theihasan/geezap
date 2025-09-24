<?php

use Illuminate\Support\Facades\Storage;

it('generates sitemap without path traversal vulnerability', function () {
    // Mock the public disk
    Storage::fake('public');

    // Run the command
    $this->artisan('sitemap:generate')
        ->assertExitCode(0)
        ->expectsOutput('Sitemap generated successfully!');

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
    // This test ensures the fix prevents path traversal
    Storage::fake('public');

    // Run the command
    $this->artisan('sitemap:generate')
        ->assertExitCode(0);

    // Verify sitemap is in the correct location (not in parent directory)
    Storage::disk('public')->assertExists('sitemap.xml');
    
    // Verify no sitemap was created in parent directory
    // (This would fail if the old vulnerable code was still present)
    expect(Storage::disk('public')->exists('../sitemap.xml'))->toBeFalse();
});