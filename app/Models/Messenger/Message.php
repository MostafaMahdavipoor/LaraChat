<?php
namespace App\Models\Messenger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = ['content_name','text_message', 'send_time', 'user_id', 'chat_name'];

    public static function insertMessage($chatName, $messageText, $userID)
    {
        $currentTime = time();
        $data = self::create([
            'text_message' => $messageText,
            'send_time' => $currentTime,
            'user_id' => $userID,
            'chat_name' => $chatName
        ]);
        return $data;
    }


    public static function softDeleteMessage($dataID)
    {
        $model = self::find($dataID);
        $model->delete();
        return $model;
    }
    public static function getMessage($page,$chatName)
    {
        $dataPage = self::where('chat_name',$chatName)->paginate(5, ['*'], 'page', $page);
        return $dataPage;
    }
    public static function uploadFile($fileName, $userID, $chatName)
    {
        $model = self::create(['content_name' => $fileName, 'send_time' => time(), 'user_id' => $userID, 'chat_name' => $chatName]);
        return $model;
    }


}
