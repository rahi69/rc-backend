<?php
namespace Tests\Unit\API\v1\Channel;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;
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
        $this->json('POST',route('channel.create'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function test_channel_can_be_created()
    {
        $channel = ['name'=> 'Laravel'];

        $this->json('POST',route('channel.create'),$channel,['Accept'=>'application/json'])
            ->assertStatus(Response::HTTP_CREATED);

    }

    /**
     * Test Update Channel
     */
    public function test_channel_update_should_be_validated()
    {
        $this->json('PUT',route('channel.update'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_update()
    {
        $channel = Channel::factory()->create([
                'name' => 'Laravel'
        ]);

        $channelData = ['id'=> $channel->id , 'name' => 'Vuejs'];

        $res = $this->json('PUT',route('channel.update'), $channelData , ['Accept' => 'application/json']);

        $updateChannel = Channel::find($channel->id);

        $res->assertStatus(Response::HTTP_CREATED);

        $this->assertEquals('Vuejs',$updateChannel->name);
    }

    /**
     * Test Delete Channel
     */
    public function test_channel_delete_should_be_validated()
    {
        $this->json('DELETE',route('channel.delete'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_delete_channel()
    {
        $channel = Channel::factory()->create();

        $idChannel = ['id'=> $channel->id];

        $this->json('DELETE',route('channel.delete'),$idChannel,['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }
    
}
