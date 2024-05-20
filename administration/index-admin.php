<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AutoIUT - Administration</title>
    <link rel="stylesheet" href="<link rel="stylesheet" href="../administration/style_administration.css?v=2">
    <link rel="icon" href="../assets/imgs/favicon.png" type="image/png">
</head>
<body class="admin-menu">
    <header>
        <?php
        include 'navbar-admin.php';
        include 'config/db_connector.php';

        $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
        ?>
    </header>
</body>
</html>
