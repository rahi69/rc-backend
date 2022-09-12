<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_threads_list_should_be_accessible()
    {
        $this->json('GET', route('threads.index'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function thread_should_be_accessible_by_slug()
    {
        $thread = Thread::factory()->create();

        $this->json('GET', route('threads.show', [$thread->slug]) ,['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function create_thread_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->json('POST',route('threads.store'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_thread_create()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = ['title'=> 'Iman','content'=> 'Ghafoori' , 'channel_id'=> Channel::factory()->create()->id];
        $this->json('POST',route('threads.store'), $thread , ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function update_thread_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $this->json('PUT',route('threads.update' , [$thread]),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_thread_update()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create([
            'title'=> 'Iman',
            'content'=> 'Ghafoori' ,
            'channel_id'=> Channel::factory()->create()->id,
            'user_id'=> $user->id
        ]);

        $threadEdit = ['title'=> 'Rahi','content'=> 'Bar' , 'channel_id'=> Channel::factory()->create()->id];

        $this->json('PUT',route('threads.update',[$thread]),$threadEdit , ['Accept' => 'application/json'])
            ->assertSuccessful();

        $thread->refresh();
        $this->assertSame('Rahi',$thread->title);
    }

    /** @test */
    public function can_add_best_answer_id_for_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create([
            'user_id'=> $user->id
        ]);

        $threadBestAnswerId = ['best_answer_id'=> 1];

        $this->json('PUT',route('threads.update',[$thread]),$threadBestAnswerId , ['Accept' => 'application/json'])
            ->assertSuccessful();

        $thread->refresh();
        $this->assertSame(1,$thread->best_answer_id);
    }

    /** @test */
    public function can_thread_destroy()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create([
            'user_id'=> $user->id
        ]);

        $this->json('DELETE',route('threads.destroy',[$thread->id]), ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }
}
