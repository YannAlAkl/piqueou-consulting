<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
<title>Modifier Analyste</title>
</head>
<body>
    <h1>Modifier Analyste</h1>

    <form method="POST" action="/admin/analysts/{{ $analyst->id }}">
        @csrf
        @method('PUT')

        <label for="first_name">Prénom</label>
        <input type="text" name="first_name" id="first_name" value="{{ $analyst->first_name }}">

        <label for="last_name">Nom</label>
        <input type="text" name="last_name" id="last_name" value="{{ $analyst->last_name }}">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ $analyst->email }}">

        <label for="phone">Téléphone (optionnel)</label>
        <input type="text" name="phone" id="phone" value="{{ $analyst->phone }}">

        <label for="account_status">Statut</label>
        <select name="account_status" id="account_status">
            <option value="active" {{ $analyst->account_status === 'active' ? 'selected' : '' }}>Actif</option>
            <option value="inactive" {{ $analyst->account_status === 'inactive' ? 'selected' : '' }}>Inactif</option>
            <option value="pending" {{ $analyst->account_status === 'pending' ? 'selected' : '' }}>En attente</option>
        </select>

        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
