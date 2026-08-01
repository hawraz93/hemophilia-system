<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_returns_welcome_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
