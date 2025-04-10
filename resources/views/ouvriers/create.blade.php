<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un nouvel ouvrier</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-top: 50px;
            border: none;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Créer un nouvel ouvrier') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('ouvriers.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="nom" class="col-md-4 col-form-label text-md-right">{{ __('Nom') }}</label>

                                <div class="col-md-6">
                                    <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required autocomplete="nom" autofocus>

                                    @error('nom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prenom" class="col-md-4 col-form-label text-md-right">{{ __('Prénom') }}</label>

                                <div class="col-md-6">
                                    <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom') }}" required autocomplete="prenom">

                                    @error('prenom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adresse Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ville" class="col-md-4 col-form-label text-md-right">{{ __('Ville') }}</label>

                                <div class="col-md-6">
                                    <input id="ville" type="text" class="form-control @error('ville') is-invalid @enderror" name="ville" value="{{ old('ville') }}" required autocomplete="ville">

                                    @error('ville')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="adresse" class="col-md-4 col-form-label text-md-right">{{ __('Adresse') }}</label>

                                <div class="col-md-6">
                                    <input id="adresse" type="text" class="form-control @error('adresse') is-invalid @enderror" name="adresse" value="{{ old('adresse') }}" required autocomplete="adresse">

                                    @error('adresse')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Mot de passe') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="confirmationMotDePasse" class="col-md-4 col-form-label text-md-right">{{ __('Confirmer le mot de passe') }}</label>

                                <div class="col-md-6">
                                    <input id="confirmationMotDePasse" type="password" class="form-control" name="confirmationMotDePasse" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="profession" class="col-md-4 col-form-label text-md-right">{{ __('Profession') }}</label>

                                <div class="col-md-6">
                                    <input id="profession" type="text" class="form-control @error('profession') is-invalid @enderror" name="profession" value="{{ old('profession') }}" required autocomplete="profession">

                                    @error('profession')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="heureDebut" class="col-md-4 col-form-label text-md-right">{{ __('Heure de début') }}</label>

                                <div class="col-md-6">
                                    <input id="heureDebut" type="text" class="form-control @error('heureDebut') is-invalid @enderror" name="heureDebut" value="{{ old('heureDebut') }}" required autocomplete="heureDebut">

                                    @error('heureDebut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="heureFin" class="col-md-4 col-form-label text-md-right">{{ __('Heure de fin') }}</label>

                                <div class="col-md-6">
                                    <input id="heureFin" type="text" class="form-control @error('heureFin') is-invalid @enderror" name="heureFin" value="{{ old('heureFin') }}" required autocomplete="heureFin">

                                    @error('heureFin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="numeroTelephone" class="col-md-4 col-form-label text-md-right">{{ __('Numéro de téléphone') }}</label>

                                <div class="col-md-6">
                                    <input id="numeroTelephone" type="text" class="form-control @error('numeroTelephone') is-invalid @enderror" name="numeroTelephone" value="{{ old('numeroTelephone') }}" required autocomplete="numeroTelephone">

                                    @error('numeroTelephone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>

                                <div class="col-md-6">
                                    <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">

                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Enregistrer') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
