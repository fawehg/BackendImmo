<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Demande; // Importer le modèle Demande

class NouvelleDemandeNotification extends Notification
{
    use Queueable;

    protected $demande;

    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    public function via($notifiable)
    {
        return ['mail']; // Envoyer la notification par email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de travail')
            ->greeting('Bonjour!')
            ->line('Vous avez reçu une nouvelle demande de travail.')
            ->line('Voici les détails de la demande :')
            ->line('Domaines : ' . $this->demande->domaines)
            ->line('Spécialités : ' . $this->demande->specialites)
            ->line('Ville : ' . $this->demande->city)
            ->line('Description : ' . $this->demande->description)
            ->action('Voir la demande', url('/demandes/' . $this->demande->id))
            ->salutation('Merci de répondre dès que possible.')
            ->line('Cordialement,')
            ->line(config('b2c'));
    }
    
}
