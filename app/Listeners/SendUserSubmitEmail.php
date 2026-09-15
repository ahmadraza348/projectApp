<?php

namespace App\Listeners;

use App\Events\UserSubmitEmail;
use App\Jobs\SendUserCreatedEmailJob;
use Illuminate\Support\Facades\Crypt;

class SendUserSubmitEmail
{

    public function handle(UserSubmitEmail $event): void
    {
        // Encrypt the temporary password before putting it in the queue. 
        $encryptedPassword = Crypt::encryptString( $event->plainPassword );
       SendUserCreatedEmailJob::dispatch($event->user, $encryptedPassword);
    }
}
