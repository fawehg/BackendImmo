<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la demande</title>
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
        <h1>Modifier la demande</h1>
        <form action="{{ route('demandes.update', $demande->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <label for="domaines"><i class="fas fa-list icon"></i>Domaines</label>
            <input type="text" id="domaines" name="domaines" value="{{ $demande->domaines }}" required>

            <label for="specialites"><i class="fas fa-list icon"></i>Spécialités</label>
            <input type="text" id="specialites" name="specialites" value="{{ $demande->specialites }}" required>

            <label for="city"><i class="fas fa-map-marker-alt icon"></i>Ville</label>
            <input type="text" id="city" name="city" value="{{ $demande->city }}" required>

            <label for="date"><i class="far fa-calendar-alt icon"></i>Date</label>
            <input type="date" id="date" name="date" value="{{ $demande->date }}" required>

            <label for="time"><i class="far fa-clock icon"></i>Heure</label>
            <input type="time" id="time" name="time" value="{{ $demande->time }}" required>

            <label for="description"><i class="fas fa-align-left icon"></i>Description</label>
            <textarea id="description" name="description" rows="4" required>{{ $demande->description }}</textarea>

            <label for="image"><i class="far fa-image icon"></i>Image</label>
            <input type="file" id="image" name="image" accept="image/*">

            <button type="submit">Modifier</button>
        </form>
    </div>
</body>
</html>
