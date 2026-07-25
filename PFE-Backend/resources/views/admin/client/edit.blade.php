<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Client</title>
</head>
<body>

    <h1>Modifier Client</h1>

    <form method="POST" action="/clients">

        <label for="first_name">Prénom</label>
        <input type="text" name="first_name" id="first_name">

        <label for="last_name">Nom</label>
        <input type="text" name="last_name" id="last_name">

        <label for="email">Email</label>
        <input type="email" name="email" id="email">

        <label for="phone">Téléphone (optionnel)</label>
        <input type="text" name="phone" id="phone">

        <label for="company_name">Entreprise</label>
        <input type="text" name="company_name" id="company_name">

        <label for="account_status">Statut</label>
        <select name="account_status" id="account_status">
            <option value="active">Actif</option>
            <option value="inactive">Inactif</option>
            <option value="pending">En attente</option>
        </select>

        <label for="wants_newsletter">
            <input type="checkbox" name="wants_newsletter" id="wants_newsletter" value="1">
            Souhaite recevoir la newsletter
        </label>

        <button type="submit">Mettre à jour</button>
        <button type="button">Annuler</button>


