@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Client | Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3A4F7A;
            --secondary-color: #4C6B9B;
            --accent-color: #6B8FD4;
            --text-color: #2D3748;
            --light-text: #718096;
            --bg-color: #F8FAFC;
            --card-color: #FFFFFF;
            --shadow-sm: 0 2px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --radius-lg: 16px;
            --radius-md: 8px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .form-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 3rem;
            background-color: var(--card-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            position: relative;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-top-left-radius: var(--radius-lg);
            border-top-right-radius: var(--radius-lg);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .form-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-header::after {
            content: '';
            position: absolute;
            bottom: -1.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        @media (max-width: 768px) {
            .form-group.full-width {
                grid-column: span 1;
            }
        }

        .form-label {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--primary-color);
            font-size: 0.95rem;
        }

        .form-label i {
            margin-right: 10px;
            color: var(--accent-color);
            width: 20px;
            text-align: center;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1.25rem 0.85rem 3rem;
            border: 1px solid #E2E8F0;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: #F8FAFC;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(107, 143, 212, 0.15);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.85rem;
            color: var(--light-text);
            font-size: 1rem;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(58, 79, 122, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(58, 79, 122, 0.25);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        .btn-submit i {
            margin-right: 10px;
        }

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--light-text);
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

        .error-message {
            color: #E53E3E;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .input-error {
            border-color: #FC8181;
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
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Nouveau Client</h1>
            <p>Remplissez les informations pour créer un nouveau client</p>
        </div>

        <form action="{{ route('clients.store') }}" method="POST" id="clientForm">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom"><i class="fas fa-user"></i>Nom</label>
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-input @error('nom') input-error @enderror" required>
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
                    <label for="ville"><i class="fas fa-city"></i>Ville</label>
                    <i class="fas fa-city input-icon"></i>
                    <input type="text" id="ville" name="ville" value="{{ old('ville') }}" class="form-input @error('ville') input-error @enderror" required>
                    @error('ville')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="adresse"><i class="fas fa-map-marker-alt"></i>Adresse</label>
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" class="form-input @error('adresse') input-error @enderror" required>
                    @error('adresse')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="email"><i class="fas fa-envelope"></i>Email</label>
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
                    <label for="password_confirmation"><i class="fas fa-lock"></i>Confirmation</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> Créer le client
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