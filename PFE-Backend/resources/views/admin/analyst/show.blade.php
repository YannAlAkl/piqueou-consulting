<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Détails de l'Analyste</title>
     <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <h1>Détails de l'Analyste</h1>

    <p>Prénom : {{ $analyst->first_name }}</p>
    <p>Nom : {{ $analyst->last_name }}</p>
    <p>Email : {{ $analyst->email }}</p>
    <p>Téléphone : {{ $analyst->phone ?? 'Non renseigné' }}</p>
    <p>Statut : {{ $analyst->account_status }}</p>
    <p>Inscrit le : {{ $analyst->created_at->format('d/m/Y H:i') }}</p>

    <a href="/admin/analysts/{{ $analyst->id }}/edit">Modifier</a>
    <a href="/admin/analysts/gestion">Retour à la liste</a>
</body>
</html>
