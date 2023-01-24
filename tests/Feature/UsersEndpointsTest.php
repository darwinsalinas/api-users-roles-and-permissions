<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class UsersEndpointsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_users_endpoint_return_a_list_of_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/api/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ]
            ]
        ]);
    }

    public function test_users_endpoint_return_a_single_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/api/users/' . $user->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ]
        ]);
    }

    public function test_users_post_endpoint_creates_a_user()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test User',
            'email' => 'test2@gmail.com',
            'password' => 'password',
            "date_of_birth" => "2023-01-01",
            "gender" => "male",
            "dni" => "2342342",
            "address" => "sdfsdf",
            "country" => "sdfsd:",
            "phone_number" => "dfsdf",
            "roles" => [
                "super admin"
            ],
            "permissions" => [
                "super admin"
            ]
        ];

        $response = $this->actingAs($user)
            ->post('/api/users', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure(
            [
                'id',
                'name',
                'email',
                'date_of_birth',
                'gender',
                'dni',
                'address',
                'country',
                'phone_number',
                'roles',
                'permissions',
            ]
        );
    }

    public function test_users_post_endpoint_returns_validation_errors_when_data_is_invalid()
    {
        $user = User::factory()->create();

        $invalidData = [
            'names' => 'Test User',
            'email' => ''
        ];

        $response = $this->actingAs($user)
            ->post('/api/users', $invalidData);

        $response->assertStatus(400)
            ->assertJsonFragment(['The name field is required.']);



        $invalidData = [
            'name' => 'Juan Perez',
            'email' => 'this is not an email'
        ];

        $response = $this->actingAs($user)
            ->post('/api/users', $invalidData);

        $response->assertStatus(400);
        $response->assertJsonFragment(['The email must be a valid email address.']);

        $invalidData = [
            'name' => 'Juan Perez',
            'email' => 'test2@email.com',
            'password' => 'invalid',
        ];

        $response = $this->actingAs($user)
            ->post('/api/users', $invalidData);



        $response->assertStatus(400);
        $response->assertJsonFragment(['The password must be at least 8 characters.']);

    }

    public function test_users_delete_endpoint_deletes_a_user()
    {
        $user = User::factory()->create();
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/api/users/' . $userToDelete->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_users_delete_endpoint_return_error_message_when_user_does_not_exists()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete('/api/users/' . 999);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonFragment(['message' => 'No query results for user with given identifier']);
    }

    public function test_users_put_endpoint_updates_a_user()
    {
        $user = User::factory()->create();
        $userToUpdate = User::factory()->create();

        $data = [
            'name' => $userToUpdate->name . ' Updated',
        ];

        $response = $this->actingAs($user)
            ->put('/api/users/' . $userToUpdate->id, $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $userToUpdate->name . ' Updated']);
    }

    public function test_users_put_endpoint_returns_validation_errors_when_data_is_invalid()
    {
        $user = User::factory()->create();

        $invalidData = [
            'name' => ''
        ];

        $response = $this->actingAs($user)
            ->put('/api/users/' . $user->id, $invalidData);

        $response->assertStatus(400)
            ->assertJsonFragment(['The name must be at least 3 characters.']);



        $invalidData = [
            'email' => 'this is not an email'
        ];

        $response = $this->actingAs($user)
            ->put('/api/users/' . $user->id, $invalidData);

        $response->assertStatus(400);
        $response->assertJsonFragment(['The email must be a valid email address.']);

        $invalidData = [
            'name' => 'Juan Perez',
            'email' => 'test2@email.com',
            'password' => 'invalid',
        ];

        $response = $this->actingAs($user)
            ->put('/api/users/' . $user->id, $invalidData);

        $response->assertStatus(400);
        $response->assertJsonFragment(['The password must be at least 8 characters.']);

    }
}
