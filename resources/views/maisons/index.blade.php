@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Maisons | Tableau de Bord</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--couleur-fond);
            color: var(--couleur-texte);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .header-section::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(58, 79, 122, 0.2), transparent);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--couleur-primaire);
            margin: 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition-fluide);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--couleur-primaire);
            color: white;
            box-shadow: 0 4px 12px rgba(58, 79, 122, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--couleur-secondaire);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(58, 79, 122, 0.25);
        }

        .table-container {
            background-color: var(--couleur-carte);
            border-radius: var(--border-radius);
            box-shadow: var(--ombre-legere);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.95rem;
        }

        .table thead th {
            background-color: var(--couleur-primaire);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
            border: none;
        }

        .table thead th:first-child {
            border-top-left-radius: var(--border-radius);
        }

        .table thead th:last-child {
            border-top-right-radius: var(--border-radius);
        }

        .table tbody tr {
            transition: var(--transition-fluide);
        }

        .table tbody tr:hover {
            background-color: rgba(107, 143, 212, 0.05);
        }

        .table tbody td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-secondary {
            background-color: #E9ECEF;
            color: var(--couleur-texte);
        }

        .btn-secondary:hover {
            background-color: #DEE2E6;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: #FFF3CD;
            color: #856404;
        }

        .btn-warning:hover {
            background-color: #FFEEBA;
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

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--couleur-texte-secondaire);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #E9ECEF;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28A745;
        }

        .status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6C757D;
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .action-buttons {
                flex-wrap: wrap;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border-radius: var(--border-radius);
                box-shadow: var(--ombre-legere);
            }
            
            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            }
            
            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                margin-right: 1rem;
                color: var(--couleur-primaire);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1 class="page-title">Gestion des Maisons</h1>
            <a href="{{ route('maisons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une maison
            </a>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Prix</th>
                        <th>Ville</th>
                        <th>Délégation</th>
                        <th>Superficie (m²)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($maisons->count() > 0)
                        @foreach($maisons as $maison)
                            <tr>
                                <td data-label="#">{{ $loop->iteration }}</td>
                                <td data-label="Titre">{{ $maison->titre }}</td>
                                <td data-label="Prix">{{ number_format($maison->prix, 2) }} TND</td>
                                <td data-label="Ville">{{ $maison->ville->nom }}</td>
                                <td data-label="Délégation">{{ $maison->delegation->nom }}</td>
                                <td data-label="Superficie">{{ $maison->superficie }} m²</td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('maisons.show', $maison->id) }}" class="btn btn-secondary btn-sm" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('maisons.edit', $maison->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('maisons.destroy', $maison->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette maison ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-home"></i>
                                    <p>Aucune maison enregistrée</p>
                                    <a href="{{ route('maisons.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Ajouter une maison
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
@endsection
