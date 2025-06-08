<?php

namespace Tests\Feature\Livewire\Jobs;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Jobs\SaveForLetter;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaveForLetterTest extends TestCase
{
    #[Test]
    public function renders_successfully()
    {
        Livewire::test(SaveForLetter::class)
            ->assertStatus(200);
    }
}
