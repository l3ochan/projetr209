<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="container">
        <?php
        include 'config/db_connector.php';
        $query = "SELECT * FROM items";
        $result = mysqli_query($conn, $query);

        // Boucle pour afficher chaque véhicule
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<a href="/index.php?page=item-details&id=' . $row['id'] . '" class="vehicle-link">';
            echo '<div class="vehicle">';
            echo '<img src="/data/ads_imgs/' . $row["id"] . '/1.png" alt="Photo du véhicule">';
            echo '<div class="details">';
            echo '<div class="make-model">' . $row['make'] . ' ' . $row['model'] . '</div>';
            echo '<div class="price">' . $row['price'] . ' €</div>';
            echo '<div class="year">Année : ' . $row['year'] . '</div>';
            echo '<div class="mileage">Kilométrage : ' . $row['mileage'] . ' km</div>';
            echo '</div>'; // fin .details
            echo '</div>'; // fin .vehicle
        }
        ?>
    </div> <!-- fin .container -->
</body>
</html>