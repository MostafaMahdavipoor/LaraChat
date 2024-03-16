<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{

    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('text_message');
            $table->timestamp('send_time');
            $table->integer('user_id');
            $table->string('chat_name');
            $table->tinyInteger('deleted')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
