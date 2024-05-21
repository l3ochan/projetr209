<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - A propos</title>
    <link rel="stylesheet" href="style_aboutus.css">
</head>
    <body>
        <h1 class="page-title">A propos</h1>
        <div class="separator"></div>
        <p class="about-us">Grossomodo, ca c'est passé comme ca, un jour on débarque en cours et notre prof nous a annoncé qu'on devait faire un site avec du php et du sql, n'ayant jamais utilisé ces outils je me suis mis en tête de faire un site pour vendre les voitures de tout l'établissement. 🙂<br>
        Voici la voiture du prof qui est à l'origine de ce magnifique site très moche. Vous pouvez l'acheter mais vous n'aurez probablement jamais son volant entre vos mains :/</p>
        <?php
        include 'config/db_connector.php';

        // Requête pour récupérer les informations du dernier véhicule ajouté
        $query = "SELECT * FROM items WHERE id = '32'";
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
            echo "<div class=separator></div>";
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
         <p class="about-us">Pour vous Mr Munier qui lisez ceci, on a brainstormé longtemps pour trouver quelquechose  de dynamique à mettre sur notre page "A propos" Je pense que c'est pas trop mal.</p>
         <div style="text-align: center; font-size: 10px; ">
         <p>Droits d'auteur © 2024 Nekocorp. Tous les droits sont réservés, sauf indication contraire. Ce site est distribué sous la licence Apache 2.0, à l'exception des éléments visuels tels que les logos ou photos, qui restent la propriété exclusive de Nekocorp. Consultez le fichier <a href="https://projetr209.nekocorp.fr/LICENSE">LICENSE</a> pour plus de détails.</p>
         <img src="assets/imgs/logo round.png" alt="Logo nekocorp" style="height: 100px;">
         </div>

        
    </body>
</html>
