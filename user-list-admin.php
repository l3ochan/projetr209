<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto IUT - Gestion utilisateurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="page-title">Liste des utilisateurs</h1>
    <div class="separator"></div>
    <div class="container">
        <?php 
            include 'config/db_connector.php';
        ?>

<?php
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);