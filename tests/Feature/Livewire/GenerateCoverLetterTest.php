<?php

namespace Tests\Feature\Livewire;

use App\Livewire\GenerateCoverLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class GenerateCoverLetterTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(GenerateCoverLetter::class)
            ->assertStatus(200);
    }
}
