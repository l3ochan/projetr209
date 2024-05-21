<?php
session_start();
include '../config/db_connector.php';

// Vérifier si un utilisateur est connecté
if (!isset($_SESSION['token'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php?page=login");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté depuis la base de données
$token = $_SESSION['token'];
$query = "SELECT id FROM users WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $userID = $row['id'];
} else {
    // Afficher un message d'erreur 403 (interdit) si l'utilisateur n'est pas trouvé dans la base de données
    http_response_code(403);
    echo "<h1>403 Forbidden</h1>";
    echo "<p>Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>";
    exit(); // Arrêter l'exécution du script
}

// Vérifier si un ID d'article à supprimer est passé en paramètre
if (isset($_GET['id'])) {
    // Récupérer l'ID de l'article à supprimer
    $itemID = $_GET['id'];

    // Vérifier que l'utilisateur est bien le propriétaire de l'article
    $query = "SELECT ownerID FROM items WHERE id = ? AND ownerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $itemID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // L'utilisateur est bien le propriétaire, supprimer l'article
        $query = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $itemID);
        $stmt->execute();
        // Supprimer le répertoire d'images de l'annonce
        $directoryToDelete = "data/ads_imgs/{$itemID}/";
        if (is_dir($directoryToDelete)) {
            // Supprimer le répertoire et son contenu
            $files = glob($directoryToDelete . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            // Supprimer le répertoire lui-même
            rmdir($directoryToDelete);
}
        // Rediriger vers la page actuelle pour rafraîchir la liste des articles après la suppression
        header("Location: index.php?page=myvehicles");
        exit();
    } else {
        // Afficher un message d'erreur 403 (interdit) si l'utilisateur n'est pas le propriétaire de l'article
        http_response_code(403);
        echo "<h1>403 Forbidden</h1>";
        echo "<p>Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>";
        exit(); // Arrêter l'exécution du script
    }
}

// Récupérer la liste des véhicules de l'utilisateur
$query = "SELECT * FROM items WHERE ownerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Afficher un message si aucun véhicule n'est associé à l'utilisateur
    echo "<p>Aucun véhicule trouvé.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de vos véhicules</title>
    <link rel="stylesheet" href="style_itemlist-sorted.css">
</head>
<body>
    <h1 class="page-title">AutoIUT - Vos annonces</h1>
    <div class="separator"></div>
    <table>
        <thead>
            <tr>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Date de création</th>
                <th>Identifiant (ID)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['make']) ?></td>
                    <td><?= htmlspecialchars($row['model']) ?></td>
                    <td><?= htmlspecialchars($row['date_of_creation']) ?></td>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td>
                        <a href="https://projetr209.nekocorp.fr/index.php?page=item-details&id=<?= $row['id'] ?>" target="_blank">Détails</a>
                        <a href="index.php?page=edit-item&id=<?= $row['id'] ?>">Modifier</a>
                        <a href="index.php?page=myvehicles&id=<?= $row['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
