<?php
session_start();
include '../config/db_connector.php';

// Vérifier si un utilisateur est connecté
if (!isset($_SESSION['token'])) {
    // Afficher un message d'erreur 403 (interdit)
    http_response_code(403);
    echo "<h1>403 Forbidden</h1>";
    echo "<p>Accès interdit. Veuillez vous connecter en tant qu'administrateur pour accéder à cette page.</p>";
    exit(); // Arrêter l'exécution du script
}

// Récupérer le rôle de l'utilisateur connecté depuis la base de données
$token = $_SESSION['token'];
$query = "SELECT role FROM users WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
    // Vérifier si l'utilisateur a le rôle d'administrateur
    if ($role != 3) {
        // Afficher un message d'erreur 403 (interdit)
        http_response_code(403);
        echo "<h1>403 Forbidden</h1>";
        echo "<p>Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>";
        exit(); // Arrêter l'exécution du script
    }
} else {
    // Afficher un message d'erreur 403 (interdit)
    http_response_code(403);
    echo "<h1>403 Forbidden</h1>";
    echo "<p>Accès interdit. Veuillez vous connecter en tant qu'administrateur pour accéder à cette page.</p>";
    exit(); // Arrêter l'exécution du script
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    
    <link rel="stylesheet" href="style_administration.css">
    <link rel="stylesheet" href="style_sidewide">
    <link rel="icon" href="../assets/imgs/favicon.png" type="image/png">
</head>
<body class="admin-menu">
    <header>
        <?php
        include 'navbar-admin.php';
        include '../config/db_connector.php';

        $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

        if ($page === 'home') :
            include('homepage-admin.php'); 
        elseif ($page === 'item-list-admin') :
            include('item-list-admin.php');
        elseif ($page === 'user-list-admin') : 
            include('user-list.php');  
        elseif ($page === 'edit-item') : 
            include('edit-item.php');  
        elseif ($page === 'edit-user') : 
            include('edit-user.php'); 
        elseif ($page === 'item-list-sorted') : 
            include('item-list-sorted.php');
        else : ?>
            <div class="error404">
                <link rel="stylesheet" href="../style404.css">
                <img src="../assets/imgs/404.png" class="error-picture" alt="404 error picture">
                <h1>404 - Page Not Found</h1><br>
                <?php header("HTTP/1.0 404 Not Found"); ?>
            </div>
        <?php endif; ?>
    </header>
</body>
</html>
