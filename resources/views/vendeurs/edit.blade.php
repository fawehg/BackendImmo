@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Vendeur | Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --couleur-primaire: #3A4F7A;
            --couleur-secondaire: #4C6B9B;
            --couleur-accent: #6B8FD4;
            --couleur-texte: #2D3748;
            --couleur-texte-secondaire: #718096;
            --couleur-fond: #F8FAFC;
            --couleur-carte: #FFFFFF;
            --ombre-legere: 0 2px 12px rgba(0, 0, 0, 0.08);
            --ombre-portee: 0 10px 30px rgba(0, 0, 0, 0.12);
            --transition-fluide: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            --border-radius: 12px;
            --border-radius-input: 8px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--couleur-fond);
            color: var(--couleur-texte);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .form-container {
            max-width: 700px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: var(--couleur-carte);
            border-radius: var(--border-radius);
            box-shadow: var(--ombre-legere);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .form-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--couleur-primaire);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--couleur-texte-secondaire);
            font-size: 0.95rem;
        }

        .form-header::after {
            content: '';
            position: absolute;
            bottom: -1.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--couleur-primaire), var(--couleur-accent));
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--couleur-primaire);
            font-size: 0.95rem;
        }

        .form-label i {
            margin-right: 10px;
            color: var(--couleur-accent);
            width: 20px;
            text-align: center;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1.25rem 0.85rem 3rem;
            border: 1px solid #E2E8F0;
            border-radius: var(--border-radius-input);
            font-size: 0.95rem;
            transition: var(--transition-fluide);
            background-color: #F8FAFC;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--couleur-accent);
            box-shadow: 0 0 0 3px rgba(107, 143, 212, 0.15);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.85rem;
            color: var(--couleur-texte-secondaire);
            font-size: 1rem;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--couleur-primaire), var(--couleur-secondaire));
            color: white;
            border: none;
            border-radius: var(--border-radius-input);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition-fluide);
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(58, 79, 122, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(58, 79, 122, 0.25);
            background: linear-gradient(135deg, var(--couleur-secondaire), var(--couleur-primaire));
        }

        .btn-submit i {
            margin-right: 10px;
        }

        .password-note {
            font-size: 0.85rem;
            color: var(--couleur-texte-secondaire);
            margin-top: -0.75rem;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .error-message {
            color: #E53E3E;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .input-error {
            border-color: #FC8181;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 1.5rem;
                padding: 1.5rem;
            }
            
            .form-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Modifier le Vendeur</h1>
            <p>Mettez à jour les informations du vendeur</p>
        </div>

        <form action="{{ route('vendeurs.update', $vendeur->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i>Nom</label>
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $vendeur->nom) }}" class="form-input @error('nom') input-error @enderror" required>
                @error('nom')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prenom"><i class="fas fa-user"></i>Prénom</label>
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $vendeur->prenom) }}" class="form-input @error('prenom') input-error @enderror" required>
                @error('prenom')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ville"><i class="fas fa-map-marker-alt"></i>Ville</label>
                <i class="fas fa-map-marker-alt input-icon"></i>
                <input type="text" id="ville" name="ville" value="{{ old('ville', $vendeur->ville) }}" class="form-input @error('ville') input-error @enderror" required>
                @error('ville')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i>Téléphone</label>
                <i class="fas fa-phone input-icon"></i>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $vendeur->phone) }}" placeholder="+123 456 7890" class="form-input @error('phone') input-error @enderror">
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse"><i class="fas fa-home"></i>Adresse</label>
                <i class="fas fa-home input-icon"></i>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $vendeur->adresse) }}" class="form-input @error('adresse') input-error @enderror" required>
                @error('adresse')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i>Email</label>
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" id="email" name="email" value="{{ old('email', $vendeur->email) }}" class="form-input @error('email') input-error @enderror" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i>Mot de passe</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" name="password" class="form-input @error('password') input-error @enderror">
                <p class="password-note">Laissez vide pour ne pas modifier le mot de passe</p>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm"><i class="fas fa-lock"></i>Confirmation</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-input">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Mettre à jour
            </button>
        </form>
    </div>
</body>
</html>
@endsection
