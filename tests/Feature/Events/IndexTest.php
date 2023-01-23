<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    /*
     * Test events index page
     * */
    public function test_get_events()
    {
        $response = $this->get('/events');

        $response->assertStatus(200);
    }
}
