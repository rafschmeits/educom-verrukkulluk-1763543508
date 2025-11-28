<?php
require_once("lib/database.php");
require_once("lib/Gerecht.php");
require_once("lib/User.php");
require_once("lib/Ingredient.php");
require_once("lib/Artikel.php");
require_once("lib/GerechtInfo.php"); 
require_once("lib/KeukenType.php");

$db = new Database();
$conn = $db->getConnection();

$gerechtRepo = new Gerecht($conn);

$gerecht_id = 1; // pas aan naar een bestaand gerecht_id
$user_id    = 1; // testgebruiker

$recipe      = $gerechtRepo->selectRecipe($gerecht_id);
$user        = $gerechtRepo->selectUser($gerecht_id);
$ingredients = $gerechtRepo->selectIngredient($gerecht_id);
$avgRating   = $gerechtRepo->selectRating($gerecht_id);
$steps       = $gerechtRepo->selectSteps($gerecht_id);
$remarks     = $gerechtRepo->selectRemarks($gerecht_id);
$kitchen     = $gerechtRepo->selectKitchen($gerecht_id);
$type        = $gerechtRepo->selectType($gerecht_id);
$isFavorite  = $gerechtRepo->determineFavorite($gerecht_id, $user_id);

echo "<h1>Test Gerecht</h1>";

echo "<h2>Basisinfo</h2>";
echo "Titel: " . htmlspecialchars($recipe['titel']) . "<br>";
echo "Omschrijving: " . htmlspecialchars($recipe['korte_omschrijving']) . "<br>";

echo "<h2>Toegevoegd door</h2>";
echo htmlspecialchars($user['user_name']) . " (" . htmlspecialchars($user['email']) . ")<br>";

echo "<h2>Ingrediënten</h2><ul>";
foreach ($ingredients as $i) {
    echo "<li>" . htmlspecialchars($i['artikel']['naam']) . 
         " – " . htmlspecialchars($i['aantal']) . 
         " × €" . number_format((float)$i['artikel']['prijs'], 2, ',', '.') . "</li>";
}
echo "</ul>";

echo "<h2>Berekeningen</h2>";
echo "Totale prijs: €" . number_format($gerechtRepo->calcPrice($gerecht_id), 2, ',', '.') . "<br>";
echo "Totale calorieën: " . $gerechtRepo->calcCalories($gerecht_id) . " kcal<br>";

echo "<h2>Gemiddelde waardering</h2>";
if ($avgRating !== null) {
    echo "Gemiddelde waardering: " . number_format($avgRating, 1, ',', '.') . " / 5<br>";
} else {
    echo "Dit gerecht heeft nog geen waarderingen.<br>";
}

echo "<h2>Bereidingsstappen</h2><ol>";
foreach ($steps as $step) {
    echo "<li>Stap " . htmlspecialchars($step['stap']) . ": " . htmlspecialchars($step['tekstveld']) . "</li>";
}
echo "</ol>";

echo "<h2>Opmerkingen</h2><ul>";
foreach ($remarks as $remark) {
    echo "<li>" . htmlspecialchars($remark['user']['user_name']) . ": " 
       . htmlspecialchars($remark['tekstveld']) 
       . " (" . htmlspecialchars($remark['datum']) . ")</li>";
}
echo "</ul>";

echo "<h2>Keuken & Type</h2>";
if ($kitchen) {
    echo "Keuken: " . htmlspecialchars($kitchen['omschrijving']) . "<br>";
}
if ($type) {
    echo "Type: " . htmlspecialchars($type['omschrijving']) . "<br>";
}

echo "<h2>Favoriet?</h2>";
echo $isFavorite 
    ? "Dit gerecht staat bij gebruiker $user_id in de favorieten.<br>" 
    : "Dit gerecht is (nog) geen favoriet.<br>";
?>
