<link rel="stylesheet" href="style_navbar.css">
<nav class="menu">
    <ul class="link-container">
        <a href="index.php?page=home"><img src="assets/imgs/logo.png" class="home-picture" alt="Logo"></a>
        <li><a href="index.php?page=home">Accueil</a></li>
        <li><a href="index.php?page=item-list">Nos véhicules</a></li>
        <li><a href="index.php?page=basket">Panier</a></li>
        <li><a href="index.php?page=about-us">A propos</a></li>
    </ul>
    <div class="account">
        <a href="index.php?page=add-item">Vendre</a>
        <?php
            session_start();
            if(isset($_SESSION['token'])) {
                if(isset($_SESSION['fname'])) {
                    $fname = $_SESSION['fname'];
                    echo "<div id='userDropdown' class='user-dropdown'>";
                    echo "<span>Bonjour, $fname</span>";
                    echo "<div class='dropdown-content' id='dropdownContent'>";
                    echo '<a href="index.php?page=edit-profile">Profil</a>';
                    echo '<a href="index.php?page=myvehicles">Mes Annonces</a>';    
                    
                    // Vérifier si l'utilisateur est un administrateur
                    include 'config/db_connector.php'; // Connexion à la base de données
                    $token = $_SESSION['token'];
                    $query = "SELECT role FROM users WHERE token = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('s', $token);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        if($row['role'] == 3) {
                            echo '<a href="/administration/index-admin.php?page=home">Vue Admin</a>'; // Lien vers la vue admin
                        }
                    }
                    
                    echo "<a href='sessions/logout.php'>Se déconnecter</a>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "Nom d'utilisateur non trouvé dans la session.";
                }
            } else {
                echo "<a href='index.php?page=login'>Se connecter</a>";
            }
        ?>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var userDropdown = document.getElementById("userDropdown");
    var dropdownContent = document.getElementById("dropdownContent");

    // Fonction pour afficher le dropdown
    function showDropdown() {
        dropdownContent.style.display = "block";
    }

    // Fonction pour masquer le dropdown
    function hideDropdown() {
        dropdownContent.style.display = "none";
    }

    // Ajouter un écouteur d'événements pour afficher le dropdown
    userDropdown.addEventListener("mouseover", showDropdown);

    // Ajouter un écouteur d'événements pour masquer le dropdown lorsque le curseur quitte la zone du dropdown
    dropdownContent.addEventListener("mouseleave", hideDropdown);
});
</script>
