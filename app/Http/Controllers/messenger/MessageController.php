<?php

namespace App\Http\Controllers\Messenger;
use App\Http\Controllers\Controller;
use App\Models\Messenger\Message;
use App\Models\User;
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
        $page = $request->input('page');
        $chatName = $request->input('chatName');
        try {
            $data = Message::getMessage($page,$chatName);
            $response = json_encode([
                'status' => 'success',
                'currentUserID' => Auth::id(),
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
    public static function uploadFile(Request $request)
    {

        $fileToUpload = $request['fileToUpload'];
        $fileName = $fileToUpload->getClientOriginalName();
        $userID = Auth::user()->getAuthIdentifier();
        $chatName = $request['activeChatList'];
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
