<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserPinTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */
    public function generate_4digit_pin()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs(User::factory()->create())->getJson('api/user-pins?count=4');
        $response->assertJson(fn(AssertableJson $json) => $json->has('pins')->etc());
        $this->assertCount(4, $response->json('pins'));
        $this->assertEquals(4, strlen($response->json('pins.0')));
        $this->assertEquals(4, strlen($response->json('pins.1')));
        $this->assertEquals(4, strlen($response->json('pins.2')));
        $this->assertEquals(4, strlen($response->json('pins.3')));
        $this->assertDatabaseCount('user_pins', 1);
    }

    /**
     * @test
     */
    public function generate_2_different_pins()
    {
        $user = User::factory()->create();
        $response1 = $this->actingAs($user)->getJson('api/user-pins?count=4');
        $response2 = $this->actingAs($user)->getJson('api/user-pins?count=4');
        $this->assertCount(0, array_intersect($response1->json('pins'), $response2->json('pins')));
        $this->assertDatabaseCount('user_pins', 1);
    }
}
