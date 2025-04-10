<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la demande</title>
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
        <h1>Détails de la demande</h1>
        <hr>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-list icon"></i>Domaines :</label>
            <input type="text" class="form-input" value="{{ $demande->domaines }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-list icon"></i>Spécialités :</label>
            <input type="text" class="form-input" value="{{ $demande->specialites }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-map-marker-alt icon"></i>Ville :</label>
            <input type="text" class="form-input" value="{{ $demande->city }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-calendar-alt icon"></i>Date :</label>
            <input type="text" class="form-input" value="{{ $demande->date }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-clock icon"></i>Heure :</label>
            <input type="text" class="form-input" value="{{ $demande->time }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-align-left icon"></i>Description :</label>
            <textarea class="form-input" rows="3" readonly>{{ $demande->description }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label"><i class="far fa-image icon"></i>Image :</label>
            @if($demande->image)
                <img src="{{ asset($demande->image) }}" alt="Image de la demande" style="max-width: 100px;">
            @else
                Aucune image
            @endif
        </div>
    </div>
</body>
</html>
