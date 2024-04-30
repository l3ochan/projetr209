<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du véhicule</title>
    <style>
        /* Styles globaux de la page */
        #vehicle-detail-page .vehicle-details-container {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
            color: #333;
        }

        #vehicle-detail-page .vehicle-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Styles pour l'image principale du véhicule */
        #vehicle-detail-page .main-image {
            max-width: 100%;
            max-height: 600px;
            display: block;
            border-radius: 8px;
        }

        /* Boutons de navigation */
        #vehicle-detail-page .navigation-button {
            cursor: pointer;
            font-size: 24px;
            color: #EF7A2D;
            background: none;
            border: none;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1000; /* Assure que les boutons sont au-dessus des autres éléments */
        }

        #vehicle-detail-page .next {
            right: 30px;
        }

        #vehicle-detail-page .prev {
            left: 30px;
        }

        /* Styles pour les détails du véhicule sous l'image principale */
        #vehicle-detail-page .vehicle-details {
            margin-top: 20px;
        }

        #vehicle-detail-page .make-model, #vehicle-detail-page .price, #vehicle-detail-page .date-mileage {
            margin: 5px 0;
        }

        /* Styles pour la galerie de miniatures */
        #vehicle-detail-page .thumbnails {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            overflow-x: auto; /* Permet de faire défiler horizontalement si nécessaire */
        }

        #vehicle-detail-page .thumbnail {
            width: 100px;
            height: 75px; /* Hauteur fixe pour toutes les miniatures */
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s, transform 0.3s;
            object-fit: cover; /* Préserve les proportions de l'image */
            margin-right: 10px; /* Espacement entre les miniatures */
            border-radius: 4px; /* Arrondit les coins des miniatures */
        }

        #vehicle-detail-page .thumbnail:hover, #vehicle-detail-page .thumbnail.active {
            transform: scale(1.1); /* Effet de zoom pour l'image active et au survol */
            opacity: 1;
        }

    </style>
</head>
<body id="vehicle-detail-page">
    <div class="vehicle-details-container">
    <?php 
    include 'config/db_connector.php';

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
            echo "<div class='condition'>Condition: " . $row['condition'] . "</div>";
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
