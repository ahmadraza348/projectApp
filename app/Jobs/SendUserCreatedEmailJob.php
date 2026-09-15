<?php

namespace App\Jobs;

use App\Mail\UserCreatedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as FoundationQueueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SendUserCreatedEmailJob implements ShouldQueue
{
    use FoundationQueueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public User $user,
        public string $encryptedPassword
    ) {
    }

    public function handle(): void
    {
        $plainPassword = Crypt::decryptString(
            $this->encryptedPassword
        );

        Mail::to($this->user->email)
            ->send(
                new UserCreatedMail(
                    $this->user,
                    $plainPassword
                )
            );
    }
}
