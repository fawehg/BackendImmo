<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du client</title>
    <!-- Ajout de Font Awesome -->
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
        }
        hr {
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .col {
            flex: 0 0 48%;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 30px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[readonly] {
            background-color: #eee;
            cursor: not-allowed;
        }
        .icon {
            display: inline-block;
            width: 30px;
            text-align: center;
            line-height: 40px;
            color: #007bff;

        }
    </style>
</head>
<body>
    <h1>Détails du client</h1>
    <hr>
    <div class="row">
        <div class="col">
            <label for="nom"><span class="icon"><i class="fas fa-user"></i></span>Nom :</label>
            <input type="text" id="nom" name="nom" value="{{ $client->nom }}" readonly>
        </div>
        <div class="col">
            <label for="prenom"><span class="icon"><i class="fas fa-user"></i></span>Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="{{ $client->prenom }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="ville"><span class="icon"><i class="fas fa-map-marker-alt"></i></span>Ville :</label>
            <input type="text" id="ville" name="ville" value="{{ $client->ville }}" readonly>
        </div>
        <div class="col">
            <label for="adresse"><span class="icon"><i class="fas fa-home"></i></span>Adresse :</label>
            <input type="text" id="adresse" name="adresse" value="{{ $client->adresse }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="email"><span class="icon"><i class="fas fa-envelope"></i></span>Email :</label>
            <input type="email" id="email" name="email" value="{{ $client->email }}" readonly>
        </div>
        <div class="col">
            <label for="password"><span class="icon"><i class="fas fa-lock"></i></span>Mot de passe :</label>
            <input type="password" id="password" value="********" readonly>
        </div>
    </div>
</body>
</html>
