@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Appartement | Tableau de Bord</title>
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
            max-width: 800px;
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

        .form-input, select.form-input {
            width: 100%;
            padding: 0.85rem 1.25rem 0.85rem 3rem;
            border: 1px solid #E2E8F0;
            border-radius: var(--border-radius-input);
            font-size: 0.95rem;
            transition: var(--transition-fluide);
            background-color: #F8FAFC;
        }

        .form-input:focus, select.form-input:focus {
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
            <h1>Modifier l'Appartement</h1>
            <p>Mettez à jour les informations de l'appartement</p>
        </div>

        <form action="{{ route('appartements.update', $appartement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="titre"><i class="fas fa-home"></i>Titre</label>
                <i class="fas fa-home input-icon"></i>
                <input type="text" id="titre" name="titre" value="{{ old('titre', $appartement->titre) }}" class="form-input @error('titre') input-error @enderror" required>
                @error('titre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prix"><i class="fas fa-money-bill"></i>Prix</label>
                <i class="fas fa-money-bill input-icon"></i>
                <input type="number" id="prix" name="prix" value="{{ old('prix', $appartement->prix) }}" class="form-input @error('prix') input-error @enderror" required>
                @error('prix')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="superficie"><i class="fas fa-ruler"></i>Superficie (m²)</label>
                <i class="fas fa-ruler input-icon"></i>
                <input type="number" id="superficie" name="superficie" value="{{ old('superficie', $appartement->superficie) }}" class="form-input @error('superficie') input-error @enderror" required>
                @error('superficie')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="superficie_couvert"><i class="fas fa-ruler-combined"></i>Superficie couverte (m²)</label>
                <i class="fas fa-ruler-combined input-icon"></i>
                <input type="number" id="superficie_couvert" name="superficie_covered" value="{{ old('superficie_couvert', $appartement->superficie_couvert) }}" class="form-input @error('superficie_couvert') input-error @enderror">
                @error('superficie_couvert')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="etage"><i class="fas fa-building"></i>Étage</label>
                <i class="fas fa-building input-icon"></i>
                <input type="number" id="etage" name="etage" value="{{ old('etage', $appartement->etage) }}" class="form-input @error('etage') input-error @enderror">
                @error('etage')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="meuble"><i class="fas fa-couch"></i>Meublé</label>
                <i class="fas fa-couch input-icon"></i>
                <select id="meuble" name="meuble" class="form-input @error('meuble') input-error @enderror">
                    <option value="1" {{ old('meuble', $appartement->meuble) == '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('meuble', $appartement->meuble) == '0' ? 'selected' : '' }}>Non</option>
                </select>
                @error('meuble')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ville_id"><i class="fas fa-map-marker-alt"></i>Ville</label>
                <i class="fas fa-map-marker-alt input-icon"></i>
                <select id="ville_id" name="ville_id" class="form-input @error('ville_id') input-error @enderror" required>
                    <option value="">Sélectionnez une ville</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville->id }}" {{ old('ville_id', $appartement->ville_id) == $ville->id ? 'selected' : '' }}>{{ $ville->nom }}</option>
                    @endforeach
                </select>
                @error('ville_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="delegation_id"><i class="fas fa-map"></i>Délégation</label>
                <i class="fas fa-map input-icon"></i>
                <select id="delegation_id" name="delegation_id" class="form-input @error('delegation_id') input-error @enderror" required>
                    <option value="">Sélectionnez une délégation</option>
                    @foreach($delegations as $delegation)
                        <option value="{{ $delegation->id }}" {{ old('delegation_id', $appartement->delegation_id) == $delegation->id ? 'selected' : '' }}>{{ $delegation->nom }}</option>
                    @endforeach
                </select>
                @error('delegation_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categorie_id"><i class="fas fa-tags"></i>Catégorie</label>
                <i class="fas fa-tags input-icon"></i>
                <select id="categorie_id" name="categorie_id" class="form-input @error('categorie_id') input-error @enderror" required>
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $appartement->categorie_id) == $categorie->id ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="type_transaction_id"><i class="fas fa-exchange-alt"></i>Type de transaction</label>
                <i class="fas fa-exchange-alt input-icon"></i>
                <select id="type_transaction_id" name="type_transaction_id" class="form-input @error('type_transaction_id') input-error @enderror" required>
                    <option value="">Sélectionnez un type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('type_transaction_id', $appartement->type_transaction_id) == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
                    @endforeach
                </select>
                @error('type_transaction_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse"><i class="fas fa-home"></i>Adresse</label>
                <i class="fas fa-home input-icon"></i>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $appartement->adresse) }}" class="form-input @error('adresse') input-error @enderror" required>
                @error('adresse')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-file-alt"></i>Description</label>
                <i class="fas fa-file-alt input-icon"></i>
                <textarea id="description" name="description" class="form-input @error('description') input-error @enderror" rows="5" required>{{ old('description', $appartement->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="environnements_app"><i class="fas fa-leaf"></i>Environnements</label>
                <i class="fas fa-leaf input-icon"></i>
                <select id="environnements_app" name="environnements_app[]" class="form-input @error('environnements_app') input-error @enderror" multiple>
                    @foreach($environnements as $environnement)
                        <option value="{{ $environnement->id }}" {{ in_array($environnement->id, old('environnements_app', $appartement->environnementsApp->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $environnement->nom }}</option>
                    @endforeach
                </select>
                @error('environnements_app')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="images"><i class="fas fa-image"></i>Images (remplace les images existantes)</label>
                <i class="fas fa-image input-icon"></i>
                <input type="file" id="images" name="images[]" class="form-input @error('images') input-error @enderror" multiple accept="image/jpeg,image/png,image/jpg">
                @error('images')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                @if($appartement->images)
                    <div style="margin-top: 1rem;">
                        <p>Images actuelles :</p>
                        @foreach($appartement->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Appartement" style="width: 100px; height: 100px; object-fit: cover; margin-right: 10px;">
                        @endforeach
                    </div>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Mettre à jour
            </button>
        </form>
    </div>
</body>
</html>
@endsection