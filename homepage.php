<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - Accueil</title>
    <link rel="stylesheet" href="style_homepage.css">
</head>
    <body>
        <p class="welcome">Bienvenue sur Auto Télécom, le site de revente de voiture de l'IUT de Mont de Marsan.<br>
        Sur ce site, vous retrouverez toutes nos meilleure occasions disponibles à la vente, obtenues de façon bien evidamment légale 🙂.</p>
        <p class="disclaimer-project">DISCLAIMER : Ce site fait partie d'un projet scolaire de fin d'année, aucun des articles en "vente" ne le sont réellement.<br>
        Ce message s'adresse au malins qui mettent des annonces innapropriées ou qui tentent de pirater nos systèmes, ce n'est pas parce que c'est le site du camarade de classe que la loi ne s'y applique pas, une tentative de piratage reste une tentative de piratage. Vous êtes prévenus.
        </p>   
        <div class="latest-vehicle">
        <h2 class ="page-title">Dernier véhicule ajouté :</h2>
        <?php
        include 'config/db_connector.php';

        // Requête pour récupérer les informations du dernier véhicule ajouté
        $query = "SELECT * FROM items ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
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
            echo "<input type='submit' name='addItem' value='Ajouter au panier'>";
            echo "</form>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p>Aucun véhicule trouvé.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
    </body>
</html>
