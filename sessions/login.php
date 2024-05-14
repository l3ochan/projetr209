<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1 class="page-title">Connexion</h1>
        <div class="separator"></div>
        <form method="post" action="login.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Se Connecter</button>
            <div class="noaccount">Pas de compte ? <a href="https://projetr209.nekocorp.fr/index.php?page=signin">S'inscire</a></div>
        </form>
    </div>
</body>
</html>
