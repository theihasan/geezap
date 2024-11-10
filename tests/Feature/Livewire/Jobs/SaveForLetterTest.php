<?php

namespace Tests\Feature\Livewire\Jobs;

use App\Livewire\Jobs\SaveForLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SaveForLetterTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(SaveForLetter::class)
            ->assertStatus(200);
    }
}
