<?php
namespace App\Models\messenger;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = ['text_message', 'send_time', 'user_id', 'chat_name'];

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

    public static function getMessage($uploaded)
    {
        $perPage = 5;
        $dataPage = self::orderBy('send_time', 'desc')->paginate($perPage);

        if ($uploaded > $dataPage->lastPage()) {
            //throw new \OutOfBoundsException('Uploaded page number exceeds available pages.');
        }

        return $dataPage;
    }

}
