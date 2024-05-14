<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="basket">
    <h1 class="page-title">Panier</h1>
    <div class="separator"></div>
    <div class="basket-body">
        <?php
        session_start();
        include 'config/db_connector.php';
        include 'basket-function.php';

        // Vérifie et crée le panier si nécessaire
        if (!isset($_SESSION['basket_id'])) {
            $basketID = createBasket();
            $_SESSION['basket_id'] = $basketID;
        } else {
            $basketID = $_SESSION['basket_id'];
        }

        // Affichage du contenu du panier
        $query = "SELECT content FROM basket WHERE id = $basketID";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Erreur de requête : " . mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($result);
        $content = $row['content'];
        $contentArray = !empty($content) ? explode(',', $content) : array();

        // Vérifie si le formulaire d'ajout au panier a été soumis
        if (isset($_POST['addItem'])) {
            // Vérifie si les données nécessaires sont présentes dans le formulaire
            if (isset($_POST['itemID'])) {
               // Récupère les données du formulaire
               $itemID = $_POST['itemID'];
               // Vérifie si l'article est déjà dans le panier
               if (!in_array($itemID, $contentArray)) {
                   $contentArray[] = $itemID;
                   $newContent = implode(',', $contentArray);
                   $query = "UPDATE basket SET content = '$newContent' WHERE id = $basketID";
                   $updateResult = mysqli_query($conn, $query);
                   if (!$updateResult) {
                       die("Erreur lors de la mise à jour du panier : " . mysqli_error($conn));
                   }
               } else {
                   echo "Cet article est déjà dans votre panier.";
               }
            } else {
                echo "Erreur : Données manquantes pour ajouter l'article au panier.";
            }
        }

        if (empty($contentArray)) {
            echo "<p>Votre panier est vide</p>";
        } else {
            echo "<table>";
            echo "<tr><th>Libellé</th><th>Prix Unitaire</th><th>Action</th></tr>";
            foreach ($contentArray as $itemID) {
                $query = "SELECT * FROM items WHERE id = $itemID";
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    die("Erreur de requête : " . mysqli_error($conn));
                }
                if ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['make'] . " " . $row['model']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "€</td>";
                    echo "<td><a href='basket.php?action=suppression&itemID=" . $itemID . "'>Supprimer</a></td>";
                    echo "</tr>";
                }
            }
            echo "<tr><td colspan='3'>Total : " . totalPrice() . "€</td></tr>";
            echo "</table>";

            // Bouton pour valider la commande
            echo "<form method='post' action='valider_commande.php'>";
            echo "<input type='submit' value='Valider la commande'>";
            echo "</form>";
        }

        // Vérifie si une action de suppression a été demandée
        if (isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['itemID'])) {
            $itemID = $_GET['itemID'];
            delItemFromBasket($itemID);
            header("Location: https://projetr209.nekocorp.fr/index.php?page=basket");
        }
        ?>
    </div>
</body>
</html>
