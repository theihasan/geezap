<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\MyApplications;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyApplicationsTest extends TestCase
{
    #[Test]
    public function renders_successfully()
    {
        Livewire::test(MyApplications::class)
            ->assertStatus(200);
    }
}
