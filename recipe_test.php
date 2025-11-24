<?php
require_once("lib/database.php");
require_once("lib/Recipe.php");

$db = new database();
$conn = $db->getConnection();

$recipeClass = new Recipe($conn);

// Voorbeeld: één gerecht ophalen
$gerecht_id = 1;
$recipe = $recipeClass->selectRecipe($gerecht_id);
echo "<h2>Één gerecht</h2>";
if ($recipe) {
    echo "<p>ID: " . htmlspecialchars($recipe['id']) . "</p>";
    echo "<p>Titel: " . htmlspecialchars($recipe['titel']) . "</p>";
} else {
    echo "<p>Geen gerecht gevonden.</p>";
}

// Voorbeeld: alle gerechten ophalen
$allRecipes = $recipeClass->selectAllRecipes();
echo "<h2>Alle gerechten</h2>";
foreach ($allRecipes as $r) {
    echo "<p>ID: " . htmlspecialchars($r['id']) . " — Titel: " . htmlspecialchars($r['titel']) . "</p>";
}

// Voorbeeld: gerechten van een specifieke gebruiker
$user_id = 1; // voorbeeld
$userRecipes = $recipeClass->selectRecipesByUser($user_id);
echo "<h2>Gerechten van gebruiker $user_id</h2>";
foreach ($userRecipes as $ur) {
    echo "<p>ID: " . htmlspecialchars($ur['id']) . " — Titel: " . htmlspecialchars($ur['titel']) . "</p>";
}
?>
