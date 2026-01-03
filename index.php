
<?php
require_once 'config.php';

$message = '';

// Recherche d'article
if (isset($_POST['rechercher'])) {
    $code = $_POST['code_article'];
    
    if (empty($code)) {
        $message = '<div style="color:red;">Veuillez entrer un code</div>';
    } else {
        $sql = "SELECT * FROM Article WHERE code = :code";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['code' => $code]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($article) {
            if ($article['qteStock'] > 0) {
                $_SESSION['article_trouve'] = $article;
                header('Location: ajouter_vente.php');
                exit();
            } else {
                $message = '<div style="color:red;">Stock épuisé</div>';
            }
        } else {
            $message = '<div style="color:red;">Article non trouvé</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Ventes</title>
</head>
<body>
    <h1>Recherche d'article</h1>
    
    <?php echo $message; ?>
    
    <h2>Recherche </h2>
    <form method="POST">
        
            
                <label for=""> <strong>Code article</strong></label>
                <input type="text" name="code_article" required>
                <input type="submit" name="rechercher" value="Rechercher">
            
        
    </form>
    
    <p><a href="rechercher_vente.php">Rechercher une vente</a></p>
</body>
</html>