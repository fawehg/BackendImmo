@extends('layouts.app')


@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="mb-0">Liste des clients</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">Ajouter un client</a>
    </div>
    <hr />
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <table class="table table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Ville</th>
                <th>Adresse</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($clients->count() > 0)
                @foreach($clients as $client)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $client->nom }}</td>
                        <td class="align-middle">{{ $client->prenom }}</td>
                        <td class="align-middle">{{ $client->ville }}</td>
                        <td class="align-middle">{{ $client->adresse }}</td>
                        <td class="align-middle">{{ $client->email }}</td>  
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('clients.show', $client->id) }}" type="button" class="btn btn-secondary">Détail</a>
                                <a href="{{ route('clients.edit', $client->id)}}" type="button" class="btn btn-warning">Modifier</a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="btn btn-danger p-0" onsubmit="return confirm('Supprimer ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger m-0">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="7">Aucun client trouvé</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
