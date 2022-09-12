<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function user_can_subscribe_to_a_thread()
    {
        Sanctum::actingAs(User::factory()->create());

        $thread = Thread::factory()->create();

        $this->json('POST',route('subscribe', [$thread]),['Accept' => 'application/json'])
            ->assertSuccessful()
            ->assertJson(['message'=> 'user subscribed successfully']);
    }

    /** @test */

    public function user_can_unSubscribe_from_a_thread()
    {
        Sanctum::actingAs(User::factory()->create());

        $thread = Thread::factory()->create();

        $this->json('POST',route('unSubscribe', [$thread]),['Accept' => 'application/json'])
            ->assertSuccessful()
            ->assertJson(['message'=> 'user unSubscribed successfully']);
    }

    /** @test */
    public function notification_will_send_to_subscribers_of_a_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Notification::fake();

        $thread = Thread::factory()->create();

        $responseSubscribe =$this->json('POST',route('subscribe', [$thread]),['Accept' => 'application/json']);
        $responseSubscribe->assertSuccessful();
        $responseSubscribe ->assertJson(['message'=> 'user subscribed successfully']);

        $responseAnswer= $this->json('POST', route('answers.store', [
            'content'=> 'Foo',
            'thread_id'=> $thread->id
        ]));
        $responseAnswer->assertSuccessful();
        $responseAnswer->assertJson(['message'=> 'answer submitted successfully']);

        Notification::assertSentTo($user, NewReplySubmitted::class);
    }


}
