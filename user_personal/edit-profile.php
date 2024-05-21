<?php
session_start();
include '../config/db_connector.php';

// Vérifier si un utilisateur est connecté
if (!isset($_SESSION['token'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php?page=login");
    exit();
}

// Récupérer l'ID de l'utilisateur connecté à partir du token
$token = $_SESSION['token'];
$query = "SELECT id, fname, lname, email FROM users WHERE token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $userID = $row['id'];
    $fname = $row['fname'];
    $lname = $row['lname'];
    $email = $row['email'];
} else {
    // Afficher un message d'erreur si l'utilisateur n'est pas trouvé dans la base de données
    echo "<h1>Erreur</h1>";
    echo "<p>Impossible de récupérer les informations de l'utilisateur.</p>";
    exit();
}

// Vérifier si le formulaire est soumis pour mettre à jour les informations de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $new_fname = $_POST['fname'];
    $new_lname = $_POST['lname'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'];

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $query = "UPDATE users SET fname = ?, lname = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssi', $new_fname, $new_lname, $new_email, $new_password, $userID);
    $stmt->execute();

    // Rediriger vers la page de profil après la mise à jour
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style_edituser.css">
    <title>Votre profil</title>
</head>
<body>
    <h1 class="page-title">Votre profil</h1>
    <div class="separator"></div>
    <form method="post" action="profile.php">
        <label for="fname">Prénom:</label><br>
        <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required><br><br>

        <label for="lname">Nom:</label><br>
        <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label for="password">Mot de passe:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Enregistrer les modifications">
        </form>
    </form>
</body>
</html>
