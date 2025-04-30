@extends('layouts.app')

@section('contents')
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
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            padding: 1rem;
            box-sizing: border-box;
        }

        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .header-section::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(58, 79, 122, 0.2), transparent);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--couleur-primaire);
            margin: 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            transition: var(--transition-fluide);
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .btn i {
            margin-right: 6px;
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
            overflow-x: auto;
            max-width: 100%;
        }

        .table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            table-layout: fixed;
        }

        .table thead th {
            background-color: var(--couleur-primaire);
            color: white;
            padding: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
            border: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table thead th:first-child {
            border-top-left-radius: var(--border-radius);
            width: 5%;
        }

        .table thead th:last-child {
            border-top-right-radius: var(--border-radius);
            width: 15%;
        }

        .table tbody tr {
            transition: var(--transition-fluide);
        }

        .table tbody tr:hover {
            background-color: rgba(107, 143, 212, 0.05);
        }

        .table tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
            justify-content: flex-end;
        }

        .btn-secondary, .btn-warning, .btn-danger {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
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
            padding: 2rem;
            text-align: center;
            color: var(--couleur-texte-secondaire);
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: #E9ECEF;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.3rem 0.6rem;
            border-radius: 50px;
            font-size: 0.7rem;
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
            .container {
                padding: 0.5rem;
            }

            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th, .table tbody td {
                padding: 0.5rem;
            }

            .table thead th:first-child, .table tbody td:first-child {
                width: 8%;
            }

            .table thead th:last-child, .table tbody td:last-child {
                width: 20%;
            }

            .action-buttons {
                gap: 0.3rem;
            }

            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: var(--border-radius);
                box-shadow: var(--ombre-legere);
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.03);
                white-space: normal;
            }

            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                margin-right: 0.5rem;
                color: var(--couleur-primaire);
                flex: 1;
            }

            .action-buttons {
                flex-wrap: wrap;
                justify-content: flex-start;
            }
        }
    </style>

    <div class="container">
        <div class="header-section">
            <h1 class="page-title">Gestion des Étages de Villas</h1>
            <a href="{{ route('etagesvillas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un étage de villa
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
                        <th>Superficie</th>
                        <th>Numéro Étage</th>
                        <th>Accès Indépendant</th>
                        <th>Parking Inclus</th>
                        <th>Année Construction</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($etagesvillas->count() > 0)
                        @foreach($etagesvillas as $etagevilla)
                            <tr>
                                <td data-label="#">{{ $loop->iteration }}</td>
                                <td data-label="Titre">{{ $etagevilla->titre }}</td>
                                <td data-label="Prix">{{ number_format($etagevilla->prix, 2) }} TND</td>
                                <td data-label="Ville">{{ $etagevilla->ville->nom }}</td>
                                <td data-label="Délégation">{{ $etagevilla->delegation->nom }}</td>
                                <td data-label="Superficie">{{ $etagevilla->superficie }} m²</td>
                                <td data-label="Numéro Étage">{{ $etagevilla->numero_etage }}</td>
                                <td data-label="Accès Indépendant">{{ $etagevilla->acces_independant ? 'Oui' : 'Non' }}</td>
                                <td data-label="Parking Inclus">{{ $etagevilla->parking_inclus ? 'Oui' : 'Non' }}</td>
                                <td data-label="Année Construction">{{ $etagevilla->annee_construction ?? 'N/A' }}</td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('etagesvillas.show', $etagevilla->id) }}" class="btn btn-secondary btn-sm" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('etagesvillas.edit', $etagevilla->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('etagesvillas.destroy', $etagevilla->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étage de villa ?')">
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
                            <td colspan="11">
                                <div class="empty-state">
                                    <i class="fas fa-home"></i>
                                    <p>Aucun étage de villa enregistré</p>
                                    <a href="{{ route('etagesvillas.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Ajouter un étage de villa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection