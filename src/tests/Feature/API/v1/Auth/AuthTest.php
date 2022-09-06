<?php
namespace Tests\Feature\API\v1\Auth;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function registerRolesAndPermissions()
    {
        $roleInDatabase = Role::where('name', config('permission.default_roles')[0]);
        if($roleInDatabase->count()<1){
            foreach (config('permission.default_roles') as $role){
                Role::create([
                    'name'=>$role
                ]);
            }
        }

        $permissionInDatabase = Permission::where('name', config('permission.default_permissions')[0]);
        if($permissionInDatabase->count()<1){
            foreach (config('permission.default_permissions') as $permission){
                Permission::create([
                    'name'=>$permission
                ]);
            }
        }
    }

    /**
     * Test Register
     */

    public function test_register_should_be_validate()
    {
        $this->json('POST', route('auth.register'), ['Accept' => 'application/json'])

            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_new_user_can_register()
    {
        $this->registerRolesAndPermissions();
        $response = $this->postJson(route('auth.register'),[
            'name'=>"Rahi",
            'email'=>"Rahi@gmail.com",
            'password'=>"123456"
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test Login
     */
    public function test_login_should_be_validate()
    {
        $this->json('POST', route('auth.login'),['Accept'=>'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_login_with_true_credentials()
    {
        $user =  User::factory()->create();
        $loginData = ['email'=> $user->email , 'password' => 'password'];

        $this->json('POST',route('auth.login'), $loginData , ['Accept'=>'application/json'])

        ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test Logged In User
     */
    public function test_show_user_info_if_logged_in()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('GET', route('auth.user'),['Accept'=>'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test Logout
     */
    public function test_logged_in_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('auth.logout'),['Accept'=>'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }

}
