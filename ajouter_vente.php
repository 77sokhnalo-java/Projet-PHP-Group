
<?php
require_once 'config.php';

// Vérifier si un article a été trouvé
if (!isset($_SESSION['article_trouve'])) {
    header('Location: index.php');
    exit();
}

$article = $_SESSION['article_trouve'];
$message = '';

// Ajout au panier
if (isset($_POST['ajouter_vente'])) {
    $quantite = (int)$_POST['quantite_vente'];
    
    if ($quantite > 0 && $quantite <= $article['qteStock']) {
        // Vérifier si l'article est déjà dans le panier
        $trouve = false;
        foreach ($_SESSION['panier'] as $key => $item) {
            if ($item['id'] == $article['id']) {
                $_SESSION['panier'][$key]['quantite'] += $quantite;
                $trouve = true;
                break;
            }
        }
        
        if (!$trouve) {
            $_SESSION['panier'][] = [
                'id' => $article['id'],
                'code' => $article['code'],
                'nom' => $article['nom'],
                'prix' => $article['prix'],
                'quantite' => $quantite
            ];
        }
        
        $message = '<div style="color:blue; font-weight:bold">Article ajouté!</div>';
        
        // Mettre à jour le stock dans la session
        $article['qteStock'] -= $quantite;
        $_SESSION['article_trouve'] = $article;
    } else {
        $message = '<div style="color:red;">Quantité invalide</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter à la vente</title>
</head>
<body>
    <h1>Ajouter à la vente</h1>
    
    <?php echo $message; ?>
    
    <h2>Saisie de la quantité</h2>
    <form method="POST">
        <table border="1">
            <tr>
                <th>Code</th>
                <th>Nom</th>
            </tr>
            <tr>
                <td><?php echo $article['code']; ?></td>
                <td><?php echo $article['nom']; ?></td>
            </tr>
            <tr>
                <th>Quantité stock</th>
                <th>Quantité vente</th>
            </tr>
            <tr>
                <td><?php echo $article['qteStock']; ?></td>
                <td>
                    <input type="number" name="quantite_vente" 
                           min="1" max="<?php echo $article['qteStock']; ?>"
                           value="1" required>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="ajouter_vente" value="Ajouter à la vente">
                </td>
            </tr>
        </table>
    </form>
    
    <?php if (!empty($_SESSION['panier'])): ?>
        
        
        
        <p><a href="terminer_vente.php">Terminer la vente</a></p>
    <?php endif; ?>
    
    
</body>
</html>