<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendCV extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct()
    {

    }


    public function build()
    {
        return $this->from("slmspnc519@gmail.com")->subject("Candidate Applied CV")->view('sendCV');
    }
}
