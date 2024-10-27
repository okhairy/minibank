<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1C627B;
            color: white;
        }
        td {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f1f5f9;
        }
        h2 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Liste des utilisateurs</h2>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>CIN</th>
                <th>Email</th>
                <th>Date de Naissance</th>
                <th>Adresse</th>
                <th>Rôle</th>
                <th>Photo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->nom }}</td>
                <td>{{ $user->prenom }}</td>
                <td>{{ $user->telephone }}</td>
                <td>{{ $user->cin }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->date_naissance }}</td>
                <td>{{ $user->adresse }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de {{ $user->nom }}" width="50">
                    @else
                        <img src="{{ asset('storage/default-avatar.png') }}" alt="Photo de {{ $user->nom }}" width="50">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
