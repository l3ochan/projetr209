<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - Inscription</title>
    <link rel="stylesheet" href="../style_sessions.css">
</head>
<body>
    <div class="login-container">
        <h1 class="page-title">Inscription</h1>
        <div class="separator"></div>
        <form method="post" action="">
            <div class="input-group">
                <label for="lname">Prénom</label>
                <input type="text" id="lname" name="lname" required>
            </div>
            <div class="input-group">
                <label for="fname">Nom</label>
                <input type="text" id="fname" name="fname" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="submit">
            <button type="submit" name="signin">S'inscrire</button>
            </div>
        </form>
    </div>

    <?php
    // Démarrer la session
    session_start();

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Inclure le fichier de connexion à la base de données
        include 'config/db_connector.php';

        // Récupérer l'email du formulaire
        $email = htmlspecialchars($_POST['email']);

        // Vérifier si l'email existe déjà dans la base de données
        $check_query = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('s', $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // L'utilisateur avec cet email existe déjà
            echo "<p class=\"error-message\">Un utilisateur avec cet email existe déjà. Veuillez utiliser une autre adresse email.</p>";
        } else {
            // L'email est unique, procéder à l'inscription
            // Récupérer les autres valeurs du formulaire
            $lname = htmlspecialchars($_POST['lname']);
            $fname = htmlspecialchars($_POST['fname']);
            $password = htmlspecialchars($_POST['password']);

            // Définir le rôle par défaut
            $role = 1;

            // Préparer la requête SQL d'insertion avec des paramètres nommés
            $insert_query = "INSERT INTO users (lname, fname, email, password, role, date_of_creation) VALUES (?, ?, ?, ?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_query);

            // Liaison des paramètres
            $insert_stmt->bind_param('ssssi', $lname, $fname, $email, $password, $role);

            // Exécuter la requête d'insertion
            if ($insert_stmt->execute()) {
                // Succès de l'inscription
                $_SESSION['success_message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header('Location: index.php?page=login');
                exit();
            } else {
                // Erreur lors de l'inscription
                $_SESSION['error_message'] = "Erreur lors de l'inscription. Veuillez réessayer.";
                header('Location: index.php?page=signup');
                exit();
            }

            // Fermer le statement et la connexion
            $insert_stmt->close();
        }

        // Fermer la connexion
        $conn->close();
    }
    ?>
</body>
</html>
