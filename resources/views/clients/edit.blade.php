<!DOCTYPE html>
<html>
<head>
    <title>Modifier le client</title>
    <!-- Ajout de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        /* Style pour les icônes */
        .icon {
            position: relative;
            top: 5px;
            margin-right: 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier le client</h1>
        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="nom"><i class="fas fa-user icon"></i>Nom</label>
            <input type="text" id="nom" name="nom" value="{{ $client->nom }}" required>

            <label for="prenom"><i class="fas fa-user icon"></i>Prénom</label>
            <input type="text" id="prenom" name="prenom" value="{{ $client->prenom }}" required>

            <label for="ville"><i class="fas fa-map-marker-alt icon"></i>Ville</label>
            <input type="text" id="ville" name="ville" value="{{ $client->ville }}" required>

            <label for="adresse"><i class="fas fa-home icon"></i>Adresse</label>
            <input type="text" id="adresse" name="adresse" value="{{ $client->adresse }}" required>

            <label for="email"><i class="fas fa-envelope icon"></i>Email</label>
            <input type="email" id="email" name="email" value="{{ $client->email }}" required>

            <label for="password"><i class="fas fa-lock icon"></i>Mot de passe</label>
            <input type="password" id="password" name="password">

            <button type="submit">Modifier</button>
        </form>
    </div>
</body>
</html>
