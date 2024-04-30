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

    <script>
        // JavaScript pour afficher les images agrandies dans une fenêtre modale
        var modal = document.getElementById('myModal');
        var images = document.querySelectorAll('.img-thumbnail');
        var modalImg = document.getElementById("img01");
        var closeButton = document.getElementsByClassName("close")[0];

        // Fonction pour afficher une image dans la fenêtre modale
        function displayImage(imageSrc) {
            modal.style.display = "block";
            modalImg.src = imageSrc;
        }

        // Attacher un événement de clic à chaque image pour afficher l'image dans la fenêtre modale
        images.forEach(function(image) {
            image.addEventListener('click', function() {
                var imageSrc = this.src;
                displayImage(imageSrc);
            });
        });

        // Attacher un événement de clic au bouton de fermeture pour fermer la fenêtre modale
        closeButton.addEventListener('click', function() {
            modal.style.display = "none";
        });
    </script>

</body>
</html>
