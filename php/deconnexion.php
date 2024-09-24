<!DOCTYPE HTML>
<html lang="fr">

<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['login'])) {
    // Détruire toutes les variables de session
    $_SESSION = array();

    // Si la session utilise des cookies, les supprimer aussi
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Détruire la session
    session_destroy();

    // Rediriger l'utilisateur vers la page de connexion (ou accueil)
    header("Location: connexion_covoit.php"); // Remplace par la page souhaitée
    exit;
} else {
    // Si l'utilisateur n'est pas connecté, le rediriger directement
    header("Location: connexion_covoit.php"); // Redirection si l'utilisateur n'est pas connecté
    exit;
}
?>


</html>