<?php
include 'config/db_connector.php';

/**
 * Crée un panier si nécessaire et retourne l'ID du panier.
 * @return int
 */
function createBasket() {
    global $conn;

    // Insère un nouveau panier dans la base de données et récupère son ID
    $query = "INSERT INTO basket (content) VALUES ('')";
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    } else {
        echo "Erreur lors de la création du panier : " . mysqli_error($conn);
        return null;
    }
}

/**
 * Ajoute un article au panier.
 * @param int $itemID
 */
function addItemToBasket($itemID) {
    global $conn;

    // Vérifie si le panier existe dans la session
    if (isset($_SESSION['basket_id'])) {
        $basketID = $_SESSION['basket_id'];

        // Récupère le contenu actuel du panier
        $query = "SELECT content FROM basket WHERE id = $basketID";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        // Ajoute le nouvel itemID au contenu du panier
        $content = $row['content'];
        $contentArray = $content ? explode(',', $content) : array();
        $contentArray[] = $itemID;
        $newContent = implode(',', $contentArray);

        // Met à jour le panier avec le nouveau contenu
        $query = "UPDATE basket SET content = '$newContent' WHERE id = $basketID";
        mysqli_query($conn, $query);
    } else {
        echo "Erreur : Panier non trouvé.";
    }
}

/**
 * Calcule le prix total du panier.
 * @return float
 */
function totalPrice() {
    global $conn;

    if (isset($_SESSION['basket_id'])) {
        $basketID = $_SESSION['basket_id'];

        // Récupère le contenu du panier
        $query = "SELECT content FROM basket WHERE id = $basketID";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $content = $row['content'];

        // Calcule le prix total
        $total = 0;
        if ($content) {
            $contentArray = explode(',', $content);
            foreach ($contentArray as $itemID) {
                $query = "SELECT price FROM items WHERE id = $itemID";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $total += $row['price'];
            }
        }
        return $total;
    }
    return 0;
}

/**
 * Supprime un article du panier.
 * @param int $itemID
 */
function delItemFromBasket($itemID) {
    global $conn;

    if (isset($_SESSION['token'])) {
        $token = $_SESSION['token'];

        // Récupérer l'ID de l'utilisateur à partir du token
        $query = "SELECT id FROM users WHERE token = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $userID = $row['id'];

            // Récupérer le panier de l'utilisateur
            $query = "SELECT id, content FROM basket WHERE ownerID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $basketID = $row['id'];
                $content = $row['content'];
            } else {
                echo "Erreur : Panier non trouvé pour cet utilisateur.";
                return;
            }
        } else {
            echo "Erreur : Utilisateur non trouvé.";
            return;
        }
    } elseif (isset($_SESSION['basket_id'])) {
        // Utiliser le basket_id stocké dans la session
        $basketID = $_SESSION['basket_id'];

        // Récupérer le contenu actuel du panier
        $query = "SELECT content FROM basket WHERE id = $basketID";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $content = $row['content'];
    } else {
        echo "Erreur : Aucun panier n'est associé à cet utilisateur.";
        return;
    }

    // Supprimer l'itemID du contenu du panier
    $contentArray = explode(',', $content);
    if (($key = array_search($itemID, $contentArray)) !== false) {
        unset($contentArray[$key]);
    }
    $newContent = implode(',', $contentArray);

    // Mettre à jour le panier avec le nouveau contenu
    $query = "UPDATE basket SET content = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $newContent, $basketID);
    $stmt->execute();
}


?>
