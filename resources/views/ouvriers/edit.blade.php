<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'user</title>
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
        <h1>Modifier l'user</h1>
        <form action="{{ route('ouvriers.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="nom"><i class="fas fa-user icon"></i>Nom</label>
            <input type="text" id="nom" name="nom" value="{{ $user->nom }}" required>

            <label for="prenom"><i class="fas fa-user icon"></i>Prénom</label>
            <input type="text" id="prenom" name="prenom" value="{{ $user->prenom }}" required>

            <label for="ville"><i class="fas fa-map-marker-alt icon"></i>Ville</label>
            <input type="text" id="ville" name="ville" value="{{ $user->ville }}" required>

            <label for="adresse"><i class="fas fa-home icon"></i>Adresse</label>
            <input type="text" id="adresse" name="adresse" value="{{ $user->adresse }}" required>

            <label for="email"><i class="fas fa-envelope icon"></i>Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>

            <label for="profession"><i class="fas fa-briefcase icon"></i>Profession</label>
            <input type="text" id="profession" name="profession" value="{{ $user->profession }}" required>

            <label for="heureDebut"><i class="far fa-clock icon"></i>Heure de début</label>
            <input type="text" id="heureDebut" name="heureDebut" value="{{ $user->heureDebut }}" required>

            <label for="heureFin"><i class="far fa-clock icon"></i>Heure de fin</label>
            <input type="text" id="heureFin" name="heureFin" value="{{ $user->heureFin }}" required>

            <label for="numeroTelephone"><i class="fas fa-phone icon"></i>Numéro de téléphone</label>
            <input type="text" id="numeroTelephone" name="numeroTelephone" value="{{ $user->numeroTelephone }}" required>

            <button type="submit">Modifier</button>
        </form>
    </div>
</body>
</html>
