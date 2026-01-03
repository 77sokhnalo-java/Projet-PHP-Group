
<?php
// config.php - Configuration minimale
session_start();
class DB {
    public static function connect() {
        try {
            // Connexion simple
            $db = new PDO("mysql:host=localhost;port=3307;dbname=Examen_S2_2016", "root", "");
            return $db;
        } catch(PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}

// Obtenir la connexion
$pdo = DB::connect();

// Initialiser le panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Fonction pour formater les prix
function formatPrix($prix) {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
}
?>