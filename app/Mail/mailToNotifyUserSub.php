<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class mailToNotifyUserSub extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body)
    {
        foreach ($body as $content => $message) {
            $this->$content = $message;
        }
    }


    public function build()
    {
        return $this->from("slmspnc519@gmail.com")->subject("Subscribstion Expired")->view('mailToNotifyuser')
            ->with(
                [
                    'username' => $this->username,
                    'sub' => $this->sub,
                    'expire' => $this->expire,
                    'email' => $this->email,
                    'plan' => $this->plan,
                ]
            );
    }
}
