<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Message extends Model
{
    use SoftDeletes;
    protected $table = 'Message';

    public function insertData(string $data, int $userId, string $chatName)
    {
        $currentTime = now();

        return Message::create([
            'text_message' => $data,
            'send_time' => $currentTime,
            'user_id' => $userId,
            'chat_name' => $chatName,
            'deleted' => 0,
        ]);
    }

    public function selectAllData(int $offset, int $limit): array
    {
        return Message::where('deleted', 0)
                      ->orderBy('send_time', 'ASC')
                      ->skip($offset)
                      ->take($limit)
                      ->get()
                      ->toArray();
    }

    public function updateData(int $id, string $newMessage)
    {
        $message = Message::find($id);
        $message->text_message = $newMessage;
        $message->save();
        return $message;
    }

    public function deleteData(int $id)
    {
        $message = Message::find($id)->de;
        $message->delete();
    }

    public function deleteDataphysical(int $id)
    {
        $message = Message::find($id);
        $message->deleted = 1;
        $message->save();
    }

    public function deleteChatHistory(string $chatlistName)
    {
        Message::where('chat_name', 'LIKE', $chatlistName . '%')->delete();
    }
}

