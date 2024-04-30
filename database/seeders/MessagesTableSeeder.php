<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {

        $users = User::all();

        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                DB::table('messages')->insert([
                    'text_message' => "Sample message $i",
                    'send_time' => time(),
                    'user_id' => 2,
                    'chat_name' => "farawin",
                    'chat_type' => 'group',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
