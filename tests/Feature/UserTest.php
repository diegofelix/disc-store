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
        // Set
        User::create([
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'password' => 'some pass',
            'phone' => '+55 11 96293 7145',
        ]);

        // Actions
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

    public function testShouldCancelUserRegistration(): void
    {
        // Set
        User::create([
            'name' => 'Diego Felix',
            'email' => 'diego@diegofelix.com.br',
            'fiscal_id' => '11.111.111-1',
            'birthdate' => '1987-03-22',
            'password' => 'some pass',
            'phone' => '+55 11 96293 7145',
        ]);

        // Actions
        $response = $this->deleteJson('api/users/1');

        // Assertions
        $response->assertStatus(204);
        // Makes sure that after deleting, the user keeps on database.
        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, ['id' => 1]);

        // Assert that we will not consider this user anymore.
        $this->assertSame(0, User::count());
    }

    public function testShouldNotCancelRegistrationForAInvalidUser(): void
    {
        // Actions
        $response = $this->deleteJson('api/users/1');

        // Assertions
        $response->assertStatus(404);
    }
}
