<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdrunAdvertiser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'no-reply@adrun.re';
        $name    = \Config::get('adrun.EMAIL.SERVER_NAME');
        $subject = \Config::get('adrun.EMAIL.ANNOCEUR_NEW');
        
        return $this->view('email.adrun-new-advertiser')
                ->from($address, $name)
                ->subject($subject);
    }
}
