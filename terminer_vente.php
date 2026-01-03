
<?php
require_once 'config.php';

// Vérifier si le panier n'est pas vide
if (empty($_SESSION['panier'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Terminer la vente</title>
</head>
<body>
    <h1>Terminer la vente</h1>
    
    <!-- Récapitulatif -->
    <h2>Récapitulatif de la commande</h2>
    <table border="1">
        <tr>
            <th>Code</th>
            <th>Nom</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Montant</th>
        </tr>
        <?php 
        $total_articles = 0;
        $total_montant = 0;
        foreach ($_SESSION['panier'] as $item):
            $montant = $item['prix'] * $item['quantite'];
            $total_articles += $item['quantite'];
            $total_montant += $montant;
        ?>
        <tr>
            <td><?php echo $item['code']; ?></td>
            <td><?php echo $item['nom']; ?></td>
            <td><?php echo formatPrix($item['prix']); ?></td>
            <td><?php echo $item['quantite']; ?></td>
            <td><?php echo formatPrix($montant); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" align="right"><strong>TOTAL :</strong></td>
            <td><strong><?php echo formatPrix($total_montant); ?></strong></td>
        </tr>
    </table>
    
    <!-- Formulaire des informations -->
    <h2>Infos sur la vente</h2>
    <form method="POST" action="enregistrer_vente.php">
        
            
                <label for=""><strong>Date</strong></label>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required><br><br>
                <label for=""><strong>Nom</strong></label>
                <input type="text" name="nom" required><br><br>
                <label for=""><strong>Adresse</strong></label>
                <input type="text" name="adresse" required>
            
                <input type="submit" value="Valider">
                
            
    </form>
    
    <p><a href="ajouter_vente.php">Ajouter plus d'articles</a></p>
</body>
</html>