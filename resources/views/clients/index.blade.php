@extends('layouts.admin')

@section('title', 'Gestion des clients')

@section('header', 'Gestion des clients')

@section('content')
<div class="mb-4">
    <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Nouveau client
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-4">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b text-left">Photo</th>
                    <th class="px-6 py-3 border-b text-left">Nom</th>
                    <th class="px-6 py-3 border-b text-left">Prénom</th>
                    <th class="px-6 py-3 border-b text-left">Téléphone</th>
                    <th class="px-6 py-3 border-b text-left">Statut</th>
                    <th class="px-6 py-3 border-b text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $client->photo) }}" alt="Photo" class="w-10 h-10 rounded-full">
                    </td>
                    <td class="px-6 py-4">{{ $client->nom }}</td>
                    <td class="px-6 py-4">{{ $client->prenom }}</td>
                    <td class="px-6 py-4">{{ $client->telephone }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-sm {{ $client->status === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $client->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 hover:text-blue-800">Modifier</a>
                            <form action="{{ route('clients.toggle-status', $client) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-{{ $client->status === 'actif' ? 'red' : 'green' }}-600 hover:text-{{ $client->status === 'actif' ? 'red' : 'green' }}-800">
                                    {{ $client->status === 'actif' ? 'Bloquer' : 'Débloquer' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">
        {{ $clients->links() }}
    </div>
</div>
@endsection
