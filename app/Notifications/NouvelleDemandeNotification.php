<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Demande; // Importer le modèle Demande
use App\Models\Client;

class NouvelleDemandeNotification extends Notification
{
    use Queueable;

    
protected $demande;
protected $client;

public function __construct(Demande $demande, Client $client)
{
    $this->demande = $demande;
    $this->client = $client;
}

    public function via($notifiable)
    {
        return ['mail']; 
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('emails.notification', ['client' => $this->client, 'demande' => $this->demande]);
    }
    


    
}
