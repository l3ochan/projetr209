<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AutoIUT</title>
    <link rel="stylesheet" href="style_homepage.css">
    <script src="script.js"></script>
    <link rel="icon" href="/assets/imgs/favicon.png" type="image/png">
</head>
<body>
    <header>  
    <?php
    // Récupérer le paramètre "page" de l'URL
    $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
    // Inclure la barre de navigation
    include 'navbar.php';
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
        <p class="welcome">Bienvenue sur Auto Télécom, le site de revente de voiture de l'IUT de Mont de Marsan.<br>
        Sur ce site, vous retrouverez toutes nos meilleure occasions disponibles à la vente, obtenues de façon bien evidamment légale 🙂</p>  
        <p><?php echo date("H:i:s");?></p> 
    <?php elseif ($page === 'itemlist') : ?>
        <p class="itemlist">Corps de texte de la liste de produits</p> 
    <?php elseif ($page === 'basket') : ?>
        <p class="basket">Corps de texte du panier</p>
    <?php elseif ($page === 'about-us') : ?>
        <p class="about-us">Corps de texte du a propos</p>
        <iframe style="border-radius:12px" src="https://open.spotify.com/embed/playlist/3vUxlLXE7zyNS4SpRK2PjT?utm_source=generator" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
    <?php elseif ($page === 'login') : ?>
        <p class="login">Corps de texte de la page de login</p>    
    <?php else : ?>
        <div class="error404">
            <img src="/assets/imgs/404.png" class="404 error picture" alt="404 error picture">
            <h1>404 - Page Not Found</h1><br>
        </div>
        <?php endif; ?>


    </header>
</body>
</html>
