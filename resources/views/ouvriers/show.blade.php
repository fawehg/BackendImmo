<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'ouvrier</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 20px;
        }
        hr {
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        .form-input {
            display: inline-block;
            width: calc(100% - 170px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .icon {
            margin-right: 10px;
            font-size: 20px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Détails de l'ouvrier</h1>
        <hr>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-user icon"></i>Nom :</label>
            <input type="text" class="form-input" value="{{ $user->nom }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-user icon"></i>Prénom :</label>
            <input type="text" class="form-input" value="{{ $user->prenom }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-envelope icon"></i>Email :</label>
            <input type="email" class="form-input" value="{{ $user->email }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-map-marker-alt icon"></i>Ville :</label>
            <input type="text" class="form-input" value="{{ $user->ville }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-home icon"></i>Adresse :</label>
            <input type="text" class="form-input" value="{{ $user->adresse }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-briefcase icon"></i>Profession :</label>
            <input type="text" class="form-input" value="{{ $user->profession }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-clock icon"></i>Heure de début :</label>
            <input type="text" class="form-input" value="{{ $user->heureDebut }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-clock icon"></i>Heure de fin :</label>
            <input type="text" class="form-input" value="{{ $user->heureFin }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-phone icon"></i>Numéro de téléphone :</label>
            <input type="text" class="form-input" value="{{ $user->numeroTelephone }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-calendar-alt icon"></i>Date de création :</label>
            <input type="text" class="form-input" value="{{ $user->created_at }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-calendar-check icon"></i>Date de mise à jour :</label>
            <input type="text" class="form-input" value="{{ $user->updated_at }}" readonly>
        </div>
    </div>
</body>
</html>
