@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Maison | Tableau de Bord</title>
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
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
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

        .profile-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 3rem;
            background-color: var(--card-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .profile-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .profile-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .profile-header::after {
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

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(58, 79, 122, 0.25);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .detail-item {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--primary-color);
            font-size: 0.95rem;
        }

        .detail-label i {
            margin-right: 10px;
            color: var(--accent-color);
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .detail-value {
            padding: 1rem;
            background-color: #F8FAFC;
            border-radius: var(--radius-md);
            border-left: 4px solid var(--accent-color);
            font-size: 0.95rem;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }

        .image-gallery img {
            max-width: 150px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.75rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(58, 79, 122, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(58, 79, 122, 0.25);
        }

        .btn-secondary {
            background-color: #E9ECEF;
            color: var(--text-color);
        }

        .btn-secondary:hover {
            background-color: #DEE2E6;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .btn-danger:hover {
            background-color: #F5C6CB;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .profile-container {
                margin: 1.5rem;
                padding: 2rem 1.5rem;
            }
            
            .profile-header h1 {
                font-size: 1.8rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .detail-item {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .detail-item:nth-child(1) { animation-delay: 0.1s; }
        .detail-item:nth-child(2) { animation-delay: 0.2s; }
        .detail-item:nth-child(3) { animation-delay: 0.3s; }
        .detail-item:nth-child(4) { animation-delay: 0.4s; }
        .detail-item:nth-child(5) { animation-delay: 0.5s; }
        .detail-item:nth-child(6) { animation-delay: 0.6s; }
        .detail-item:nth-child(7) { animation-delay: 0.7s; }
        .detail-item:nth-child(8) { animation-delay: 0.8s; }
        .detail-item:nth-child(9) { animation-delay: 0.9s; }
        .detail-item:nth-child(10) { animation-delay: 1.0s; }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($maison->titre, 0, 1)) }}
            </div>
            <h1>{{ $maison->titre }}</h1>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-money-bill"></i>
                    <span>Prix</span>
                </div>
                <div class="detail-value">
                    {{ number_format($maison->prix, 2) }} TND
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-ruler"></i>
                    <span>Superficie</span>
                </div>
                <div class="detail-value">
                    {{ $maison->superficie }} m²
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-bed"></i>
                    <span>Nombre de chambres</span>
                </div>
                <div class="detail-value">
                    {{ $maison->nombre_chambres ?? 'Non spécifié' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-door-open"></i>
                    <span>Nombre de pièces</span>
                </div>
                <div class="detail-value">
                    {{ $maison->nombre_pieces ?? 'Non spécifié' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Année de construction</span>
                </div>
                <div class="detail-value">
                    {{ $maison->annee_construction ?? 'Non spécifié' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-couch"></i>
                    <span>Meublé</span>
                </div>
                <div class="detail-value">
                    {{ $maison->meuble ? 'Oui' : 'Non' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Type de transaction</span>
                </div>
                <div class="detail-value">
                    {{ $maison->typeTransaction->nom }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-tags"></i>
                    <span>Catégorie</span>
                </div>
                <div class="detail-value">
                    {{ $maison->categorie->nom }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Ville</span>
                </div>
                <div class="detail-value">
                    {{ $maison->ville->nom }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-map"></i>
                    <span>Délégation</span>
                </div>
                <div class="detail-value">
                    {{ $maison->delegation->nom }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-leaf"></i>
                    <span>Environnement</span>
                </div>
                <div class="detail-value">
                    {{ $maison->environnement->nom ?? 'Non spécifié' }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-home"></i>
                    <span>Adresse</span>
                </div>
                <div class="detail-value">
                    {{ $maison->adresse }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-file-alt"></i>
                    <span>Description</span>
                </div>
                <div class="detail-value">
                    {{ $maison->description }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Date de création</span>
                </div>
                <div class="detail-value">
                    {{ $maison->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-calendar-check"></i>
                    <span>Dernière mise à jour</span>
                </div>
                <div class="detail-value">
                    {{ $maison->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        @if($maison->images && count($maison->images) > 0)
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-images"></i>
                    <span>Images</span>
                </div>
                <div class="image-gallery">
                    @foreach($maison->images as $image)
                        <img src="{{ Storage::url($image) }}" alt="Image de la maison">
                    @endforeach
                </div>
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('maisons.edit', $maison->id) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <form action="{{ route('maisons.destroy', $maison->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette maison ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</body>
</html>
@endsection
