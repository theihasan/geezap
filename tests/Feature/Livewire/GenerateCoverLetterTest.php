<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use PHPUnit\Framework\Test;
use App\Livewire\GenerateCoverLetter;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateCoverLetterTest extends TestCase
{
    #[Test]
    public function renders_successfully()
    {
        Livewire::test(GenerateCoverLetter::class)
            ->assertStatus(200);
    }
}
