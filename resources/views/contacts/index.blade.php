@extends('layouts.app')

@section('title', 'Gestion des Contacts')

@section('contents')
<div class="container">
    <div class="header-section">
        <h1 class="page-title">Gestion des Contacts</h1>

        </a>
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($contacts->count() > 0)
                    @foreach($contacts as $contact)
                        <tr>
                            <td data-label="#">{{ $contact->id }}</td>
                            <td data-label="Nom">{{ $contact->name }}</td>
                            <td data-label="Téléphone">{{ $contact->phone }}</td>
                            <td data-label="Message" class="truncate-text">{{ Str::limit($contact->message, 50) }}</td>
                            <td data-label="Actions">
                                <div class="action-buttons">
                                    <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-secondary btn-sm" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')">
                                      
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-address-book"></i>
                                <p>Aucun contact enregistré</p>
                                <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter un contact
                                </a>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

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

.truncate-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
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
    
    .truncate-text {
        max-width: 100%;
    }
}
</style>
@endsection