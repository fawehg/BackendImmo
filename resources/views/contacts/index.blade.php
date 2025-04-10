@extends('layouts.app')

@section('contents')
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Mettez vos balises meta, liens CSS, etc. ici -->
</head>
<body>
    <!-- Contenu de la page -->
    <div class="container">
        <h1>Liste des contacts</h1>
        <hr>

        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->description }}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">
                            <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-secondary">Détails</a>
                            <!-- Ajoutez d'autres actions si nécessaire, comme la modification ou la suppression -->
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Aucun contact trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
@endsection
