<?php
session_start(); // Démarre la session

// Déconnexion de l'utilisateur en détruisant toutes les données de session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Redirection vers la page de connexion ou une autre page
header('Location: ../index.php?page=login');
exit();
?>
