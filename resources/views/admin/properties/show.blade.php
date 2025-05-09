<!DOCTYPE html>
<html>
<head>
    <title>Détails de l'Annonce</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Détails de l'Annonce</h1>

        <h2>{{ $property->titre }}</h2>
        <p><strong>Vendeur:</strong> {{ $property->vendeur ? $property->vendeur->nom . ' ' . $property->vendeur->prenom : 'Vendeur inconnu' }}</p>
        <p><strong>Type:</strong> {{ $type }}</p>
        <p><strong>Description:</strong> {{ $property->description }}</p>
        <p><strong>Prix:</strong> {{ number_format($property->prix, 0, ',', ' ') }} DT</p>
        <p><strong>Superficie:</strong> {{ $property->superficie }} m²</p>

        <!-- Add more property details as needed -->

        <h3>Images</h3>
        @if ($property->images)
            @foreach (json_decode($property->images) as $image)
                <img src="{{ Storage::url($image) }}" alt="Property Image" style="max-width: 200px;">
            @endforeach
        @else
            <p>Aucune image disponible.</p>
        @endif

        <h3>Actions</h3>
        <form action="{{ route('admin.properties.update', ['type' => $type, 'id' => $property->id]) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="approved">Approuver</option>
                    <option value="rejected">Rejeter</option>
                </select>
            </div>
            <div class="form-group">
                <label for="rejection_reason">Raison du rejet (si applicable)</label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>

        <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary mt-3">Retour</a>
    </div>
</body>
</html>