<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du véhicule</title>
    <style>
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
        }

        #vehicle-detail-page .main-image {
            max-width: 100%;
            max-height: 1000px;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
        }

        #vehicle-detail-page .navigation-button {
            cursor: pointer;
            font-size: 24px;
            color: #EF7A2D;
            background: none;
            border: none;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        #vehicle-detail-page .next {
            right: 10px;
        }

        #vehicle-detail-page .prev {
            left: 10px;
        }

        #vehicle-detail-page .vehicle-details {
            margin-top: 10px;
        }

        #vehicle-detail-page .make-model, #vehicle-detail-page .price, #vehicle-detail-page .date-mileage {
            margin: 5px 0;
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
            document.getElementById('displayedImage').src = images[currentIndex];
        }
    </script>
</body>
</html>
