<?php
session_start();

// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=connexion_covoit;charset=utf8;', 'web', '4hxVH5dQ,#RRym;');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
ini_set('display_errors', 1);

// Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Vérifier si les champs sont vides
    if (empty($login) || empty($password)) {
        $message = 'Tous les champs sont requis.';
    } else {
        // Rechercher l'utilisateur dans la base de données
        $stmt = $bdd->prepare("SELECT * FROM usr WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie, redirection vers une autre page
            header("Location: dashboard.php");
            exit();
        } else {
            $message = 'Identifiant ou mot de passe incorrect.';
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion Covoit'Smart</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/connexion.css">
</head>

<body>
  <div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
      <div class="loginbackground box-background--white padding-top--64">
        <div class="loginbackground-gridContainer">
          <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
            <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(150, 250, 150) 33%); flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
            <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
            <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
            <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
            <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
            <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
            <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
            <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
            <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
          </div>
        </div>
      </div>
      <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
        <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
          <h1>Covoit'Smart</h1>
        </div>
        <div class="formbg-outer">
          <div class="formbg">
            <div class="formbg-inner padding-horizontal--48">
              <span class="padding-bottom--15">Connexion</span>
              <?php if (!empty($message)) { echo '<p style="color:red;">'.$message.'</p>'; } ?>
              <form id="stripe-login" method="POST">
                <div class="field padding-bottom--24">
                  <label for="login">Identifiant</label>
                  <input type="text" name="login" required>
                </div>
                <div class="field padding-bottom--24">
                  <label for="password">Mot de passe</label>
                  <input type="password" name="password" required>
                </div>
                <div class="field padding-bottom--24">
                  <input type="submit" name="submit" value="Se connecter">
                </div>
              </form>
            </div>
          </div>
          <div class="footer-link padding-top--24">
            <span>Pas encore de compte ? <a href="inscription.php">Inscris-toi !</a></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
