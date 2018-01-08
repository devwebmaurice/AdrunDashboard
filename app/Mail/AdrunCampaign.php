<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdrunCampaign extends Mailable
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
        $subject = \Config::get('adrun.EMAIL.BILAN_CAMPAIGN');
        
        return $this->view('email.adrun-all-campaign')
                ->from($address, $name)
                ->subject($subject)
                ->attach(base_path().'/vendor/adrun/settings/bilan-des-campaign.xlsx');;
    }
}
