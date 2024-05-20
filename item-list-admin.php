<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto IUT - Gestion véhicules</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="page-title">Liste des véhicules</h1>
    <div class="separator"></div>
    <div class="container">
        <?php 
            include 'config/db_connector.php';
            

            $query = "SELECT * FROM items";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='vehicle-container'>";
                    echo "<div class='vehicle'>";
                    echo "<a href='index.php?page=item-details&id=" . $row['id'] . "' class='vehicle-link'>";
                    echo "<img src='/data/ads_imgs/" . $row["id"] . "/1.png' class='item-picture'>";
                    echo "<div class='details'>";
                    echo "<div class='make-model'>" . $row['make'] . " " . $row['model'] . "</div>";
                    echo "<div class='price'>" . $row['price'] . "€</div>";
                    echo "<div class='date-mileage'>" . $row['year'] . " - " . $row['mileage'] . " km</div>";
                    echo "<div class='energy'>";
                    $energy = intval($row['energy']);
                    switch ($energy) {
                        case 1:
                            echo "Diesel";
                            break;
                        case 2:
                            echo "Essence";
                            break;
                        case 3:
                            echo "Electrique";
                            break;
                        case 4:
                            echo "Gaz";
                            break;
                        case 5:
                            echo "Ethanol";
                            break;
                        default:
                            echo "Inconnu";
                            break;
                    }
                    echo "</div>";
                    // Bouton d'ajout au panier avec un formulaire
                    echo "<form method='post' action='https://projetr209.nekocorp.fr/index.php?page=basket'>";
                    echo "<input type='hidden' name='itemID' value='" . $row['id'] . "'>";
                    echo "<input type='submit' name='deleteItem' value='Supprimer annonce'>";
                    echo "</form>";
                    echo "</div>";
                    echo "</a>";
                    echo "</div>";
                    echo "</div>";
                    
                }
            } else {
                echo "<p>Aucune donnée trouvée dans la table 'items'.</p>";
            }

            mysqli_close($conn);
        ?>
    </div>
</body>
</html>
