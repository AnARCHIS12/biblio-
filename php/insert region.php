<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input, select, textarea, button { width: 100%; padding: 8px; margin: 5px 0; }
        button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>

    <form action="#" method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="email" name="email" placeholder="Email" required>

        <button type="submit">Envoyer</button>
    </form>

</body>
</html>
