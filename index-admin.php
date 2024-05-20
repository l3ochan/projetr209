<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Page administrateur</title>
        <link rel="stylesheet" href="style_homepage.css">
        <script src="script.js"></script>
        <link rel="icon" href="/assets/imgs/favicon.png" type="image/png">
    </head>
    
<body>
    <header>
        <?php
        include 'navbar-admin.php'
        include 'config/db_connector.php'

        $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
        ?>

    <?php

    $req_url = $_SERVER['REQUEST_URI'];

    if ($req_url === "/") {
    
        header("Location: https://projetr209.nekocorp.fr/index.php?page=home");
        exit(); 
    }
    ?>

    <?php if ($page === 'home') : ?>
        <?php include('homepage.php'); ?> 
    <?php elseif ($page === 'item-list-admin') : ?>
        <?php include('item-list-admin.php'); ?> 
    <?php elseif ($page === 'item-details') : ?>
        <?php include('user-list-admin.php'); ?>

</body>
</html>