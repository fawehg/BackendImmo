@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Maison | Tableau de Bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Select2 CSS from jsDelivr -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            display: flex;
            align-items: center;
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

        /* Style pour Select2 */
        .select2-container--default .select2-selection--single {
            border: 1px solid #E2E8F0;
            border-radius: var(--border-radius-input);
            background-color: #F8FAFC;
            padding: 0.5rem;
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.6;
            padding-left: 2rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
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
            <h1>Modifier la Maison</h1>
            <p>Mettez à jour les informations de la maison</p>
        </div>

        <form action="{{ route('maisons.update', $maison->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="titre"><i class="fas fa-home"></i>Titre</label>
                <i class="fas fa-home input-icon"></i>
                <input type="text" id="titre" name="titre" value="{{ old('titre', $maison->titre) }}" class="form-input @error('titre') input-error @enderror" required>
                @error('titre')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prix"><i class="fas fa-money-bill"></i>Prix</label>
                <i class="fas fa-money-bill input-icon"></i>
                <input type="number" id="prix" name="prix" value="{{ old('prix', $maison->prix) }}" class="form-input @error('prix') input-error @enderror" required>
                @error('prix')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="superficie"><i class="fas fa-ruler"></i>Superficie (m²)</label>
                <i class="fas fa-ruler input-icon"></i>
                <input type="number" id="superficie" name="superficie" value="{{ old('superficie', $maison->superficie) }}" class="form-input @error('superficie') input-error @enderror" required>
                @error('superficie')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nombre_chambres"><i class="fas fa-bed"></i>Nombre de chambres</label>
                <i class="fas fa-bed input-icon"></i>
                <input type="number" id="nombre_chambres" name="nombre_chambres" value="{{ old('nombre_chambres', $maison->nombre_chambres) }}" class="form-input @error('nombre_chambres') input-error @enderror">
                @error('nombre_chambres')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nombre_pieces"><i class="fas fa-door-open"></i>Nombre de pièces</label>
                <i class="fas fa-door-open input-icon"></i>
                <input type="number" id="nombre_pieces" name="nombre_pieces" value="{{ old('nombre_pieces', $maison->nombre_pieces) }}" class="form-input @error('nombre_pieces') input-error @enderror">
                @error('nombre_pieces')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="annee_construction"><i class="fas fa-calendar-alt"></i>Année de construction</label>
                <i class="fas fa-calendar-alt input-icon"></i>
                <input type="number" id="annee_construction" name="annee_construction" value="{{ old('annee_construction', $maison->annee_construction) }}" class="form-input @error('annee_construction') input-error @enderror">
                @error('annee_construction')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="meuble"><i class="fas fa-couch"></i>Meublé</label>
                <i class="fas fa-couch input-icon"></i>
                <select id="meuble" name="meuble" class="form-input @error('meuble') input-error @enderror">
                    <option value="1" {{ old('meuble', $maison->meuble) == 1 ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('meuble', $maison->meuble) == 0 ? 'selected' : '' }}>Non</option>
                </select>
                @error('meuble')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="type_transaction_id"><i class="fas fa-exchange-alt"></i>Type de transaction</label>
                <i class="fas fa-exchange-alt input-icon"></i>
                <select id="type_transaction_id" name="type_transaction_id" class="form-input @error('type_transaction_id') input-error @enderror" required>
                    <option value="">Sélectionnez un type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('type_transaction_id', $maison->type_transaction_id) == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
                    @endforeach
                </select>
                @error('type_transaction_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categorie_id"><i class="fas fa-tags"></i>Catégorie</label>
                <i class="fas fa-tags input-icon"></i>
                <select id="categorie_id" name="categorie_id" class="form-input @error('categorie_id') input-error @enderror" required>
                    <option value="">Sélectionnez une catégorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $maison->categorie_id) == $categorie->id ? 'selected' : '' }}>{{ $categorie->nom }}</option>
                    @endforeach
                </select>
                @error('categorie_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ville_id"><i class="fas fa-map-marker-alt"></i>Ville</label>
                <i class="fas fa-map-marker-alt input-icon"></i>
                <select id="ville_id" name="ville_id" class="form-input @error('ville_id') input-error @enderror" required>
                    <option value="">Sélectionnez une ville</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville->id }}" {{ old('ville_id', $maison->ville_id) == $ville->id ? 'selected' : '' }}>{{ $ville->nom }}</option>
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
                        <option value="{{ $delegation->id }}" {{ old('delegation_id', $maison->delegation_id) == $delegation->id ? 'selected' : '' }}>{{ $delegation->nom }}</option>
                    @endforeach
                </select>
                @error('delegation_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="environnement_id"><i class="fas fa-leaf"></i>Environnement</label>
                <i class="fas fa-leaf input-icon"></i>
                <select id="environnement_id" name="environnement_id" class="form-input @error('environnement_id') input-error @enderror">
                    <option value="">Sélectionnez un environnement</option>
                    @foreach($environnements as $environnement)
                        <option value="{{ $environnement->id }}" {{ old('environnement_id', $maison->environnement_id) == $environnement->id ? 'selected' : '' }}>{{ $environnement->nom }}</option>
                    @endforeach
                </select>
                @error('environnement_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="adresse"><i class="fas fa-home"></i>Adresse</label>
                <i class="fas fa-home input-icon"></i>
                <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $maison->adresse) }}" class="form-input @error('adresse') input-error @enderror" required>
                @error('adresse')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-file-alt"></i>Description</label>
                <i class="fas fa-file-alt input-icon"></i>
                <textarea id="description" name="description" class="form-input @error('description') input-error @enderror" rows="5" required>{{ old('description', $maison->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="images"><i class="fas fa-image"></i>Nouvelles Images</label>
                <i class="fas fa-image input-icon"></i>
                <input type="file" id="images" name="images[]" class="form-input @error('images') input-error @enderror" multiple accept="image/jpeg,image/png,image/jpg">
                <p class="password-note">Sélectionnez de nouvelles images pour les ajouter. Les images existantes seront conservées.</p>
                @error('images')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            @if($maison->images && count($maison->images) > 0)
                <div class="form-group">
                    <label><i class="fas fa-images"></i>Images Existantes</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($maison->images as $image)
                            <img src="{{ Storage::url($image) }}" alt="Image de la maison" style="max-width: 100px; border-radius: 8px;">
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Mettre à jour
            </button>
        </form>
    </div>

    <!-- JavaScript pour Select2 et AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialiser Select2 pour les sélecteurs
            $('#type_transaction_id, #categorie_id, #ville_id, #delegation_id, #environnement_id').select2({
                placeholder: function() {
                    return $(this).find('option:first').text();
                },
                allowClear: true,
                width: '100%'
            });

            // Événement de changement sur le sélecteur de ville
            $('#ville_id').on('change', function() {
                var villeId = $(this).val();
                var delegationSelect = $('#delegation_id');

                // Vider le sélecteur de délégations
                delegationSelect.html('<option value="">Sélectionnez une délégation</option>');

                if (villeId) {
                    // Requête AJAX pour récupérer les délégations
                    $.ajax({
                        url: '{{ route("delegations.by.ville") }}',
                        type: 'POST',
                        data: {
                            ville_id: villeId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            // Remplir le sélecteur de délégations
                            $.each(data, function(index, delegation) {
                                delegationSelect.append(
                                    $('<option>', {
                                        value: delegation.id,
                                        text: delegation.nom,
                                        selected: delegation.id == '{{ old("delegation_id", $maison->delegation_id) }}'
                                    })
                                );
                            });
                            // Rafraîchir Select2
                            delegationSelect.trigger('change');
                        },
                        error: function(xhr) {
                            console.error('Erreur AJAX:', xhr);
                            alert('Impossible de charger les délégations. Veuillez réessayer.');
                        }
                    });
                }
            });

            // Déclencher le changement initial pour charger les délégations
            if ($('#ville_id').val()) {
                $('#ville_id').trigger('change');
            }
        });
    </script>
</body>
</html>
@endsection
