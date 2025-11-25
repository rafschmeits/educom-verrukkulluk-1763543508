<?php
require_once("lib/database.php");
require_once("lib/Ingredient.php");
require_once("lib/Artikel.php");

// Maak databaseverbinding
$db = new Database();
$conn = $db->getConnection();

// Maak object van Ingredient
$ingredientRepo = new Ingredient($conn);

// Test: haal alle ingrediënten van gerecht met ID 1
$ingrediënten = $ingredientRepo->selectIngredient(2);

// Toon resultaat
echo "<h2>Test Ingrediënten + Artikelen</h2>";
echo "<pre>";
print_r($ingrediënten);
echo "</pre>";
?>
