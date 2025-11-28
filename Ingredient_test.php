<?php 
require_once("lib/database.php");
require_once("lib/Gerecht.php");
require_once("lib/Boodschappenlijst.php");
require_once("lib/Ingredient.php");
require_once("lib/artikel.php");


$db = new Database();
$conn = $db->getConnection();

$boodschappenRepo = new Boodschappenlijst($conn);
$lijst = $boodschappenRepo->maakLijst([1, 2 ,3, 4]);


echo "<h2>Boodschappenlijst</h2><ul>";
foreach ($lijst as $item) {
    echo "<li>" 
       . htmlspecialchars($item['naam']) . " – " 
       . htmlspecialchars($item['hoeveelheid']) . " " 
       . htmlspecialchars($item['eenheid']) 
       . " (" . htmlspecialchars($item['verpakking']) . ") × €" 
       . number_format($item['prijs'], 2, ',', '.') 
       . "</li>";
}
echo "</ul>";


$totaal = $boodschappenRepo->totalePrijs($lijst);
echo "<p><strong>Totaal:</strong> €" . number_format($totaal, 2, ',', '.') . "</p>";
