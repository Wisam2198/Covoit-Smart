<?php
// Paramètres de connexion à la base de données
$host = 'localhost'; // ou l'adresse de ton serveur de base de données
$dbname = 'covoit_smart';
$username = 'covoit'; // Utilisateur MySQL
$password = 'rootcovoit'; // Mot de passe MySQL

try {
    // Connexion à la base de données via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configuration des erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les données de la table user_data
    $sql = 'SELECT * FROM user_data';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Stocker les résultats sous forme de tableau associatif
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des utilisateurs</title>
    


</body>
</html>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpooling Service</title>
    <link rel="stylesheet" href="../assets/css/accueil.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur Covoit'Smart</h1>
    </header>

    <nav>
        <ul>
            <li><a href="accueil.html">Accueil</a></li>
            <li><a class="nav-link" href="#profile">Profile</a></li>
            <li><a href="../php/deconnexion.php">Déconnexion</a></li>
        </ul>
    </nav>

    <main>
        <h2>Bienvenue à notre service de covoiturage</h2>
        <p>Utilisez le menu ci-dessus pour naviguer entre les différentes sections de votre compte et planifiez vos trajets facilement.</p>
    </main>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Liste des utilisateurs</h1>

<?php if (!empty($users)): ?>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Autres valeurs</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                    <td>
                        <?php
                        // Afficher les autres colonnes
                        foreach ($user as $key => $value) {
                            if ($key != 'nom') {
                                echo htmlspecialchars($value) . ' ';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>

<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2024 Service de Covoiturage. Tous droits réservés.</p>
    </footer>
</body>
</html>

