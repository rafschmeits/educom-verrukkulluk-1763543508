<?php
require_once("lib/database.php");
require_once("lib/Recipe.php");

$db = new Database();
$conn = $db->getConnection();
$recipeRepo = new Recipe($conn);

// Vraag specifiek gerechten 1 en 4 op
$recipes = $recipeRepo->selectRecipes([1, 3, 4]);

echo "<h2>Titels van geselecteerde gerechten</h2><ul>";
foreach ($recipes as $r) {
    echo "<li>" . htmlspecialchars($r['titel']) . "</li>";
}
echo "</ul>";
?>
