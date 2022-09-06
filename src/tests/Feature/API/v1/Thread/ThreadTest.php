<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     @test
     */
    public function all_threads_list_should_be_accessible()
    {
        $this->json('GET', route('threads.index'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);

    }

    /**@test*/
    public function thread_should_be_accessible_by_slug()
    {
        $thread = Thread::factory()->create();

        $this->json('GET', route('threads.show' , [$thread->slug]) ,['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function thread_should_be_validated()
    {
        $this->json('POST',route('threads.store'),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function can_thread_create()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = ['title'=> 'Foo','content'=> 'Bar' , 'channel_id'=> Channel::factory()->create()->id];
        $this->json('POST',route('threads.store'),$thread , ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED);
    }


}
