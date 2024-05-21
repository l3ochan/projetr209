<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style_sidewide.css">
    <script src="script.js"></script>
    <link rel="icon" href="/assets/imgs/favicon.png" type="image/png">
</head>
<body>
    <header>  
    <?php
    include 'navbar.php';
    include 'config/db_connector.php';
    // Récupérer le paramètre "page" de l'URL
    $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
    // Inclure la barre de navigation
    
    ?>

    <!-- Contenu spécifique à chaque page -->
    <?php
    // Récupérer l'URL demandée
    $req_url = $_SERVER['REQUEST_URI'];

    // Vérifier si l'URL demandée est "/"
    if ($req_url === "/") {
        // Rediriger vers la page d'accueil
        header("Location: https://projetr209.nekocorp.fr/index.php?page=home");
        exit(); // Arrêter l'exécution du script après la redirection
    }
    ?>
    <?php if ($page === 'home') : ?>
        <?php include('homepage.php'); ?> 
    <?php elseif ($page === 'item-list') : ?>
        <?php include('item-list.php'); ?> 
    <?php elseif ($page === 'item-details') : ?>
            <div class="item-details">
                <?php 
                    if (isset($_GET['id'])) {
                    // Récupérer l'ID depuis l'URL
                    $item_id = $_GET['id'];

                    // Inclure le fichier item-list.php en lui passant l'ID en tant que paramètre
                    include('item-details.php');
                } else {
                    // Afficher un message d'erreur ou rediriger l'utilisateur vers une autre page si aucun ID n'est spécifié
                    echo "Aucun ID n'a été spécifié.";
                    // Ou rediriger
                    // header("Location: une_autre_page.php");
                    // exit();
                }
                ?>
            </div>

    <?php elseif ($page === 'basket') : ?>
        <?php include('basket.php'); ?>
    <?php elseif ($page === 'about-us') : ?>
        
        <?php include('aboutus.php'); ?>
        <?php elseif ($page === 'login') : ?>
        <?php include('sessions/login.php'); ?>  
    <?php elseif ($page === 'signin') : ?>
        <?php include('sessions/signin.php'); ?>  
    <?php elseif ($page === 'add-item') : ?>
        <?php include('add-item.php'); ?>    
    <?php elseif ($page === 'myvehicles') : ?>
        <?php include('user_personal/item-list-sorted.php'); ?>  
    <?php elseif ($page === 'edit-item') : ?>
        <?php include('user_personal/edit-item.php'); ?>  
    <?php elseif ($page === 'edit-profile') : ?>
        <?php include('user_personal/edit-profile.php'); ?>  
    <?php else : ?>
        <div class="error404">
            <link rel="stylesheet" href="style404.css">
            <img src="/assets/imgs/404.png" class="404 error picture" alt="404 error picture">
            <?php header("HTTP/1.0 404 Not Found");?>
            <h1>404 - Page Not Found</h1><br>
        </div>
        <?php endif; ?>

    
</body>
</html>
