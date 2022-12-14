<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_get_all_answer_list()
    {
        $this->json('GET',route('answers.index'),['Accept' => 'application/json'])
            ->assertSuccessful();
    }

    /** @test */
    public function create_answer_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());
        $this->json('POST', route('answers.store'), ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['content', 'thread_id']);
    }

    /** @test */
    public function can_submit_new_answer_for_thread()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $answer = ['content'=>'Foo', 'thread_id'=> $thread->id];
        $this->json('POST', route('answers.store'), $answer ,['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(['message'=> 'answer submitted successfully']);
        $this->assertTrue($thread->answers()->where('content','Foo')->exists());
    }

    /** @test */
    function user_score_will_increase_by_submit_new_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create();
        $answer = ['content'=>'Foo', 'thread_id'=> $thread->id];
        $this->json('POST', route('answers.store'), $answer ,['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED);
        $user->refresh();
        $this->assertEquals(10 , $user->score);
    }

    /** @test */
    public function update_answer_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());
        $answer = Answer::factory()->create();
        $this->json('PUT', route('answers.update',[$answer]), ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function can_update_own_answer_of_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create([
            'content'=>'Foo',
            'user_id'=> $user->id
        ]);

        $this->json('PUT', route('answers.update', [$answer]), ['content' => 'Bar'],['Accept' => 'application/json'])

        ->assertStatus(Response::HTTP_OK)

        ->assertJson(['message'=> 'answer updated successfully']);

        $answer->refresh();

        $this->assertEquals('Bar',$answer->content);
    }
    /** @test */
    function can_delete_own_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create([
            'user_id'=>$user->id
            ]);

        $this->json('DELETE',route('answers.destroy', [$answer]),['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message'=> 'answer deleted successfully']);

        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());
    }
}
