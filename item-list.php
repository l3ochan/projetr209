<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoIUT - Nos véhicules</title>
    <link rel="stylesheet" href="style_itemlist.css">
</head>
<body>
    <h1 class="page-title">Liste des véhicules</h1>
    <div class="separator"></div>
    <div class="filters">
        <form action='https://projetr209.nekocorp.fr/index.php' method="get">

            <input type="hidden" name="page" value="item-list">

            <label for="make">Marque :</label>
        <select id="make" name="make">
            <option value="">Sélectionnez une marque</option>
            <option value="Abarth">Abarth</option>
            <option value="Alfa Romeo">Alfa Romeo</option>
            <option value="Aston Martin">Aston Martin</option>
            <option value="Audi">Audi</option>
            <option value="Bentley">Bentley</option>
            <option value="BMW">BMW</option>
            <option value="Bugatti">Bugatti</option>
            <option value="Buick">Buick</option>
            <option value="CAT">CAT</option>
            <option value="Cadillac">Cadillac</option>
            <option value="Chevrolet">Chevrolet</option>
            <option value="Chrysler">Chrysler</option>
            <option value="Citroën">Citroën</option>
            <option value="Dacia">Dacia</option>
            <option value="Dodge">Dodge</option>
            <option value="Ferrari">Ferrari</option>
            <option value="Fiat">Fiat</option>
            <option value="Ford">Ford</option>
            <option value="Genesis">Genesis</option>
            <option value="GMC">GMC</option>
            <option value="Honda">Honda</option>
            <option value="Hyundai">Hyundai</option>
            <option value="Infiniti">Infiniti</option>
            <option value="Jaguar">Jaguar</option>
            <option value="Jeep">Jeep</option>
            <option value="Kia">Kia</option>
            <option value="Lamborghini">Lamborghini</option>
            <option value="Land Rover">Land Rover</option>
            <option value="Lexus">Lexus</option>
            <option value="Lincoln">Lincoln</option>
            <option value="Lotus">Lotus</option>
            <option value="Maserati">Maserati</option>
            <option value="Mazda">Mazda</option>
            <option value="McLaren">McLaren</option>
            <option value="Mercedes-Benz">Mercedes-Benz</option>
            <option value="Mini">MINI</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Nissan">Nissan</option>
            <option value="Opel">Opel</option>
            <option value="Peugeot">Peugeot</option>
            <option value="Porsche">Porsche</option>
            <option value="Ram">Ram</option>
            <option value="Renault">Renault</option>
            <option value="Rolls-Royce">Rolls-Royce</option>
            <option value="Saab">Saab</option>
            <option value="Seat">Seat</option>
            <option value="Škoda">Škoda</option>
            <option value="Smart">Smart</option>
            <option value="Subaru">Subaru</option>
            <option value="Suzuki">Suzuki</option>
            <option value="Tesla">Tesla</option>
            <option value="Toyota">Toyota</option>
            <option value="Volkswagen">Volkswagen</option>
            <option value="Volvo">Volvo</option>
            <option value="Custom">Custom</option>
        </select>
        
        <label for="energy">Énergie :</label>
        <select id="energy" name="energy">
            <option value="">Sélectionnez l'énergie</option>
            <option value="1">Diesel</option>
            <option value="2">Essence</option>
            <option value="3">Electrique</option>
            <option value="4">Gaz</option>
            <option value="5">Ethanol</option>
        </select>
        
        <label for="order">Etat :</label>
        <select id="order" name="order">
            <option value="">Sélectionnez un ordre</option>
            <option value="asc">Croissant (meilleur d'abord)</option>
            <option value="desc">Décroissant (moins bon d'abord)</option>
        </select>

        <label for="price">Trier par prix :</label>
        <select id="price" name="price">
            <option value="">Sélectionnez un ordre</option>
            <option value="asc">Croissant (moins cher d'abord)</option>
            <option value="desc">Décroissant (plus cher d'abord)</option>
        </select>
        <label for="time">Trier par date d'ajout :</label>
        <select id="time" name="time">
            <option value="">Sélectionnez un ordre</option>
            <option value="asc">Croissant (Plus ancien d'abord)</option>
            <option value="desc">Décroissant (Plus récent d'abord)</option>
        </select>
        
        <input type="submit" value="Filtrer">
    </form>
    </div>
    <div class="container">
        <?php 
            include 'config/db_connector.php';

            // Construction de la requête en fonction des filtres
            $query = "SELECT * FROM items";
            if (isset($_GET['make']) && !empty($_GET['make'])) {
                $make = $_GET['make'];
                $query .= " WHERE make = '$make'";
            }
            if (isset($_GET['energy']) && !empty($_GET['energy'])) {
                $energy = $_GET['energy'];
                if (strpos($query, 'WHERE') === false) {
                    $query .= " WHERE energy = '$energy'";
                } else {
                    $query .= " AND energy = '$energy'";
                }
            }
            if (isset($_GET['order']) && !empty($_GET['order'])) {
                $order = $_GET['order'];
                $query .= " ORDER BY `condition` " . ($order == 'desc' ? 'ASC' : 'DESC');
            }
            if (isset($_GET['price']) && !empty($_GET['price'])) {
                $order = $_GET['price'];
                $query .= " ORDER BY price " . ($order == 'asc' ? 'ASC' : 'DESC');
            }
            if (isset($_GET['time']) && !empty($_GET['time'])) {
                $order = $_GET['id'];
                $query .= " ORDER BY id " . ($order == 'asc' ? 'ASC' : 'DESC');
            }

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
                    echo "<input type='submit' name='addItem' value='Ajouter au panier'>";
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
    <script>
        // Fonction pour extraire les paramètres de l'URL et les sélectionner dans les menus déroulants
        function selectFiltersFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            const make = urlParams.get('make');
            const energy = urlParams.get('energy');
            const order = urlParams.get('order');
            const price = urlParams.get('price');
            const time = urlParams.get('time');

            document.getElementById('make').value = make || '';
            document.getElementById('energy').value = energy || '';
            document.getElementById('order').value = order || '';
            document.getElementById('price').value = price || '';
            document.getElementById('time').value = time || '';
        }

        // Appeler la fonction au chargement de la page
        window.addEventListener('DOMContentLoaded', selectFiltersFromURL);
    </script>
</body>
</html>
