<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "covoit_smart";
$username = "covoit";
$password = "rootcovoit";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Supposons que l'utilisateur est connecté et qu'on dispose de son login
session_start();
if (!isset($_SESSION['login'])) {
    die("Vous devez être connecté pour accéder à cette page.");
}

$login = $_SESSION['login'];

// Récupérer les informations de la table "user"
$stmt = $pdo->prepare("SELECT id, login, password FROM user WHERE login = :login");
$stmt->execute(['login' => $login]);
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_info) {
    die("Utilisateur non trouvé.");
}

$user_id = $user_info['id']; // Récupération de l'ID de l'utilisateur

// Récupérer les informations de la table "user_data" en utilisant l'ID de l'utilisateur
$stmt = $pdo->prepare("SELECT photo, ville, tel, nom, prenom, ecole, emploi_du_temps, email FROM user_data WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_data) {
    die("Aucune donnée utilisateur trouvée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .profile-info table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .profile-info table td:first-child {
            font-weight: bold;
            width: 30%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-header">
        <h1>Profil de <?php echo htmlspecialchars($user_data['prenom']) . ' ' . htmlspecialchars($user_data['nom']); ?></h1>
        <img src="<?php echo htmlspecialchars($user_data['photo']); ?>" alt="Photo de profil" class="profile-pic">
    </div>

    <div class="profile-info">
        <h2>Modifier les informations de connexion</h2>
        <form action="modifier_profil.php" method="post">
            <table>
                <tr>
                    <td>Login :</td>
                    <td><input type="text" name="login" value="<?php echo htmlspecialchars($user_info['login']); ?>" required></td>
                </tr>
                <tr>
                    <td>Mot de passe :</td>
                    <td><input type="password" name="password" placeholder="Nouveau mot de passe (laisser vide pour ne pas modifier)"></td>
                </tr>
            </table>

            <h2>Modifier les informations personnelles</h2>
            <table>
                <tr>
                    <td>Nom :</td>
                    <td><input type="text" name="nom" value="<?php echo htmlspecialchars($user_data['nom']); ?>" required></td>
                </tr>
                <tr>
                    <td>Prénom :</td>
                    <td><input type="text" name="prenom" value="<?php echo htmlspecialchars($user_data['prenom']); ?>" required></td>
                </tr>
                <tr>
                    <td>Ville :</td>
                    <td><input type="text" name="ville" value="<?php echo htmlspecialchars($user_data['ville']); ?>" required></td>
                </tr>
                <tr>
                    <td>Téléphone :</td>
                    <td><input type="text" name="tel" value="<?php echo htmlspecialchars($user_data['tel']); ?>" required></td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required></td>
                </tr>
                <tr>
                    <td>École :</td>
                    <td><input type="text" name="ecole" value="<?php echo htmlspecialchars($user_data['ecole']); ?>" required></td>
                </tr>
                <tr>
                    <td>Emploi du temps :</td>
                    <td><input type="text" name="emploi_du_temps" value="<?php echo htmlspecialchars($user_data['emploi_du_temps']); ?>" required></td>
                </tr>
            </table>
            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</div>

</body>
</html>
