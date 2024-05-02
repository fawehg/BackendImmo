<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AcceptanceNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $demandeDescription;

    /**
     * Create a new message instance.
     *
     * @param string $clientName
     * @param string $demandeDescription
     * @return void
     */
    public function __construct($clientName, $demandeDescription)
    {
        $this->clientName = $clientName;
        $this->demandeDescription = $demandeDescription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.acceptance-notification')
                    ->subject('Votre demande a été acceptée');
    }
}
