<?php
session_start();

// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=covoit_smart;charset=utf8;', 'covoit', 'rootcovoit');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
ini_set('display_errors', 1);

// Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifier si les champs sont vides
    if (empty($login) || empty($password) || empty($confirm_password)) {
        $message = 'Tous les champs sont requis.';
    } elseif ($password !== $confirm_password) {
        $message = 'Les mots de passe ne correspondent pas.';
    } else {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $bdd->prepare("SELECT * FROM user WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = 'Identifiant déjà pris.';
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer les données dans la base de données
            $stmt = $bdd->prepare("INSERT INTO user (login, password) VALUES (:login, :password)");
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // Rediriger vers la page de connexion
            header("Location: connexion.php");
            exit();
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Inscription Covoit'Smart</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/inscription.css">
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
              <span class="padding-bottom--15">Inscription</span>
              <?php if (!empty($message)) { echo '<p style="color:red;">'.$message.'</p>'; } ?>
              <form id="stripe-signup" method="POST">
                <div class="field padding-bottom--24">
                  <label for="login">Identifiant</label>
                  <input type="text" name="login" required>
                </div>
                <div class="field padding-bottom--24">
                  <label for="password">Mot de passe</label>
                  <input type="password" name="password" required>
                </div>
                <div class="field padding-bottom--24">
                  <label for="confirm_password">Confirmez le mot de passe</label>
                  <input type="password" name="confirm_password" required>
                </div>
                <div class="field padding-bottom--24">
                  <input type="submit" name="submit" value="S'inscrire">
                </div>
              </form>
            </div>
          </div>
          <div class="footer-link padding-top--24">
            <span>Tu as déjà un compte ? <a href="connexion.php">Connecte-toi !</a></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
