<?php

namespace App\Http\Controllers\Messenger;
use App\Http\Controllers\Controller;
use App\Http\Requests\validator\messages\DeleteMessageRequest;
use App\Http\Requests\validator\messages\GetMessageRequest;
use App\Models\Messenger\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\validator\messages\UploadFileRequest;

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

    public function get(GetMessageRequest $request)
    {
        $page = $request->input('page');
        $chatName = $request->input('chatName');
        try {
            $model = Message::getMessage($page,$chatName);
            foreach ($model as $message) {
                if ($message['content_name']) {
                    $message['content_name'] = 'storage/uploaded/' . $message['content_name'];
                }
            }
            $response = json_encode([
                'status' => 'success',
                'data' => $model,
                 'currentUserID' => Auth::id(),
                'chatName' => $chatName
            ]);
            return response($response, 200);
        } catch (\Exception $error) {
            Log::error('getting messages got error: ' . $error->getMessage());
            $response = json_encode([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
            return response($response, 500);
        }
    }
    public function delete(DeleteMessageRequest $request)
    {
        $data = $request->validated();
                $dataID = $data['dataID'];
                if (!empty($dataID)) {
                    try {
                        $model =Message::softDeleteMessage($dataID);
                        $response = json_encode([
                            'status' => 'success',
                            'data' => $model,
                        ]);
                        return response($response, 200);
                    } catch (\Exception $error) {
                        Log::error('deleting message got error: ' . $error->getMessage());
                        $response = json_encode([
                            'status' => 'error',
                            'message' => $error->getMessage(),
                        ]);
                        return response($response, 500);
                    }
                }

    }
        public static function uploadFile(UploadFileRequest $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
        {
        $data = $request->validated();
        $chatName=$data['activeChatlist'];
        $fileToUpload = $data['fileToUpload'];
        $fileName = $fileToUpload->getClientOriginalName();
        $userID = Auth::user()->getAuthIdentifier();
        //$chatName ="php";
        try {
            $fileToUpload->storeAs('public/uploaded', $fileName);
            $model = Message::uploadFile($fileName, $userID, $chatName);
            $response = json_encode([
                'status' => 'success',
                'data' => $model
            ]);
            return response($response, 200);
        } catch (\Exception $error) {
            Log::error('getting messages got error: ' . $error->getMessage());
            $response = json_encode([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
            return response($response, 500);
        }
    }
    function getUser(Request $request)
    {
        $user_login = Auth::user();


        $users = User::all();

        $userList = [];
        foreach ($users as $user) {
            if ($user_login !== $user->id) {

                $userData = [
                    "nationalCode" => "",
                    "fName" => $user->name,
                    "lName" => "",
                    "phone" => "",
                    "fullNname" => $user->name,
                    "userName" => "@" . $user->name,
                    "profile" => "../../resources/messenger/image/user.png",
                    "chatType" => "1",
                    "sender" => $user->name,
                    "lastMessage" => "",
                    "date" => "",
                    "numberMessages" => "0",
                    "messageSendingStatus" => ""
                ];
                $userList[] = $userData;
            }
        }
        var_dump($userList);
        return json_encode($userList);
    }
    public static function SetContact(Request $request)
    {

        try {
            $model = \App\Models\Contact::insertContact($request);

            $response = json_encode([
                'status' => 'success',
                'data' => $model
            ]);
            return response($response, 200);
        } catch (\Exception $error) {
            Log::error('setting contact got error: ' . $error->getMessage());
            $response = json_encode([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
            return response($response, 500);
        }
    }
    public static function getContact()
    {
        try {
            $model = \App\Models\Contact::getContact();
            $response = json_encode([
                'status' => 'success',
                'data' => $model
            ]);
            return response($response, 200);
        } catch (\Exception $error) {
            Log::error('getting contact got error: ' . $error->getMessage());
            $response = json_encode([
                'status' => 'error',
                'message' => $error->getMessage(),
            ]);
            return response($response, 500);
        }
    }
}
