<?php

namespace App\Http\Controllers\API\v1\Channel;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Repositories\ChannelRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{
    public function getAllChannelsList()
    {
        $allChannel = resolve(ChannelRepository::class)->all();

        return response()->json($allChannel ,Response::HTTP_OK);
    }

    /**
     * Create New Channel
     * @param Request $request
     * @return JsonResponse
     */

    public function createNewChannel(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

       resolve(ChannelRepository::class)->create($request->name);

        return response()->json([
            'message'=> 'channel created successfully'
        ],Response::HTTP_CREATED);
    }

    public function updateChannel(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

        resolve(ChannelRepository::class)->update($request->id,$request->name);

        return response()->json([
            'message'=> 'channel updated successfully'
        ],Response::HTTP_CREATED);

    }

    public function deleteChannel(Request $request)
    {
        $request->validate([
            'id' => ['required']
        ]);

        resolve(ChannelRepository::class)->delete($request->id);

        return response()->json([
            'message'=> 'channel deleted successfully'
        ],Response::HTTP_OK);
    }
}
