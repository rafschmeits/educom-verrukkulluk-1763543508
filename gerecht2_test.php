<?php
require_once("lib/database.php");
require_once("lib/Gerecht.php");

$db = new database();
$conn = $db->getConnection();

$gr = new Gerecht($conn);

$gerecht_id = 2; // voorbeeld

// Basisinfo
$recipe = $gr->selectRecipe($gerecht_id);
echo "<h2>Gerecht</h2>";
echo "<p>ID: " . htmlspecialchars($recipe['id']) . "</p>";
echo "<p>Titel: " . htmlspecialchars($recipe['titel']) . "</p>";

// User
$user = $gr->selectUser($gerecht_id);
echo "<h2>Toegevoegd door</h2>";
echo "<p>User: " . htmlspecialchars($user['user_name']) . "</p>";

// Ingrediënten
$ingredients = $gr->selectIngredient($gerecht_id);
echo "<h2>Ingrediënten</h2>";
foreach ($ingredients as $i) {
    echo "<p>" . htmlspecialchars($i['artikel_naam']) . " — "
       . htmlspecialchars($i['aantal']) . " stuks (€"
       . number_format((float)$i['prijs'], 2, ',', '.') . " per stuk)</p>";
}

// Totale prijs
echo "<h2>Berekeningen</h2>";
echo "<p>Totale prijs: €" . number_format($gr->calcPrice($gerecht_id), 2, ',', '.') . "</p>";

// Waarderingen
$ratings = $gr->selectRating($gerecht_id);
echo "<h2>Waarderingen</h2>";
foreach ($ratings as $r) {
    echo "<p>User " . htmlspecialchars($r['user_id']) . " gaf " . htmlspecialchars($r['aantal']) . " sterren.</p>";
}

// Stappen
$steps = $gr->selectSteps($gerecht_id);
echo "<h2>Stappen</h2>";
foreach ($steps as $s) {
    echo "<p>Stap " . htmlspecialchars($s['stap']) . ": " . htmlspecialchars($s['tekstveld']) . "</p>";
}

// Opmerkingen
$remarks = $gr->selectRemarks($gerecht_id);
echo "<h2>Opmerkingen</h2>";
foreach ($remarks as $o) {
    echo "<p>User " . htmlspecialchars($o['user_id']) . ": " . htmlspecialchars($o['tekstveld']) . "</p>";
}

// Keuken
$kitchen = $gr->selectKitchen($gerecht_id);
echo "<h2>Keuken</h2>";
echo "<p>" . htmlspecialchars($kitchen['omschrijving']) . "</p>";

// Type (uit keuken_type)
$type = $gr->selectType($gerecht_id);
echo "<h2>Type</h2>";
echo "<p>" . htmlspecialchars($type['omschrijving']) . "</p>";

// Favoriet check
$isFav = $gr->determineFavorite($gerecht_id, 1);
echo "<h2>Favoriet?</h2>";
echo $isFav ? "<p>Ja</p>" : "<p>Nee</p>";

// Totale prijs en calorieën
echo "<h2>Berekeningen</h2>";
echo "<p>Totale prijs: €" . number_format($gr->calcPrice($gerecht_id), 2, ',', '.') . "</p>";
echo "<p>Totale calorieën: " . $gr->calcCalories($gerecht_id) . " kcal</p>";

?>
