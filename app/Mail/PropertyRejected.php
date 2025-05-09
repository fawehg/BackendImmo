<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $reason;

    public function __construct($property, $reason)
    {
        $this->property = $property;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Votre annonce a été rejetée')
                    ->view('emails.property_rejected');
    }
}