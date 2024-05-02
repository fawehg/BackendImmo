<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre demande a été acceptée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        p {
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Votre demande a été acceptée</h1>
        <p>Bonjour {{ $clientName }},</p>
        <p>Votre demande a été acceptée. Voici les détails :</p>
        <p><strong>Description de la demande :</strong> {{ $demandeDescription }}</p>
        <p>Merci.</p>
    </div>
    <div class="footer">
        <p>Cordialement,<br>b2c</p>
    </div>
</body>
</html>
