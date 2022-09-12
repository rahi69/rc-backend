<?php

namespace App\Http\Controllers\API\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function userNotification()
    {
       return response()->json(auth()->user()->unreadNotifications() , Response::HTTP_OK);
    }

    public function leaderboards()
    {
        return resolve(UserRepository::class)->leaderboards();
    }
}
