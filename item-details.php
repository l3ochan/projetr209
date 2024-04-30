<?php
include 'db_connector.php';

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
