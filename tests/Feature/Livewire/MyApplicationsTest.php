<?php

namespace Tests\Feature\Livewire;

use App\Livewire\MyApplications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;


class MyApplicationsTest extends TestCase
{
    #[PHPUnit\Framework\Attributes\Test]
    public function renders_successfully()
    {
        Livewire::test(MyApplications::class)
            ->assertStatus(200);
    }
}
