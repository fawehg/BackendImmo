@extends('layouts.app')


@section('contents')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Liste des demandes</h1>
    <a href="{{ route('demandes.create') }}" class="btn btn-primary">Ajouter une demande</a>
</div>
<hr />

<table class="table table-hover">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Domaines</th>
            <th>Spécialités</th>
            <th>Ville</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if($demandes->count() > 0)
            @foreach($demandes as $demande)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $demande->domaines }}</td>
                    <td>{{ $demande->specialites }}</td>
                    <td>{{ $demande->city }}</td>
                    <td>{{ $demande->date }}</td>
                    <td>{{ $demande->time }}</td>
                    <td>{{ $demande->description }}</td>
                    <td>
                        @if($demande->image)
                            <img src="{{ asset($demande->image) }}" alt="Image de la demande" style="max-width: 100px;">
                        @else
                            Aucune image
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">
                            <a href="{{ route('demandes.show', $demande->id) }}" class="btn btn-secondary">Détails</a>
                            <a href="{{ route('demandes.edit', $demande->id) }}" class="btn btn-warning">Modifier</a>
                            <form action="{{ route('demandes.destroy', $demande->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" class="text-center">Aucune demande trouvée</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection
