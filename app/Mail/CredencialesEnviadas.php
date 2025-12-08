<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesEnviadas extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $plainPassword;

    public function __construct(User $user, string $plainPassword)
    {
        $this->user          = $user;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        $activationUrl = route('account.activate', [
            'id'   => $this->user->id,
            'hash' => sha1($this->user->email),
        ]);

        return $this->subject('FertiGyn – Activación de cuenta')
            ->view('emails.users.credentials')
            ->with([
                'user'          => $this->user,
                'plainPassword' => $this->plainPassword,
                'activationUrl' => $activationUrl,
            ]);
    }
}
