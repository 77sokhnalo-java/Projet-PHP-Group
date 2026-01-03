
<?php
require_once 'config.php';

$vente = null;
$articles = [];
$total = 0;

// Recherche
if (isset($_GET['numero'])) {
    $numero = $_GET['numero'];
    
    if (!empty($numero)) {
        // Chercher la vente
        $sql_vente = "SELECT * FROM Vente WHERE numero = :numero";
        $stmt_vente = $pdo->prepare($sql_vente);
        $stmt_vente->execute(['numero' => $numero]);
        $vente = $stmt_vente->fetch(PDO::FETCH_ASSOC);
        
        if ($vente) {
            $id_vente = $vente['id'];
            
            // Chercher les articles
            $sql_articles = "SELECT A.code, A.nom, A.prix, VA.qteVendue 
                             FROM VenteArticle VA
                             JOIN Article A ON VA.idArt = A.id
                             WHERE VA.idVen = :id_vente";
            $stmt_articles = $pdo->prepare($sql_articles);
            $stmt_articles->execute(['id_vente' => $id_vente]);
            $articles = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculer le total
            foreach ($articles as $article) {
                $total += $article['prix'] * $article['qteVendue'];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recherche de vente</title>
</head>
<body>
    <h1>Recherche d'une vente</h1>
    
    <!-- Formulaire de recherche -->
    <h2>Rechercher une vente</h2>
    <form method="GET">
        <table>
            <tr>
                <td><strong>Numéro</strong></td>
                <td><input type="text" name="numero" placeholder="Ex: V2024121" required></td>
                <td><input type="submit" value="Rechercher"></td>
            </tr>
        </table>
    </form>
    
    <!-- Résultats -->
    <?php if ($vente): ?>
        <h2>Informations de la vente</h2>
        <p><strong>Numéro :</strong> <?php echo $vente['numero']; ?></p>
        <p><strong>Date :</strong> <?php echo $vente['date']; ?></p>
        </p><p><strong>Nom :</strong> <?php echo $vente['nom']; ?>
        </p><p><strong>Adresse :</strong> <?php echo $vente['adresse']; ?>
        
                
        <table border="1">
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Montant</th>
            </tr>
            <?php foreach ($articles as $article): ?>
            <?php $montant = $article['prix'] * $article['qteVendue']; ?>
            <tr>
                <td><?php echo $article['code']; ?></td>
                <td><?php echo $article['nom']; ?></td>
                <td><?php echo formatPrix($article['prix']); ?></td>
                <td><?php echo $article['qteVendue']; ?></td>
                <td><?php echo formatPrix($montant); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" align="right"><strong>TOTAL :</strong></td>
                <td><strong><?php echo formatPrix($total); ?></strong></td>
            </tr>
        </table>

    <?php elseif (isset($_GET['numero'])): ?>
        <p style="color:red;">Aucune vente trouvée avec ce numéro.</p>
    <?php endif; ?>
    
    <p><a href="index.php"> Retour à l'accueil</a></p>
</body>
</html>