<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content'=> $this->faker->realText(),
            'user_id'=> User::factory()->create()->id,
            'thread_id'=> Thread::factory()->create()->id,
        ];
    }
}
