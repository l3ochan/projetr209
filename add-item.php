<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - Vendre</title>
    <link rel="stylesheet" href="style_additem.css">
</head>
<body id="add-item">
    <h1 class="page-title">AutoIUT - Vendre</h1>
    <div class="separator"></div>
    <div class="result">
            <?php
            // Démarrer la session
            session_start();

            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['token'])) {
                // Afficher un message d'erreur et rediriger vers la page de connexion s'il n'est pas connecté
                echo "<p>Vous devez être connecté pour mettre un véhicule en vente. Veuillez vous <a href='index.php?page=login'>connecter</a>.</p>";
                exit();
            }

            // Inclure le fichier de connexion à la base de données
            include 'config/db_connector.php';

            // Récupérer le token de l'utilisateur connecté
            $token = $_SESSION['token'];

            // Requête SQL pour récupérer l'ID de l'utilisateur à partir de son token
            $query = "SELECT id FROM users WHERE token = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();

            // Vérifier si l'utilisateur existe dans la base de données
            if ($result->num_rows > 0) {
                // Récupérer l'ID de l'utilisateur
                $row = $result->fetch_assoc();
                $userId = $row['id'];

                // Vérifier si le formulaire a été soumis
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Récupérer les valeurs du formulaire après les avoir nettoyées
                    $make = htmlspecialchars($_POST['make']);
                    $model = htmlspecialchars($_POST['model']);
                    $description = htmlspecialchars($_POST['description']);
                    $condition = intval($_POST['condition']);
                    $mileage = intval($_POST['mileage']);
                    $year = intval($_POST['year']);
                    $price = floatval($_POST['price']);
                    $energy = intval($_POST['energy']);
                    $status = intval('1');

                    // Préparer la requête SQL d'insertion avec des paramètres nommés
                    $query = "INSERT INTO items (make, model, description, `condition`, mileage, year, price, energy, status, ownerID, date_of_creation) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

                    $stmt = $conn->prepare($query);

                    // Liaison des paramètres
                    $stmt->bind_param('sssiiidiii', $make, $model, $description, $condition, $mileage, $year, $price, $energy, $status, $userId);

                    // Exécuter la requête d'insertion
                    if ($stmt->execute()) {
                        // Insérer l'annonce réussie, rediriger l'utilisateur vers la page de détails du produit
                        $lastInsertedId = $conn->insert_id;
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
                        $url = "https://projetr209.nekocorp.fr/index.php?page=item-details&id=" . $lastInsertedId;
                        header("Location: " . $url);
                        exit();
                    } else {
                        // Erreur lors de l'insertion de l'annonce
                        echo "Erreur lors de l'ajout du véhicule : " . $conn->error;
                    }

                    // Fermer le statement
                    $stmt->close();
                }
            } else {
                // L'utilisateur n'existe pas dans la base de données, déconnecter et rediriger vers la page de connexion
                session_destroy();
                header('Location: index.php?page=login');
                exit();
            }

            // Fermer la connexion
            $conn->close();
        ?>


    </div>

    <div class="additem-body">
        <form action='' method="post" enctype="multipart/form-data">
            <label for="make">Marque :</label><br>
            <select id="make" name="make" required>
            <option value="">Sélectionnez une marque</option>
            <option value="Abarth">Abarth</option>
            <option value="Alfa Romeo">Alfa Romeo</option>
            <option value="Aston Martin">Aston Martin</option>
            <option value="Audi">Audi</option>
            <option value="Bentley">Bentley</option>
            <option value="BMW">BMW</option>
            <option value="Bugatti">Bugatti</option>
            <option value="Buick">Buick</option>
            <option value="CAT">CAT</option>
            <option value="Cadillac">Cadillac</option>
            <option value="Chevrolet">Chevrolet</option>
            <option value="Chrysler">Chrysler</option>
            <option value="Citroën">Citroën</option>
            <option value="Dacia">Dacia</option>
            <option value="Dodge">Dodge</option>
            <option value="Ferrari">Ferrari</option>
            <option value="Fiat">Fiat</option>
            <option value="Ford">Ford</option>
            <option value="Genesis">Genesis</option>
            <option value="GMC">GMC</option>
            <option value="Honda">Honda</option>
            <option value="Hyundai">Hyundai</option>
            <option value="Infiniti">Infiniti</option>
            <option value="Jaguar">Jaguar</option>
            <option value="Jeep">Jeep</option>
            <option value="Kia">Kia</option>
            <option value="Lamborghini">Lamborghini</option>
            <option value="Land Rover">Land Rover</option>
            <option value="Lexus">Lexus</option>
            <option value="Lincoln">Lincoln</option>
            <option value="Lotus">Lotus</option>
            <option value="Maserati">Maserati</option>
            <option value="Mazda">Mazda</option>
            <option value="McLaren">McLaren</option>
            <option value="Mercedes-Benz">Mercedes-Benz</option>
            <option value="MINI">MINI</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Nissan">Nissan</option>
            <option value="Opel">Opel</option>
            <option value="Peugeot">Peugeot</option>
            <option value="Porsche">Porsche</option>
            <option value="Ram">Ram</option>
            <option value="Renault">Renault</option>
            <option value="Rolls-Royce">Rolls-Royce</option>
            <option value="Saab">Saab</option>
            <option value="Seat">Seat</option>
            <option value="Škoda">Škoda</option>
            <option value="Smart">Smart</option>
            <option value="Subaru">Subaru</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Tesla">Tesla</option>
            <option value="Toyota">Toyota</option>
            <option value="Volkswagen">Volkswagen</option>
            <option value="Volvo">Volvo</option>
            <option value="Custom">Custom</option>
            </select><br><br>


            <label for="model">Modèle :</label><br>
            <input type="text" id="model" name="model" required><br><br>

            <label for="description">Description :</label><br>
            <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

            <label for="condition">Condition :</label><br>
            <select id="condition" name="condition" required>
                <option value="">Sélectionnez l'état du véhicule</option>
                <option value="1">Très mauvais état</option>
                <option value="2">Mauvais état</option>
                <option value="3">État moyen</option>
                <option value="4">Bon état</option>
                <option value="5">Très bon état</option>
            </select><br><br>

            <label for="energy">Energie :</label><br>
            <select id="energy" name="energy" required>
            <option value="">Sélectionnez l'énergie de votre véhicule</option>
                <option value="1">Diesel</option>
                <option value="2">Essence</option>
                <option value="3">Electrique</option>
                <option value="4">Gaz</option>
                <option value="5">Ethanol</option>
            </select><br><br>

            <label for="mileage">Kilométrage :</label><br>
            <input type="number" id="mileage" name="mileage" required> km<br><br>

            <label for="year">Année de fabrication :</label><br>
            <input type="number" id="year" name="year" required><br><br>

            <label for="price">Prix :</label><br>
            <input type="number" id="price" name="price" required> €<br><br>

            <label for="main_image">Photo principale :</label><br>
            <input type="file" id="main_image" name="main_image" accept="image/png"><br><br>

            <label for="other_images">Autres images :</label><br>
            <input type="file" id="other_images" name="other_images[]" multiple accept="image/png"><br><br>



            <input type="submit" value="Ajouter le véhicule">
        </form>
    </div>
</body>
</html>

