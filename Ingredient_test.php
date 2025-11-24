<?php
require_once("lib/database.php");
require_once("lib/Ingredient.php");

$db = new database();
$conn = $db->getConnection();

$ing = new Ingredient($conn);

// Test: haal alle ingrediënten van gerecht 1
$ingredients = $ing->selectIngredient(1);
echo "<h2>Ingrediënten voor gerecht 1</h2>";
if ($ingredients) {
    foreach ($ingredients as $i) {
        echo "<p>Ingredient ID: " . htmlspecialchars($i['id']) . "</p>";
        echo "<p>Gerecht ID: " . htmlspecialchars($i['gerecht_id']) . "</p>";   // ✅ toegevoegd
        echo "<p>Artikel ID: " . htmlspecialchars($i['artikel_id']) . "</p>";
        echo "<p>Artikel naam: " . htmlspecialchars($i['artikel_naam']) . "</p>";
        echo "<p>Aantal: " . htmlspecialchars($i['aantal']) . "</p>";
        echo "<hr>";
    }
} else {
    echo "<p>Geen ingrediënten gevonden.</p>";
}

// Test: haal artikel-info direct op
$article = $ing->selectArticle(2); // voorbeeld artikel_id
echo "<h2>Artikel info</h2>";
if ($article) {
    echo "<p>ID: " . htmlspecialchars($article['id']) . "</p>";
    echo "<p>Naam: " . htmlspecialchars($article['naam']) . "</p>";
    echo "<p>Omschrijving: " . htmlspecialchars($article['omschrijving']) . "</p>";
} else {
    echo "<p>Geen artikel gevonden.</p>";
}
?>
