<?php 
require_once("lib/database.php");
require_once("lib/Gerecht.php");
require_once("lib/Boodschappenlijst.php");
require_once("lib/Ingredient.php");
require_once("lib/artikel.php");

$db = new Database();
$conn = $db->getConnection();

$boodschappenRepo = new Boodschappenlijst($conn);

// Stel: gebruiker kiest gerechten 1 en 2
$lijst = $boodschappenRepo->boodschappenToevoegen([1, 2, 3]);

echo "<h2>Boodschappenlijst</h2><ul>";
foreach ($lijst as $item) {
    echo "<li>" . htmlspecialchars($item['naam']) . " – " 
         . htmlspecialchars($item['aantal']) . " " 
         . htmlspecialchars($item['eenheid']) . " (" 
         . htmlspecialchars($item['verpakking']) . ") × €" 
         . number_format($item['prijs'], 2, ',', '.') . "</li>";
}
echo "</ul>";

$totaal = $boodschappenRepo->totalePrijs($lijst);
echo "<p><strong>Totaal:</strong> €" . number_format($totaal, 2, ',', '.') . "</p>";
?>
