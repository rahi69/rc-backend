<?php
namespace Tests\Unit\API\v1\Channel;

use App\Models\Channel;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ChannelTest extends TestCase
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
     * Test All Channels List Should Be Accessible
     */
    public function test_all_channels_list_should_be_accessible()
    {
        $this->json('GET',route('channel.all'),['Accept'=>'application'])
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test Create Channel
     */
    public function test_create_channel_should_be_validated()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $this->actingAs($user)->json('POST',route('channel.create'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function test_channel_can_be_created()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $channel = ['name'=> 'Laravel'];

        $this->actingAs($user)->json('POST',route('channel.create'),$channel,['Accept'=>'application/json'])
            ->assertStatus(Response::HTTP_CREATED);

    }

    /**
     * Test Update Channel
     */
    public function test_channel_update_should_be_validated()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $this->actingAs($user)->json('PUT',route('channel.update'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_update()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $channel = Channel::factory()->create([
            'name' => 'Laravel'
        ]);

        $channelData = ['id'=> $channel->id , 'name' => 'Vuejs'];

        $res = $this->actingAs($user)->json('PUT',route('channel.update'), $channelData , ['Accept' => 'application/json']);

        $updateChannel = Channel::find($channel->id);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals('Vuejs',$updateChannel->name);
    }

    /**
     * Test Delete Channel
     */
    public function test_channel_delete_should_be_validated()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $this->actingAs($user)->json('DELETE',route('channel.delete'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_delete_channel()
    {
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $channel = Channel::factory()->create();

        $idChannel = ['id'=> $channel->id];

        $this->actingAs($user)->json('DELETE',route('channel.delete'),$idChannel,['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }

}
