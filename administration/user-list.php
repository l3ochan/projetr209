<?php
session_start();
include 'config/db_connector.php';

// Vérifier si un utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['token'])) {
        // Afficher un message d'erreur 403 (interdit)
        http_response_code(403);
        echo "<h1>403 Forbidden</h1>";
        echo "<p>Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>";
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
        echo "<p>Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>";
        exit(); // Arrêter l'exécution du script
}

// Vérifier si un ID d'utilisateur à supprimer est passé en paramètre
if (isset($_GET['id'])) {
    // Récupérer l'ID de l'utilisateur à supprimer
    $userID = $_GET['id'];

    // Préparer et exécuter la requête de suppression de l'utilisateur
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userID);
    $stmt->execute();

    // Rediriger vers la page actuelle pour rafraîchir la liste des utilisateurs après la suppression
    header("Location: index-admin.php?page=user-list-admin");
    exit();
}

// Récupérer la liste des utilisateurs
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Erreur de requête : " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[⚙️] AutoIUT - Tout les utilisateurs</title>
    <link rel="stylesheet" href="style_administration.css">
    <link rel="stylesheet" href="style_sidewide.css">
    <link rel="stylesheet" href="../style_itemlist-sorted.css">
</head>
<body>
    <h1 class="page-title">Liste des utilisateurs</h1>
    <div class="separator"></div>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Date de création du compte</th>
                <th>Identifiant (ID)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['lname']) ?></td>
                    <td><?= htmlspecialchars($row['fname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['role'] == 1 ? 'Utilisateur' : ($row['role'] == 3 ? 'Administrateur' : 'Inconnu') ?></td>
                    <td><?= htmlspecialchars($row['date_of_creation']) ?></td>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td>
                        <a href="index-admin.php?page=edit-user&id=<?= $row['id'] ?>">Modifier</a>
                        <a href="index-admin.php?page=item-list-sorted&userid=<?= $row['id'] ?>">Annonces</a>
                        <a href="index-admin.php?page=user-list-admin&id=<?= $row['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
