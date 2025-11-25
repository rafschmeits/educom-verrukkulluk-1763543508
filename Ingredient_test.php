<?php
require_once("lib/database.php");
require_once("lib/Ingredient.php");
require_once("lib/Artikel.php");

// Maak databaseverbinding
$db = new Database();
$conn = $db->getConnection();

// Maak objecten
$ingredientRepo = new Ingredient($conn);
$artikelRepo = new Artikel($conn);

// Test: haal alle ingrediënten van gerecht met ID 1
$ingrediënten = $ingredientRepo->selectIngredient(1);

// Voeg artikelgegevens toe via Artikel-class
foreach ($ingrediënten as &$i) {
    $i['artikel'] = $artikelRepo->selecteerArtikel((int)$i['artikel_id']);
}

// Toon resultaat
echo "<h2>Test Ingrediënten + Artikelen</h2>";
echo "<pre>";
print_r($ingrediënten);
echo "</pre>";
?>
