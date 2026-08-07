<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('titre')</title>
</head>
<body style="background-color:#f4f4f4; font-family:Arial, sans-serif; color:#333333; padding:20px;">

    <div style="max-width:600px; margin:0 auto; background-color:#ffffff; border-top:4px solid #1d4ed8; padding:25px;">

        <h2 style="color:#1d4ed8;">Piqueou Conseil</h2>

        @yield('contenu')

        <hr style="border:0; border-top:1px solid #eeeeee;">

        <p style="font-size:12px; color:#888888;">
            Email envoyé automatiquement par le portail Piqueou Conseil.
        </p>
    </div>

</body>
</html>
