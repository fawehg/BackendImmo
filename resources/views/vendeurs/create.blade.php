@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Vendeur | Tableau de Bord</title>
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
            --border-radius: 16px;
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
            max-width: 800px;
            margin: 3rem auto;
            padding: 3rem;
            background-color: var(--couleur-carte);
            border-radius: var(--border-radius);
            box-shadow: var(--ombre-legere);
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--couleur-primaire), var(--couleur-accent));
        }

        .form-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .form-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--couleur-primaire);
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .form-header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--couleur-accent);
            border-radius: 3px;
        }

        .form-header p {
            color: var(--couleur-texte-secondaire);
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--couleur-primaire);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
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

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--couleur-texte-secondaire);
        }

        .strength-bar {
            height: 4px;
            background: #E2E8F0;
            border-radius: 2px;
            margin-top: 0.25rem;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #E53E3E;
            transition: width 0.3s ease;
        }

        /* Style pour les erreurs de validation */
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
                padding: 2rem 1.5rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .form-header h1 {
                font-size: 1.8rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }
        .form-group:nth-child(7) { animation-delay: 0.7s; }
        .form-group:nth-child(8) { animation-delay: 0.8s; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Nouveau Vendeur</h1>
            <p>Remplissez les informations pour créer un nouveau compte vendeur</p>
        </div>

        <form method="POST" action="{{ route('vendeurs.store') }}" id="vendeurForm">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom"><i class="fas fa-user"></i>Nom</label>
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-input @error('nom') input-error @enderror" required autofocus>
                    @error('nom')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="prenom"><i class="fas fa-user"></i>Prénom</label>
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" class="form-input @error('prenom') input-error @enderror" required>
                    @error('prenom')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ville"><i class="fas fa-map-marker-alt"></i>Ville</label>
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input type="text" id="ville" name="ville" value="{{ old('ville') }}" class="form-input @error('ville') input-error @enderror" required>
                    @error('ville')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="adresse"><i class="fas fa-home"></i>Adresse</label>
                    <i class="fas fa-home input-icon"></i>
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" class="form-input @error('adresse') input-error @enderror" required>
                    @error('adresse')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="email"><i class="fas fa-envelope"></i>Adresse Email</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input @error('email') input-error @enderror" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i>Mot de passe</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input @error('password') input-error @enderror" required>
                    <div class="password-strength">
                        <div>Force du mot de passe: <span id="strength-text">Faible</span></div>
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm"><i class="fas fa-lock"></i>Confirmation</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-input" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> Créer le vendeur
            </button>
        </form>
    </div>

    <script>
        // Indicateur de force du mot de passe
        const passwordInput = document.getElementById('password');
        const strengthText = document.getElementById('strength-text');
        const strengthFill = document.getElementById('strength-fill');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Vérifier la longueur
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Vérifier la complexité
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Mettre à jour l'affichage
            updateStrengthIndicator(strength);
        });
        
        function updateStrengthIndicator(strength) {
            let color = '';
            let width = '0%';
            let text = 'Très faible';
            
            if (strength >= 4) {
                color = '#38A169';
                width = '100%';
                text = 'Très fort';
            } else if (strength === 3) {
                color = '#319795';
                width = '75%';
                text = 'Fort';
            } else if (strength === 2) {
                color = '#D69E2E';
                width = '50%';
                text = 'Moyen';
            } else if (strength === 1) {
                color = '#DD6B20';
                width = '25%';
                text = 'Faible';
            } else {
                color = '#E53E3E';
                width = '10%';
                text = 'Très faible';
            }
            
            strengthFill.style.width = width;
            strengthFill.style.background = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        }
    </script>
</body>
</html>
@endsection