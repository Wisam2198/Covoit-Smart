<!DOCTYPE HTML>
<html lang="fr">

<?php
// DÃ©marrer la session
session_start();

// VÃ©rifier si l'utilisateur est connectÃ©
if (isset($_SESSION['login'])) {
    // DÃ©truire toutes les variables de session
    $_SESSION = array();

    // Si la session utilise des cookies, les supprimer aussi
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // DÃ©truire la session
    session_destroy();

    // Rediriger l'utilisateur vers la page de connexion (ou accueil)
    header("Location: connexion.php"); // Remplace par la page souhaitÃ©e
    exit;
} else {
    // Si l'utilisateur n'est pas connectÃ©, le rediriger directement
    header("Location: connexion.php"); // Redirection si l'utilisateur n'est pas connectÃ©
    exit;
}
?>


</html>
