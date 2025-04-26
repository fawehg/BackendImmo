@extends('layouts.app')
  
@section('title', 'Profil Utilisateur')
  
@section('contents')
<div class="profile-container">
    <div class="profile-header">
    <div class="profile-avatar">
    @if (auth()->user()->avatar)
        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
    @else
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    @endif
</div>
        <h1>Paramètres du Profil</h1>
    </div>

    <form method="POST" enctype="multipart/form-data" action="{{ route('profile') }}" class="profile-form">
        @csrf

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-user"></i>
                    <span>Nom complet</span>
                </div>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" placeholder="Votre nom">
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-envelope"></i>
                    <span>Email</span>
                </div>
                <input type="text" name="email" class="form-control" value="{{ auth()->user()->email }}" placeholder="Email" disabled>
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-phone"></i>
                    <span>Téléphone</span>
                </div>
                <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" placeholder="Numéro de téléphone">
            </div>

            <div class="detail-item">
                <div class="detail-label">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Adresse</span>
                </div>
                <input type="text" name="address" class="form-control" value="{{ auth()->user()->address }}" placeholder="Adresse">
            </div>

            <div class="detail-item full-width">
                <div class="detail-label">
                    <i class="fas fa-image"></i>
                    <span>Photo de profil</span>
                </div>
                <input type="file" name="avatar" class="form-control-file">
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<style>
    .profile-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3A4F7A, #6B8FD4);
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 600;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #3A4F7A;
    }

    .detail-label i {
        margin-right: 10px;
        color: #6B8FD4;
        width: 20px;
        text-align: center;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #6B8FD4;
        box-shadow: 0 0 0 3px rgba(107, 143, 212, 0.2);
        outline: none;
    }

    .form-control[disabled] {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .form-control-file {
        width: 100%;
        padding: 0.5rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-primary {
        background-color: #3A4F7A;
        color: white;
    }

    .btn-primary:hover {
        background-color: #4C6B9B;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #E9ECEF;
        color: #2D3748;
    }

    .btn-secondary:hover {
        background-color: #DEE2E6;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 1.5rem;
            margin: 1rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endsection