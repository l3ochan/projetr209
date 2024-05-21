<?php
session_start();
include '../config/db_connector.php';

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

// Récupérer l'ID de l'utilisateur connecté
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
    // Afficher un message d'erreur 403 (interdit)
    http_response_code(403);
    echo "<h1>403 Forbidden</h1>";
    echo "<p>Accès interdit. Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>";
    exit(); // Arrêter l'exécution du script
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
if (isset($_GET['userid'])) {
    $userID = $_GET['userid'];
} else {
    // Afficher un message d'erreur 404 (introuvable) si aucun ID n'est passé dans l'URL
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>Page non trouvée. L'identifiant de l'utilisateur n'est pas spécifié dans l'URL.</p>";
    exit(); // Arrêter l'exécution du script
}
// Vérifier si le paramètre "id" est présent dans l'URL
if (isset($_GET['id'])) {
    // Récupérer l'ID de l'annonce à supprimer depuis l'URL
    $itemID = $_GET['id'];

    // Préparer et exécuter la requête de suppression de l'annonce
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

    // Rediriger vers la page actuelle pour rafraîchir la liste des annonces après la suppression
    header("Location: index-admin.php?page=item-list-sorted&userid=$userID");
    exit(); // Arrêter l'exécution du script après la redirection
}


// Récupérer le nom et le prénom de l'utilisateur à partir de son ID
$query = "SELECT fname, lname FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $userFullName = $row['fname'] . ' ' . $row['lname'];
} else {
    // Afficher un message d'erreur si l'utilisateur n'est pas trouvé
    $userFullName = "Utilisateur inconnu";
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
    <title>[⚙️] AutoIUT - Véhicules de <?= htmlspecialchars($userFullName) ?></title>
    <link rel="stylesheet" href="style_administration.css">
    <link rel="stylesheet" href="style_sidewide.css">
    <link rel="stylesheet" href="../style_itemlist-sorted.css">
</head>
<body>
    <h1 class="page-title">Liste des véhicules de <?= htmlspecialchars($userFullName) ?></h1>
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
                        <a href="index-admin.php?page=edit-item&id=<?= $row['id'] ?>">Modifier</a>
                        <a href="https://projetr209.nekocorp.fr/administration/index-admin.php?page=item-list-sorted&userid=<?= $userID ?>&id=<?= $row['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
