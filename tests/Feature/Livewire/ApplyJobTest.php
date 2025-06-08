<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ApplyJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplyJobTest extends TestCase
{
    #[Test]
    public function renders_successfully()
    {
        Livewire::test(ApplyJob::class)
            ->assertStatus(200);
    }
}
