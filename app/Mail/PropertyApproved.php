<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $property;

    public function __construct($property)
    {
        $this->property = $property;
    }

    public function build()
    {
        return $this->subject('Votre annonce a été approuvée')
                    ->view('emails.property_approved');
    }
}