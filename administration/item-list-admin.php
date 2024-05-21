<?php
session_start();
include 'config/db_connector.php';

// Vérifier si un utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['token'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit();
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
        // Rediriger vers la page d'accueil si l'utilisateur n'est pas administrateur
        header("Location: index.php");
        exit();
    }
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas trouvé dans la base de données
    header("Location: login.php");
    exit();
}

// Vérifier si un ID d'article à supprimer est passé en paramètre
if (isset($_GET['id'])) {
    // Récupérer l'ID de l'article à supprimer
    $itemID = $_GET['id'];

    // Préparer et exécuter la requête de suppression de l'article
    $query = "DELETE FROM items WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $itemID);
    $stmt->execute();

    // Supprimer le répertoire d'images de l'annonce
    $directoryToDelete = "../data/ads_imgs/{$itemID}/";
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
    header("Location: index-admin.php?page=item-list-admin");
    exit();
}
// Récupérer la liste des articles avec les informations sur les propriétaires
$query = "SELECT items.*, IFNULL(users.email, 'null') AS owner_email FROM items LEFT JOIN users ON items.ownerID = users.id";
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
    <title>[⚙️] AutoIUT - Tout les véhicules</title>
    <link rel="stylesheet" href="style_administration.css">
    <link rel="stylesheet" href="style_sidewide.css">
    <link rel="stylesheet" href="../style_itemlist-sorted.css">
</head>
<body>
    <h1 class="page-title">Liste des articles</h1>
    <div class="separator"></div>
    <table>
        <thead>
            <tr>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Propriétaire</th>
                <th>Date de création</th>
                <th>Identifiant (ID)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['make']) ?></td>
                    <td><?= htmlspecialchars($row['model']) ?></td>
                    <td><?= htmlspecialchars($row['owner_email']) ?></td>
                    <td><?= htmlspecialchars($row['date_of_creation']) ?></td>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td>
                        <a href="https://projetr209.nekocorp.fr/index.php?page=item-details&id=<?= $row['id'] ?>" target="_blank">Détails</a>
                        <a href="index-admin.php?page=edit-item&id=<?= $row['id'] ?>">Modifier</a>
                        <a href="index-admin.php?page=item-list-admin&id=<?= $row['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
