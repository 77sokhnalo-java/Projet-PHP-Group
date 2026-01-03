
<?php
require_once 'config.php';

// Vérifier l'accès
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit();
}

// Vérifier le panier
if (empty($_SESSION['panier'])) {
    die("Panier vide!");
}

// Récupérer les données
$date = $_POST['date'];
$nom = $_POST['nom'];
$adresse = $_POST['adresse'];

// Générer un numéro de vente
//$numero_vente = 'V' . date('YmdHis') . rand(100, 999);

// Fonction pour générer un numéro unique
function genererNumeroUnique($prefix = 'V') {
    global $pdo;
    do {
        // Utilisation de la date + microsecondes pour minimiser les collisions
        $timestamp = date('YmdHis');
        $micro = substr(explode(' ', microtime())[0], 2, 3); 
        $numero = $prefix . $timestamp . $micro;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Vente WHERE numero = ?");
        $stmt->execute([$numero]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    return $numero;
}

// Logique d'enregistrement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Générer le numéro
        $numero = genererNumeroUnique();

        // 2. Préparer l'insertion (ajustez les colonnes selon votre table)
        $sql = "INSERT INTO Vente (numero, date, nom, adresse) 
                VALUES (:numero, :date, :nom, :adresse)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':numero' => $numero,
            ':date' => $date,
            ':nom'  => $_POST['nom'],
            ':adresse'  => $_POST['adresse'],
        ]);

        echo "Vente enregistrée avec succès ! Numéro : " . $numero;

    } catch (PDOException $e) {
        // Gestion spécifique de l'erreur de doublon (Code 23000)
        if ($e->getCode() == 23000) {
            echo "Erreur système : Le numéro de vente a été généré en double. Veuillez valider à nouveau le formulaire.";
        } else {
            echo "Une erreur critique est survenue : " . $e->getMessage();
        }
    }
}


// Démarrer une transaction
$pdo->beginTransaction();

// Récupérer l'ID de la vente
    $id_vente = $pdo->lastInsertId();
    
    // 2. Insérer les articles et mettre à jour les stocks
    
    foreach ($_SESSION['panier'] as $item) {
        // Insérer dans VenteArticle
        $sql_detail = "INSERT INTO VenteArticle (idArt, idVen, qteVendue) 
                       VALUES (:idArt, :idVen, :qteVendue)";
        $stmt_detail = $pdo->prepare($sql_detail);
       
        // Mettre à jour le stock
        $sql_stock = "UPDATE Article 
                      SET qteStock = qteStock - :quantite 
                      WHERE id = :idArt";
        $stmt_stock = $pdo->prepare($sql_stock);
        $stmt_stock->execute([
            'quantite' => $item['quantite'],
            'idArt' => $item['id']
        ]);
    }
    
    // Valider la transaction
    $pdo->commit();
    
    // Vider le panier
    $_SESSION['panier'] = [];
    unset($_SESSION['article_trouve']);
    
    // Afficher confirmation
    echo "<h1>Vente enregistrée avec succès!</h1>";
    echo "<p><strong>Numéro de vente :</strong> $numero</p>";
    echo "<p><strong>Date :</strong> $date</p>";
    echo "<p><strong>Client :</strong> $nom</p>";
    echo "<p><strong>Adresse :</strong> $adresse</p>";
    echo '<p><a href="index.php">Nouvelle vente</a> | ';
    echo '<a href="rechercher_vente.php?numero=' . $numero . '">Voir cette vente</a></p>';

?>

