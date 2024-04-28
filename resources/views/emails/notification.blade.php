<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande de travail</title>
    <style>
        /* Styles CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4caf50;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            padding: 20px;
            text-align: center;
        }
        .button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Nouvelle demande de travail</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Vous avez reçu une nouvelle demande de travail de <strong>{{ $client->prenom }} {{ $client->nom }}</strong>.</p>
            <p><strong>Nom du client:</strong> {{ $client->nom }}</p>
            <p><strong>Prénom du client:</strong> {{ $client->prenom }}</p>
            <p><strong>Adresse du client:</strong> {{ $client->adresse }}</p>

            <hr style="border-top: 1px solid #ddd; margin: 20px 0;">
            <p><strong>Voici les détails de la demande :</strong></p>
            <p><strong>Domaines:</strong> {{ $demande->domaines }}</p>
            <p><strong>Spécialités:</strong> {{ $demande->specialites }}</p>
            <p><strong>Ville:</strong> {{ $demande->city }}</p>
            <p><strong>Description:</strong> {{ $demande->description }}</p>
        </div>
        <div class="footer">
            <a href="{{ url('/demandes/' . $demande->id) }}" class="button">Voir la demande</a>
        </div>
    </div>
    <p style="text-align: center; margin-top: 20px;">Merci de répondre dès que possible.</p>
    <p style="text-align: center;">Cordialement,</p>
    <p style="text-align: center;">{{ config('app.name') }}</p>
</body>
</html>
