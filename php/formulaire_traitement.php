<?php
session_start();

// Connexion à la base de données
try {
    $bdd = new PDO('mysql:host=localhost;dbname=covoit_smart;charset=utf8;', 'covoit', 'rootcovoit');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    ini_set('display_errors', 1);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données du formulaire
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $ville = filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $ecole = filter_input(INPUT_POST, 'ecole', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING);
    $carburant = filter_input(INPUT_POST, 'carburant', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $prix_km = filter_input(INPUT_POST, 'prix_km_val', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $nbrs_places = filter_input(INPUT_POST, 'nbrs_places', FILTER_SANITIZE_NUMBER_INT);

    // Gestion du fichier photo
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = str_replace(' ', '_', $_FILES['photo']['name']); // Remplacer les espaces par des underscores
        $target_dir = "../uploads/photos/";
        $target_file = $target_dir . basename($photo);

        // Vérifiez le fichier avant de le déplacer
        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            // Déplacer le fichier uploadé
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                error_log("Fichier photo uploadé : " . $photo);
            } else {
                error_log("Erreur lors du téléchargement de la photo : " . print_r($_FILES['photo'], true));
            }
        }
    }

    // Gestion du fichier emploi du temps
    $emploi_temps = null;
    if (isset($_FILES['emploi_temps']) && $_FILES['emploi_temps']['error'] == 0) {
        $emploi_temps = str_replace(' ', '_', $_FILES['emploi_temps']['name']); // Remplacer les espaces par des underscores
        $target_dir = "../uploads/emplois_temps/";
        $target_file = $target_dir . basename($emploi_temps);

        // Vérifiez le fichier avant de le déplacer
        if ($_FILES['emploi_temps']['error'] === UPLOAD_ERR_OK) {
            // Déplacer le fichier emploi du temps
            if (move_uploaded_file($_FILES['emploi_temps']['tmp_name'], $target_file)) {
                error_log("Fichier emploi du temps uploadé : " . $emploi_temps);
            } else {
                error_log("Erreur lors du téléchargement de l'emploi du temps : " . print_r($_FILES['emploi_temps'], true));
            }
        }
    }

    // Vérification des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($ville) || empty($tel) || empty($carburant) || empty($prix_km) || empty($nbrs_places) || empty($photo)) {
        echo 'Tous les champs obligatoires doivent être remplis.';
    } else {
        // Insertion des données dans la base de données
        try {
            $stmt = $bdd->prepare("
                INSERT INTO user_data (nom, prenom, ville, ecole, email, tel, carburant, prix_km, places, photo, emploi_du_temps)
                VALUES (:nom, :prenom, :ville, :ecole, :email, :tel, :carburant, :prix_km, :places, :photo, :emploi_du_temps)
            ");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':ecole', $ecole);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':carburant', $carburant);
            $stmt->bindParam(':prix_km', $prix_km);
            $stmt->bindParam(':places', $nbrs_places);
            $stmt->bindParam(':photo', $photo);
            $stmt->bindParam(':emploi_du_temps', $emploi_temps);

            // Affichage des valeurs pour le débogage
            error_log("Valeurs à insérer : " . print_r([$nom, $prenom, $ville, $ecole, $email, $tel, $carburant, $prix_km, $nbrs_places, $photo, $emploi_temps], true));

            if ($stmt->execute()) {
                error_log("Insertion réussie : " . $stmt->rowCount() . " ligne(s) affectée(s).");
                header("Location: ../html/accueil.html");
                exit();
            } else {
                error_log("Erreur lors de l'insertion : " . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            error_log("Erreur lors de l'insertion : " . $e->getMessage());
            echo 'Erreur lors de l\'inscription : ' . $e->getMessage(); // Affiche l'erreur sur la page
        }
    }
}
?>
