<?php

namespace App\Listeners;

use App\Events\MessageDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DeleteMessageFile
{
    /**
     * Handle the event.
     *
     * @param  MessageDeleted  $event
     * @return void
     */
    public function handle(MessageDeleted $event)
    {

        $message = $event->message;

        if ($message->file_path) {
            Storage::delete($message->file_path);
        }
    }
}
