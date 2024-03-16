<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoremessengerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Message;
use App\Events\MessageDeleted;
class MessengerController extends Controller
{
    public function store(StoremessengerRequest $request)
    {
        try {
            $messageText = strip_tags(trim($request->input('dialog__message')));
            $chatName = $request->input('activeChatlist');
            if (empty($messageText)) {
                throw new \Exception('Message text is empty.');
            }

            Message::insertData($messageText, 444, $chatName);

            return response()->json(['status' => 'success', 'data' => null], 200);
        } catch (\Exception $e) {
            Log::error('Creating message encountered an error: ' . $e->getMessage());

            $res = json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);

            return response($res, 400)->header('Content-Type', 'application/json');
        }
    }

    public function update(Request $request)
    {
        try {
            $id = strip_tags(trim($request->input('dataID')));
            $newMessage = strip_tags(trim($request->input('newMessage')));

            if (empty($id)) {
                throw new \Exception('Message ID is empty.');
            }

            Message::updateData($id, $newMessage);

            return response()->json(['status' => 'success', 'message' => '', 'data' => $newMessage], 200);
        } catch (\Exception $e) {
            Log::error('Updating message encountered an error: ' . $e->getMessage());

            $res = json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);

            return response($res, 400)->header('Content-Type', 'application/json');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $deleteType = $request->input('deleteType');

            switch ($deleteType) {
                case 'single-real':
                    return $this->handleSingleDelete($request, 'deleteData');
                case 'single-physical':
                    return $this->handleSingleDelete($request, 'deleteDataphysical');
                case 'integrated':
                    $chatlistName = $request->input('activeChatlist');
                    return $this->handleIntegratedDelete($chatlistName);
                default:
                    throw new \Exception('Invalid delete type.');
            }
        } catch (\Exception $e) {
            Log::error('Deleting message encountered an error: ' . $e->getMessage());

            $res = json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);

            return response($res, 400)->header('Content-Type', 'application/json');
        }
    }

    public function get(Request $request)
    {
        try {
        $uploaded = (int)$request->input('uploaded');
        $messageModel = new Message();
        $messages = $messageModel->selectAllData($uploaded, 10);

        return response()->json(['status' => 'success', 'message' => '', 'data' => $messages], 200);
    } catch (\Exception $e) {
        Log::error('Getting messages encountered an error: ' . $e->getMessage());

        $res = json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);

        return response($res, 400)->header('Content-Type', 'application/json');
    }
}


    public function handleSingleDelete(Request $request, $deleteMethod)
    {
        try {
            $id = strip_tags(trim($request->input('dataID')));
            event(new MessageDeleted($request));
            if (empty($id)) {
                throw new \Exception('Message ID is empty.');
            }
            $message = Message::findOrFail($id);
            event(new MessageDeleted($message));
            Message::$deleteMethod($id);

            return response()->json(['status' => 'success', 'message' => 'deleted...'], 200);
        } catch (\Exception $e) {
            Log::error('Handling single delete encountered an error: ' . $e->getMessage());

            $res = json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);

            return response($res, 400)->header('Content-Type', 'application/json');
        }
    }

    private function handleIntegratedDelete($chatlistName)
    {
        try {
            if (empty($chatlistName)) {
                throw new \Exception('Chatlist name is empty.');
            }

            Message::deleteChatHistory($chatlistName);

            return response()->json(['status' => 'success', 'message' => 'deleted...'], 200);
        } catch (\Exception $e) {
            Log::error('Handling integrated delete encountered an error: ' . $e->getMessage());

            $res = json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);

            return response($res, 400)->header('Content-Type', 'application/json');
        }
    }
}

