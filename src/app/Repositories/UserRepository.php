<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function find($id)
    {
        return User::find($id);
    }
    /**
     * @param Request $request
     * @return User
     */
    public function create(Request $request)
    {
       return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    public function leaderboards()
    {
        return User::query()->orderByDesc('score')->paginate(20);
    }

    public function isBlock()
    {
        return (bool) auth()->user()->is_block;
    }
}