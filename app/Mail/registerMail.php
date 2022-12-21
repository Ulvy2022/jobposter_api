<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class registerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($body)
    {
        foreach ($body as $content => $message){
            $this->$content = $message;
        }
    }

    
    public function build()
    {
        return $this->from("slmspnc519@gmail.com")->subject("Registeration")->view('registerUserToClient')
        ->with([
            'username'=>$this->username,
            'email'=>$this->email,
            'id'=>$this->id,
        ]);
    }
}
