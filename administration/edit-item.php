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

// Récupérer l'identifiant de l'annonce à modifier depuis l'URL
$itemID = $_GET['id'];

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier s'il y a des images à supprimer
    if (isset($_POST["delete_images"])) {
        $imagesToDelete = $_POST["delete_images"];
        foreach ($imagesToDelete as $imageName) {
            $imagePath = "../data/ads_imgs/{$itemID}/{$imageName}";
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    // Traitement de la photo principale
    if (isset($_FILES['main_image'])) {
        $mainImage = $_FILES['main_image'];
        $mainImagePath = "../data/ads_imgs/{$itemID}/1.png"; // Nom de fichier pour la photo principale
        move_uploaded_file($mainImage['tmp_name'], $mainImagePath);
    }

    // Traitement des autres images
    if (!empty($_FILES['other_images']['name'][0])) {
        $otherImages = $_FILES['other_images'];
        foreach ($otherImages['tmp_name'] as $key => $tmpName) {
            $index = $key + 2; // Index de l'image à partir de 2.png
            $otherImagePath = "../data/ads_imgs/{$itemID}/{$index}.png"; // Nom de fichier pour les autres images
            move_uploaded_file($tmpName, $otherImagePath);
        }
    }

    // Ensuite, mettre à jour les autres champs de la base de données
    // Récupérer les données du formulaire
    $make = $_POST['make'];
    $model = $_POST['model'];
    $description = $_POST['description'];
    $condition = intval($_POST['condition']);
    $mileage = intval($_POST['mileage']);
    $year = intval($_POST['year']);
    $price = floatval($_POST['price']);
    $energy = intval($_POST['energy']);

    // Préparer et exécuter la requête de mise à jour
    $query = "UPDATE items SET make = ?, model = ?, description = ?, `condition` = ?, mileage = ?, year = ?, price = ?, energy = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiiidii', $make, $model, $description, $condition, $mileage, $year, $price, $energy, $itemID);
    $stmt->execute();

    // Rediriger vers la page de liste des annonces après la mise à jour
    header("Location: index-admin.php?page=item-list-admin");
    exit();
}

// Récupérer les données de l'annonce à modifier depuis la base de données
$query = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $itemID);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si une annonce correspondante a été trouvée
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();  
                      
    // Afficher le formulaire de modification pré-rempli avec les valeurs actuelles de l'annonce
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style_additem.css">
    <link rel="stylesheet" href="style_sidewide.css">
    <title>[⚙️] AutoIUT - Modifier annonce</title>
