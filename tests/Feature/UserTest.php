<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldRegisterUser(): void
    {
        // Actions
        $response = $this->postJson('api/users', [
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'phone' => '+55 11 96293 7145',
        ]);

        // Assertions
        $response->assertJson([
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'phone' => '+55 11 96293 7145',
        ]);

        $response->assertStatus(200);
    }

    public function testShouldNotRegisterDuplicatedUsers(): void
    {
        // Actions
        User::create([
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'password' => 'some pass',
            'phone' => '+55 11 96293 7145',
        ]);

        $response = $this->postJson('api/users', [
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'phone' => '+55 11 96293 7145',
        ]);

        // Assertions
        $response->assertJsonValidationErrorFor('email');
        $response->assertJsonValidationErrorFor('fiscal_id');
    }
}
