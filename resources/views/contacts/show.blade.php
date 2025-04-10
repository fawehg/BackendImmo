<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du contact</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Vos styles CSS ici */
    </style>
</head>
<body>
    <div class="container">
        <h1>Détails du contact</h1>
        <hr>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-user icon"></i>Nom :</label>
            <input type="text" class="form-input" value="{{ $contact->name }}" readonly>
        </div>
        <!-- Ajoutez d'autres champs de contact ici en fonction de vos besoins -->
    </div>
</body>
</html>
