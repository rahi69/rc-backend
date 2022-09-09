<?php

namespace App\Repositories;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadRepository
{
    public function getAllAvailableThreads()
    {
        return Thread::whereFlag(1)->latest()->get();
    }

    public function showThreadBySlug($slug)
    {
        return Thread::whereSlug($slug)->whereFlag(1)->first();
    }

    public function store(Request $request)
    {
        Thread::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'channel_id' => $request->input('channel_id'),
            'user_id' => auth()->user()->id
        ]);
    }

    public function update(Request $request , Thread $thread)
    {
        if(!$request->has('best_answer_id')) {
            $thread->update([
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title')),
                'content' => $request->input('content'),
                'channel_id' => $request->input('channel_id'),
            ]);
        }
        else {
            $thread->update([
                'best_answer_id' => $request->input('best_answer_id')
            ]);
        }

    }

    public function destroy(Thread $thread)
    {
        $thread->delete();
    }
}