</head>
<body>
    <h1 class="page-title">Modifier l'annonce</h1>
    <div class="separator"></div>
    <form method="post" action="index-admin.php?page=edit-item&id=<?php echo $itemID; ?>" enctype="multipart/form-data">
        <label for="make">Marque:</label><br>
        <select id="make" name="make" required>
            <option value="">Sélectionnez une marque</option>
            <option value="Abarth" <?php if ($row['make'] == 'Abarth') echo 'selected'; ?>>Abarth</option>
            <option value="Alfa Romeo" <?php if ($row['make'] == 'Alfa Romeo') echo 'selected'; ?>>Alfa Romeo</option>
            <option value="Aston Martin" <?php if ($row['make'] == 'Aston Martin') echo 'selected'; ?>>Aston Martin</option>
            <option value="Audi" <?php if ($row['make'] == 'Audi') echo 'selected'; ?>>Audi</option>
            <option value="Bentley" <?php if ($row['make'] == 'Bentley') echo 'selected'; ?>>Bentley</option>
            <option value="BMW" <?php if ($row['make'] == 'BMW') echo 'selected'; ?>>BMW</option>
            <option value="Bugatti" <?php if ($row['make'] == 'Bugatti') echo 'selected'; ?>>Bugatti</option>
            <option value="Buick" <?php if ($row['make'] == 'Buick') echo 'selected'; ?>>Buick</option>
            <option value="CAT" <?php if ($row['make'] == 'CAT') echo 'selected'; ?>>CAT</option>
            <option value="Cadillac" <?php if ($row['make'] == 'Cadillac') echo 'selected'; ?>>Cadillac</option>
            <option value="Chevrolet" <?php if ($row['make'] == 'Chevrolet') echo 'selected'; ?>>Chevrolet</option>
            <option value="Chrysler" <?php if ($row['make'] == 'Chrysler') echo 'selected'; ?>>Chrysler</option>
            <option value="Citroën" <?php if ($row['make'] == 'Citroën') echo 'selected'; ?>>Citroën</option>
            <option value="Dacia" <?php if ($row['make'] == 'Dacia') echo 'selected'; ?>>Dacia</option>
            <option value="Dodge" <?php if ($row['make'] == 'Dodge') echo 'selected'; ?>>Dodge</option>
            <option value="Ferrari" <?php if ($row['make'] == 'Ferrari') echo 'selected'; ?>>Ferrari</option>
            <option value="Fiat" <?php if ($row['make'] == 'Fiat') echo 'selected'; ?>>Fiat</option>
            <option value="Ford" <?php if ($row['make'] == 'Ford') echo 'selected'; ?>>Ford</option>
            <option value="Genesis" <?php if ($row['make'] == 'Genesis') echo 'selected'; ?>>Genesis</option>
            <option value="GMC" <?php if ($row['make'] == 'GMC') echo 'selected'; ?>>GMC</option>
            <option value="Honda" <?php if ($row['make'] == 'Honda') echo 'selected'; ?>>Honda</option>
            <option value="Hyundai" <?php if ($row['make'] == 'Hyundai') echo 'selected'; ?>>Hyundai</option>
            <option value="Infiniti" <?php if ($row['make'] == 'Infiniti') echo 'selected'; ?>>Infiniti</option>
            <option value="Jaguar" <?php if ($row['make'] == 'Jaguar') echo 'selected'; ?>>Jaguar</option>
            <option value="Jeep" <?php if ($row['make'] == 'Jeep') echo 'selected'; ?>>Jeep</option>
            <option value="Kia" <?php if ($row['make'] == 'Kia') echo 'selected'; ?>>Kia</option>
            <option value="Lamborghini" <?php if ($row['make'] == 'Lamborghini') echo 'selected'; ?>>Lamborghini</option>
            <option value="Land Rover" <?php if ($row['make'] == 'Land Rover') echo 'selected'; ?>>Land Rover</option>
            <option value="Lexus" <?php if ($row['make'] == 'Lexus') echo 'selected'; ?>>Lexus</option>
            <option value="Lincoln" <?php if ($row['make'] == 'Lincoln') echo 'selected'; ?>>Lincoln</option>
            <option value="Lotus" <?php if ($row['make'] == 'Lotus') echo 'selected'; ?>>Lotus</option>
            <option value="Maserati" <?php if ($row['make'] == 'Maserati') echo 'selected'; ?>>Maserati</option>
            <option value="Mazda" <?php if ($row['make'] == 'Mazda') echo 'selected'; ?>>Mazda</option>
            <option value="McLaren" <?php if ($row['make'] == 'McLaren') echo 'selected'; ?>>McLaren</option>
            <option value="Mercedes-Benz" <?php if ($row['make'] == 'Mercedes-Benz') echo 'selected'; ?>>Mercedes-Benz</option>
            <option value="MINI" <?php if ($row['make'] == 'MINI') echo 'selected'; ?>>MINI</option>
            <option value="Mitsubishi" <?php if ($row['make'] == 'Mitsubishi') echo 'selected'; ?>>Mitsubishi</option>
            <option value="Nissan" <?php if ($row['make'] == 'Nissan') echo 'selected'; ?>>Nissan</option>
            <option value="Opel" <?php if ($row['make'] == 'Opel') echo 'selected'; ?>>Opel</option>
            <option value="Peugeot" <?php if ($row['make'] == 'Peugeot') echo 'selected'; ?>>Peugeot</option>
            <option value="Porsche" <?php if ($row['make'] == 'Porsche') echo 'selected'; ?>>Porsche</option>
            <option value="Ram" <?php if ($row['make'] == 'Ram') echo 'selected'; ?>>Ram</option>
            <option value="Renault" <?php if ($row['make'] == 'Renault') echo 'selected'; ?>>Renault</option>
            <option value="Rolls-Royce" <?php if ($row['make'] == 'Rolls-Royce') echo 'selected'; ?>>Rolls-Royce</option>
            <option value="Saab" <?php if ($row['make'] == 'Saab') echo 'selected'; ?>>Saab</option>
            <option value="Seat" <?php if ($row['make'] == 'Seat') echo 'selected'; ?>>Seat</option>
            <option value="Škoda" <?php if ($row['make'] == 'Škoda') echo 'selected'; ?>>Škoda</option>
            <option value="Smart" <?php if ($row['make'] == 'Smart') echo 'selected'; ?>>Smart</option>
            <option value="Subaru" <?php if ($row['make'] == 'Subaru') echo 'selected'; ?>>Subaru</option>
            <option value="Suzuki" <?php if ($row['make'] == 'Suzuki') echo 'selected'; ?>>Suzuki</option>
            <option value="Tesla" <?php if ($row['make'] == 'Tesla') echo 'selected'; ?>>Tesla</option>
            <option value="Toyota" <?php if ($row['make'] == 'Toyota') echo 'selected'; ?>>Toyota</option>
            <option value="Volkswagen" <?php if ($row['make'] == 'Volkswagen') echo 'selected'; ?>>Volkswagen</option>
            <option value="Volvo" <?php if ($row['make'] == 'Volvo') echo 'selected'; ?>>Volvo</option>
            <option value="Custom" <?php if ($row['make'] == 'Custom') echo 'selected'; ?>>Custom</option>
            </select><br><br>

            <label for="model">Modèle:</label><br>
            <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($row['model']); ?>" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required><?php echo htmlspecialchars($row['description']); ?></textarea><br><br>

            <label for="condition">Condition:</label><br>
            <select id="condition" name="condition" required>
            <option value="">Sélectionnez l'état du véhicule</option>
            <option value="1" <?php if ($row['condition'] == 1) echo 'selected'; ?>>Très mauvais état</option>
            <option value="2" <?php if ($row['condition'] == 2) echo 'selected'; ?>>Mauvais état</option>
            <option value="3" <?php if ($row['condition'] == 3) echo 'selected'; ?>>État moyen</option>
            <option value="4" <?php if ($row['condition'] == 4) echo 'selected'; ?>>Bon état</option>
            <option value="5" <?php if ($row['condition'] == 5) echo 'selected'; ?>>Très bon état</option>
            </select><br><br>

            <label for="energy">Energie:</label><br>
            <select id="energy" name="energy" required>
            <option value="">Sélectionnez l'énergie de votre véhicule</option>
            <option value="1" <?php if ($row['energy'] == 1) echo 'selected'; ?>>Diesel</option>
            <option value="2" <?php if ($row['energy'] == 2) echo 'selected'; ?>>Essence</option>
            <option value="3" <?php if ($row['energy'] == 3) echo 'selected'; ?>>Electrique</option>
            <option value="4" <?php if ($row['energy'] == 4) echo 'selected'; ?>>Gaz</option>
            <option value="5" <?php if ($row['energy'] == 5) echo 'selected'; ?>>Ethanol</option>
            </select><br><br>

            <label for="mileage">Kilométrage:</label><br>
            <input type="number" id="mileage" name="mileage" value="<?php echo htmlspecialchars($row['mileage']); ?>" required> km<br><br>

            <label for="year">Année de fabrication:</label><br>
            <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($row['year']); ?>" required><br><br>

            <label for="price">Prix:</label><br>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required> €<br><br>

            <label for="main_image">Photo principale:</label><br>
            <input type="file" id="main_image" name="main_image" accept="image/png"><br><br>

            <label for="other_images">Autres images:</label><br>
            <input type="file" id="other_images" name="other_images[]" multiple accept="image/png"><br><br>
            <h2>Images existantes</h2><br>
            <p>Selectionnez les images que vous souhaitez supprimer</p><br>
            <div class="picture">
        <?php
        // Affichage des images existantes avec des cases à cocher
        $directory = "../data/ads_imgs/{$itemID}/";
        if (is_dir($directory)) {
            $images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
            foreach ($images as $image) {
                $imageName = basename($image);
                echo "<div class='checkbox-container'>";
                echo "<input type='checkbox' name='delete_images[]' value='{$imageName}'>";
                echo "<img src='{$image}' alt='Image'>";
                echo "</div>";
            }
        }
        ?>
    </div>


            <input type="submit" value="Enregistrer">
            </form>
            </body>
            </html>
            <?php
            } else {
                // Afficher un message si aucune annonce correspondante n'a été trouvée
                echo "Aucune annonce correspondante n'a été trouvée.";
            }

            // Gestion de la soumission du formulaire pour supprimer les images cochées
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_images"])) {
                $imagesToDelete = $_POST["delete_images"];
                foreach ($imagesToDelete as $imageName) {
                    $imagePath = $directory . $imageName;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                // Redirection vers la même page pour rafraîchir l'affichage des images
                header("Location: https://projetr209.nekocorp.fr/administration/index-admin.php?page=edit-item&id=" . $itemID);
                exit();
            }
            ?>
