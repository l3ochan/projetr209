<?php 
    include 'db_connector.php';

    $query = "SELECT * FROM items";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Afficher les données dans un tableau
            echo "<table class='itemlist'>";
            echo "<tr><th>Marque</th><th>Modèle</th><th>Kilométrage</th><th>Date de création</th><th>Année</th><th>Lien</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['make'] . "</td>";
                echo "<td>" . $row['model'] . "</td>";
                echo "<td>" . $row['mileage'] . "</td>";
                echo "<td>" . $row['date_of_creation'] . "</td>";
                echo "<td>" . $row['year'] . "</td>";
                echo "<td><a href='index.php?page=item-details&id=" . $row['id'] . "'>Voir détails</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune donnée trouvée dans la table 'items'.";
        }

        mysqli_close($conn);
        ?>