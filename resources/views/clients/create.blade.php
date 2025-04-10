<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un client</title>
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
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        div {
            margin-bottom: 20px;
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
        .icon {
            display: inline-block;
            width: 30px;
            text-align: center;
            line-height: 40px;
            color: #007bff;

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
    </style>
</head>
<body>
    <h1>Ajouter un client</h1>
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div>
            <label for="nom"><span class="icon"><i class="fas fa-user"></i></span>Nom :</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="prenom"><span class="icon"><i class="fas fa-user"></i></span>Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div>
            <label for="ville"><span class="icon"><i class="fas fa-city"></i></span>Ville :</label>
            <input type="text" id="ville" name="ville" required>
        </div>
        <div>
            <label for="adresse"><span class="icon"><i class="fas fa-map-marker-alt"></i></span>Adresse :</label>
            <input type="text" id="adresse" name="adresse" required>
        </div>
        <div>
            <label for="email"><span class="icon"><i class="fas fa-envelope"></i></span>Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password"><span class="icon"><i class="fas fa-lock"></i></span>Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
