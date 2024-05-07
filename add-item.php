<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="add-item">
    <h1 class="page-title">Ajouter un véhicule à vendre</h1>
    <div class="separator"></div>
    <div class="result">
                <?php
            // Afficher les erreurs PHP
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Vérifier si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Inclure le fichier de connexion à la base de données
                include 'config/db_connector.php';
                    

                // Récupérer les valeurs du formulaire après les avoir nettoyées
                $make = htmlspecialchars($_POST['make']);
                $model = htmlspecialchars($_POST['model']);
                $description = htmlspecialchars($_POST['description']);
                $condition = intval($_POST['condition']);
                $mileage = intval($_POST['mileage']);
                $year = intval($_POST['year']);
                $price = floatval($_POST['price']);

                // Préparer la requête SQL d'insertion avec des paramètres nommés
                $query = "INSERT INTO items (make, model, description, `condition`, mileage, year, price) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($query);
        
                // Liaison des paramètres
                $stmt->bind_param('sssiiid', $make, $model, $description, $condition, $mileage, $year, $price);

                // Exécuter la requête d'insertion
                if ($stmt->execute()) {
                    // Récupérer l'ID de l'article inséré
                    $lastInsertedId = $conn->insert_id;
                        // Répertoire de destination pour les images
                    $directory = "data/ads_imgs/";

                    // Créer un nouveau dossier avec l'ID de l'annonce comme nom
                    $itemId = $conn->insert_id; // Récupérer l'ID de l'annonce nouvellement insérée
                    $newDirectory = $directory . $itemId;
                    if (!is_dir($newDirectory)) {
                        mkdir($newDirectory);
                        chmod($newDirectory, 0777);
                    }

                    // Traitement de la photo principale
                    if (isset($_FILES['main_image'])) {
                        $mainImage = $_FILES['main_image'];
                        $mainImagePath = $newDirectory . "/1.png"; // Nom de fichier pour la photo principale
                        move_uploaded_file($mainImage['tmp_name'], $mainImagePath);
                    }

                    // Traitement des autres images
                    if (!empty($_FILES['other_images']['name'][0])) {
                        $otherImages = $_FILES['other_images'];
                        foreach ($otherImages['tmp_name'] as $key => $tmpName) {
                            $index = $key + 2; // Index de l'image à partir de 2.png
                            $otherImagePath = $newDirectory . "/" . $index . ".png"; // Nom de fichier pour les autres images
                            move_uploaded_file($tmpName, $otherImagePath);
                        }
                    }    
                    // Construire l'URL de la page de détails du produit
                    $url = "https://projetr209.nekocorp.fr/index.php?page=item-details&id=" . $lastInsertedId;

                    // Redirection vers la page de détails du produit
                    header("Location: " . $url);
                    exit();
                } else {
                    echo "Erreur lors de l'ajout du véhicule : " . $conn->error;
                }

            }
            ?>

    </div>

    <div class="basket-body">
        <form action='' method="post" enctype="multipart/form-data">
            <label for="make">Marque :</label><br>
            <input type="text" id="make" name="make" required><br><br>

            <label for="model">Modèle :</label><br>
            <input type="text" id="model" name="model" required><br><br>

            <label for="description">Description :</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

            <label for="condition">Condition :</label><br>
            <select id="condition" name="condition" required>
                <option value="1">Très mauvais état</option>
                <option value="2">Mauvais état</option>
                <option value="3">État moyen</option>
                <option value="4">Bon état</option>
                <option value="5">Très bon état</option>
            </select><br><br>

            <label for="mileage">Kilométrage :</label><br>
            <input type="number" id="mileage" name="mileage" required> km<br><br>

            <label for="year">Année de fabrication :</label><br>
            <input type="number" id="year" name="year" required><br><br>

            <label for="price">Prix (en euros) :</label><br>
            <input type="number" id="price" name="price" required> €<br><br>

            <label for="main_image">Photo principale :</label><br>
            <input type="file" id="main_image" name="main_image" accept="image/png" required><br><br>

            <label for="other_images">Autres images :</label><br>
            <input type="file" id="other_images" name="other_images[]" multiple accept="image/png"><br><br>



            <input type="submit" value="Ajouter le véhicule">
        </form>
    </div>
</body>
</html>
