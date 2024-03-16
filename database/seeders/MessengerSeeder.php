<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run()
    {

        for ($i = 1; $i <= 10; $i++) {
            Message::create([
                'text_message' => "Test message $i",
                'send_time' => now(),
                'user_id' =>444 , 
                'chat_name' => 'Farawin',
                'deleted' => 0,
            ]);
        }
    }
}
