<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - Connexion</title>
    <link rel="stylesheet" href="../style_sessions.css">
</head>
<body>
    <div class="login-container">
        <h1 class="page-title">Connexion</h1>
        <div class="separator"></div>
        <form method="post" action=''>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="submit">
            <button type="submit" name="login">Se Connecter</button>
            </div>
            <div class="noaccount">Pas de compte ? <a href="https://projetr209.nekocorp.fr/index.php?page=signin">S'inscire</a></div>
        </form>
    </div>

    <?php
        session_start();
        // Afficher le message d'inscription réussie s'il existe
        if (isset($_SESSION['success_message'])) {
            echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']); // Effacer le message de la session
        }

        // Afficher le message d'erreur s'il existe
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']); // Effacer le message de la session
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Inclure le fichier de connexion à la base de données
            include '../config/db_connector.php';
        
            // Récupérer les informations du formulaire
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
        
            // Requête SQL pour vérifier les informations de connexion
            $query = "SELECT id, fname, lname FROM users WHERE email = ? AND password = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $email, $password);
        
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows == 1) {
                // Utilisateur authentifié
                $user = $result->fetch_assoc();
        
                // Génération d'un token aléatoire
                $token = generateRandomToken();
        
                // Mise à jour du token dans la base de données
                $updateQuery = "UPDATE users SET token = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('si', $token, $user['id']);
                $updateStmt->execute();
        
                // Stocker le token en session
                $_SESSION['token'] = $token;
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
        
                // Redirection vers la page d'accueil ou une autre page sécurisée
                header('Location: index.php?page=home');
                exit();
            } else {
                // Identifiants incorrects, afficher un message d'erreur
                echo "<p class=\"error-message\">Nom d'utilisateur ou mot de passe incorrect.</p>";
            }
        }
        
        // Fonction de génération de token aléatoire
        function generateRandomToken() {
            return bin2hex(random_bytes(16)); // Génère un token hexadécimal de 16 octets
        }
    ?>        
</body>
</html>
