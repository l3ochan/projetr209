<?php
session_start();
include '../config/db_connector.php';

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

// Vérifier si un ID d'utilisateur à modifier est passé en paramètre
if (!isset($_GET['id'])) {
    // Rediriger vers la page de liste des utilisateurs si aucun ID n'est spécifié
    header("Location: index-admin.php?page=user-list-admin");
    exit();
}

$userID = $_GET['id'];

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Préparer et exécuter la requête de mise à jour de l'utilisateur
    $query = "UPDATE users SET lname = ?, fname = ?, email = ?, password = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssii', $lname, $fname, $email, $password, $role, $userID);
    $stmt->execute();

    // Rediriger vers la page de liste des utilisateurs après la mise à jour
    header("Location: index-admin.php?page=user-list-admin");
    exit();
}

// Récupérer les données de l'utilisateur à modifier depuis la base de données
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si un utilisateur correspondant a été trouvé
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style_edituser.css">
    <link rel="stylesheet" href="style_sidewide.css">
    <title>[⚙️] AutoIUT - Modifier utilisateur</title>
</head>
<body>
    <h1 class="page-title">Modifier l'utilisateur</h1>
    <div class="separator"></div>
    <form method="post" action="index-admin.php?page=edit-user&id=<?= $userID ?>">
        <label for="lname">Nom:</label><br>
        <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($row['lname']) ?>" required><br><br>

        <label for="fname">Prénom:</label><br>
        <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($row['fname']) ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required><br><br>

        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" value="<?= htmlspecialchars($row['password']) ?>" required><br><br>

        <label for="role">Rôle:</label><br>
        <select id="role" name="role" required>
            <option value="1" <?php if ($row['role'] == 1) echo 'selected'; ?>>Utilisateur</option>
            <option value="3" <?php if ($row['role'] == 3) echo 'selected'; ?>>Administrateur</option>
        </select><br><br>

        <input type="submit" value="Enregistrer">
    </form>
</body>
</html>
<?php
} else {
    // Afficher un message si aucun utilisateur correspondant n'a été trouvé
    echo "Aucun utilisateur correspondant n'a été trouvé.";
}
?>
