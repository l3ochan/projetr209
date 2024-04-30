<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="item-details">
<?php
include 'config/db_connector.php';

    // Utilisez cet ID pour récupérer les informations de l'article depuis la base de données
    $query = "SELECT * FROM items WHERE id = $item_id";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        // L'article existe, affichez ses détails
        $row = mysqli_fetch_assoc($result);
        echo "<h2>Détails de l'article</h2>";
        echo "<p>ID: " . $row['id'] . "</p>";
        echo "<p>Marque: " . $row['make'] . "</p>";
        echo "<p>Modèle: " . $row['model'] . "</p>";
        echo "<p>Année: " . $row['year'] . "</p>";
        echo "<p>Kilométrage: " . $row['mileage'] . "</p>";
        echo "<p>Etat: " . $row['condition'] . "/5</p>";


        echo "<p>Description: " . $row['description'] . "</p>";
        // Affichez d'autres détails de l'article selon votre structure de données
    } else {
        // L'article n'existe pas
        echo "Aucun article trouvé avec l'ID $item_id.";
    }
?>
    </div>


    <div class="gallery">
<?php

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Chemin du répertoire contenant les images
        $directory = "data/ads_imgs/$item_id";

        // Obtenir la liste des fichiers dans le répertoire
        $files = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

        // Parcourir les fichiers et afficher chaque image
        foreach($files as $image) {
            echo "<img src='$image' alt='Image' class='img-thumbnail'>";
        }
?>
    </div>

    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
    </div>

    <script src="homepage_scripts.js"></script>

</body>
</html>