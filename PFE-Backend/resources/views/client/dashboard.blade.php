<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Client Dashboard</h1>
        <p>Welcome, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</p>
        <p>Your account status is: {{ Auth::user()->account_status }}</p>
        <p>Your company name is: {{ Auth::user()->company_name }}</p>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
