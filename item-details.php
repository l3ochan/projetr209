<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style_itemdetails.css">
    <title>AutoIUT - Détails du véhicule</title>
</head>
<body id="vehicle-detail-page">
    <div class="vehicle-details-container">
    <?php 
    include 'config/db_connector.php';
    include 'basket-function.php';

    if (isset($_GET['id'])) {
        $vehicle_id = $_GET['id'];
        $query = "SELECT * FROM items WHERE id = $vehicle_id";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $directory = "data/ads_imgs/" . $row['id'];
            $images = is_dir($directory) ? glob($directory . "/*.{jpg,jpeg,png,gif}", GLOB_BRACE) : [];

            echo "<div class='vehicle-card'>";
            if (!empty($images)) {
                echo "<img src='" . $images[0] . "' class='main-image' id='displayedImage'>";
                echo "<button class='navigation-button prev' onclick='changeImage(-1)'>&#10094;</button>";
                echo "<button class='navigation-button next' onclick='changeImage(1)'>&#10095;</button>";
                echo "<div class='thumbnails'>";
                foreach ($images as $index => $image) {
                    echo "<img src='" . $image . "' class='thumbnail" . ($index == 0 ? " active" : "") . "' onclick='setActiveImage($index)'>";
                }
                echo "</div>";
            }
            echo "<div class='vehicle-details'>";
            echo "<div class='make-model'>" . $row['make'] . " " . $row['model'] . "</div>";
            echo "<div class='price'>" . $row['price'] . "€</div>";
            echo "<div class='date-mileage'>" . $row['year'] . " - " . $row['mileage'] . " km</div>";
            echo "<div class='description'>" . $row['description'] . "</div>";
            echo "<div class='condition'>Etat: ";
            $condition = intval($row['condition']);
            switch ($condition) {
                case 1:
                    echo "Très mauvais état";
                    break;
                case 2:
                    echo "Mauvais état";
                    break;
                case 3:
                    echo "Etat moyen";
                    break;
                case 4:
                    echo "Bon état";
                    break;
                case 5:
                    echo "Très bon état";
                    break;
                default:
                    echo "Inconnu";
                    break;
            }
            echo "<br>";
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

            // Récupérer le nom de l'utilisateur propriétaire de l'annonce
            $ownerID = $row['ownerID'];
            $ownerQuery = "SELECT fname FROM users WHERE id = $ownerID";
            $ownerResult = mysqli_query($conn, $ownerQuery);
            if ($ownerResult && mysqli_num_rows($ownerResult) > 0) {
                $ownerData = mysqli_fetch_assoc($ownerResult);
                echo "<div class='owner'>Annonce créée par : " . $ownerData['fname'] . "</div>";
            } else {
                echo "<div class='owner'>Annonce créée par : Utilisateur inconnu</div>";
            }

            echo "</div>";
            echo "<form method='post' action='https://projetr209.nekocorp.fr/index.php?page=basket'>";
            echo "<input type='hidden' name='itemID' value='" . $row['id'] . "'>";
            echo "<input type='submit' name='addItem' value='Ajouter au panier'>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>Aucun véhicule trouvé avec l'ID spécifié.</p>";
        }
    } else {
        echo "<p>L'ID du véhicule n'est pas spécifié dans l'URL.</p>";
    }
    mysqli_close($conn);
    ?>
    </div>

    <script>
        var images = <?php echo json_encode($images); ?>;
        var currentIndex = 0;

        function changeImage(direction) {
            currentIndex += direction;
            if (currentIndex >= images.length) {
                currentIndex = 0;
            } else if (currentIndex < 0) {
                currentIndex = images.length - 1;
            }
            setActiveImage(currentIndex);
        }

        function setActiveImage(index) {
            currentIndex = index;
            document.getElementById('displayedImage').src = images[currentIndex];
            document.querySelectorAll('.thumbnail').forEach((thumb, idx) => {
                if (idx === currentIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>
