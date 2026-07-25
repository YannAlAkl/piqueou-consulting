<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'utilisateur</title>
</head>
<body>

    <h1>Modifier l'utilisateur</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/admin/users/{{ $user->id }}">
        @csrf
        @method('PUT')

        <div>
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required>
        </div>
        <br>

        <div>
            <label for="last_name">Nom</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
        </div>
        <br>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <br>

        <div>
            <label for="phone">Téléphone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
        </div>
        <br>

        <div>
            <label for="company_name">Entreprise</label>
            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $user->company_name) }}">
        </div>
        <br>

        <div>
            <button type="submit">Mettre à jour</button>
            <a href="javascript:history.back()">Annuler</a>
        </div>
    </form>

</body>
</html>
