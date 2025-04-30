@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Terrain | Tableau de Bord</title>
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
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background-color: var(--couleur-carte);
            border-radius: var(--border-radius);
            box-shadow: var(--ombre-legere);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--couleur-primaire);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--couleur-primaire);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #E2E8F0;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition-fluide);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--couleur-accent);
            box-shadow: 0 0 0 3px rgba(107, 143, 212, 0.1);
        }

        .form-control.is-invalid {
            border-color: #F56565;
        }

        .invalid-feedback {
            color: #F56565;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition-fluide);
            border: none;
            text-decoration: none;
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

        .btn-secondary {
            background-color: #E9ECEF;
            color: var(--couleur-texte);
        }

        .btn-secondary:hover {
            background-color: #DEE2E6;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            font-weight: 400;
            margin-bottom: 0;
        }

        .checkbox-group input {
            margin-right: 0.5rem;
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-preview img {
            max-width: 100px;
            border-radius: var(--border-radius);
            box-shadow: var(--ombre-legere);
        }

        @media (max-width: 768px) {
            .container {
                margin: 1.5rem;
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const villeSelect = document.getElementById('ville_id');
            const delegationSelect = document.getElementById('delegation_id');

            function loadDelegations(villeId, selectedDelegationId = null) {
                delegationSelect.innerHTML = '<option value="">Sélectionnez une délégation</option>';
                if (villeId) {
                    fetch(`/api/delegations/${villeId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(delegation => {
                                const option = document.createElement('option');
                                option.value = delegation.id;
                                option.textContent = delegation.nom;
                                if (delegation.id == selectedDelegationId) {
                                    option.selected = true;
                                }
                                delegationSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Erreur:', error));
                }
            }

            villeSelect.addEventListener('change', function () {
                loadDelegations(this.value);
            });

            // Load delegations for the current ville
            loadDelegations({{ $terrain->ville_id }}, {{ $terrain->delegation_id }});
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h1>Modifier un Terrain</h1>
        </div>

        <form action="{{ route('terrains.update', $terrain->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre', $terrain->titre) }}">
                @error('titre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $terrain->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prix">Prix (TND)</label>
                <input type="number" name="prix" id="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix', $terrain->prix) }}" step="0.01">
                @error('prix')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="superficie">Superficie (m²)</label>
                <input type="number" name="superficie" id="superficie" class="form-control @error('superficie') is-invalid @enderror" value="{{ old('superficie', $terrain->superficie) }}" step="0.01">
                @error('superficie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="surface_constructible">Surface constructible (m²)</label>
                <input type="number" name="surface_constructible" id="surface_constructible" class="form-control @error('surface_constructible') is-invalid @enderror" value="{{ old('surface_constructible', $terrain->surface_constructible) }}" step="0.01">
                @error('surface_constructible')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="permis_construction">Permis de construction</label>
                <input type="checkbox" name="permis_construction" id="permis_construction" {{ old('permis_construction', $terrain->permis_construction) ? 'checked' : '' }}>
                @error('permis_construction')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="cloture">Clôture</label>
                <input type="checkbox" name="cloture" id="cloture" {{ old('cloture', $terrain->cloture) ? 'checked' : '' }}>
                @error('cloture')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse', $terrain->adresse) }}">
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="type_id">Type de transaction</label>
                <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror">
                    <option value="">Sélectionnez un type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('type_id', $terrain->type_id) == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
                    @endforeach
                </select>
                @error('type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categorie_id">Catégorie</label>
                <select name="categorie_id" id="categorie_id" class="form-control @error('categorie_id') is-invalid @enderror">
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $terrain->categorie_id) == $categorie->id ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ville_id">Ville</label>
                <select name="ville_id" id="ville_id" class="form-control @error('ville_id') is-invalid @enderror">
                    <option value="">Sélectionnez une ville</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville->id }}" {{ old('ville_id', $terrain->ville_id) == $ville->id ? 'selected' : '' }}>{{ $ville->nom }}</option>
                    @endforeach
                </select>
                @error('ville_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="delegation_id">Délégation</label>
                <select name="delegation_id" id="delegation_id" class="form-control @error('delegation_id') is-invalid @enderror">
                    <option value="">Sélectionnez une délégation</option>
                </select>
                @error('delegation_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="types_terrains_id">Type de terrain</label>
                <select name="types_terrains_id" id="types_terrains_id" class="form-control @error('types_terrains_id') is-invalid @enderror">
                    <option value="">Sélectionnez un type de terrain</option>
                    @foreach($types_terrains as $type_terrain)
                        <option value="{{ $type_terrain->id }}" {{ old('types_terrains_id', $terrain->types_terrains_id) == $type_terrain->id ? 'selected' : '' }}>{{ $type_terrain->nom }}</option>
                    @endforeach
                </select>
                @error('types_terrains_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="types_sols_id">Type de sol</label>
                <select name="types_sols_id" id="types_sols_id" class="form-control @error('types_sols_id') is-invalid @enderror">
                    <option value="">Sélectionnez un type de sol</option>
                    @foreach($types_sols as $type_sol)
                        <option value="{{ $type_sol->id }}" {{ old('types_sols_id', $terrain->types_sols_id) == $type_sol->id ? 'selected' : '' }}>{{ $type_sol->nom }}</option>
                    @endforeach
                </select>
                @error('types_sols_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="images">Images supplémentaires</label>
                <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" multiple>
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(is_array($terrain->images) && count($terrain->images) > 0)
                    <div class="image-preview">
                        @foreach($terrain->images as $image)
                            <img src="{{ Storage::url($image) }}" alt="Image du terrain">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('terrains') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</body>
</html>
@endsection
