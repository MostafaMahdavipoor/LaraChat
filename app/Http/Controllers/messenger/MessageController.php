<?php

namespace App\Http\Controllers\messenger;

use App\Http\Controllers\Controller;
use App\Models\messenger\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{

    public function set(request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $chatName = $request['activeChatList'];
        $userID=Auth::user()->getAuthIdentifier();
        $messageText = strip_tags(trim($request['dialogMessage']));
        try {
            $data = Message::insertMessage($chatName, $messageText, $userID);
            $response = json_encode([
                'status' => 'success',
                'data' => $data,
            ]);
            return response($response, 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            $response = json_encode([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
            return response($response, 500);
        }
    }

    public function get(Request $request)
    {
        $uploaded = $request->input('uploaded');
        try {
            $data = Message::getMessage($uploaded);
            $response = json_encode([
                'status' => 'success',
                'data' => $data
            ]);
            return response($response, 200);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            $response = json_encode([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
            return response($response, 500);
        }
    }
}
