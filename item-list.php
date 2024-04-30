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

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='vehicle-container clearfix'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='container'>";
                echo "<div class='vehicle'>";
                echo "<a class='vehicle-link' href='index.php?page=item-details&id=" . $row['id'] . "'>";
                echo "<img src='/data/ads_imgs/" . $row["id"] . "/1.png' class='item-picture' alt='Image du véhicule'>";
                echo "<div class='details'>";
                echo "<div class='make-model'>" . $row['make'] . " " . $row['model'] . "</div>";
                echo "<div class='price'>" . $row['price'] . "</div>";
                echo "<div class='date-mileage'>" . $row['year'] . " | " . $row['mileage'] . " km</div>";
                echo "</div>"; // Fermeture de details
                echo "</a>"; // Fermeture de vehicle-link
                echo "</div>"; // Fermeture de vehicle
                echo "</div>"; // Fermeture de container
            }
            echo "</div>"; // Fermeture de vehicle-container
        } else {
            echo "Aucune donnée trouvée dans la table 'items'.";
        }

        mysqli_close($conn);
        ?>

    </div>
</body>
</html>