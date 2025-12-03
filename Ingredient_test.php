
<?php
require_once("./vendor/autoload.php");
require_once("lib/gerecht.php");
require_once("lib/artikel.php");
require_once("lib/database.php");
require_once("lib/gerechtinfo.php");
require_once("lib/Ingredient.php");
require_once("lib/KeukenType.php");
require_once("lib/Recipe.php");
require_once("lib/user.php");


$connection = new mysqli("localhost", "root", "", "recepten_db");
if ($connection->connect_error) {
    die("Databaseverbinding mislukt: " . $connection->connect_error);
}

$gerechtRepo = new Gerecht($connection);
$user_id = 1;

// Kies een test-id van een gerecht dat je weet dat bestaat
$gerecht_id = 1;

$fullRecipe = $gerechtRepo->getFullRecipe($gerecht_id, $user_id);

// Netjes printen
echo "<pre>";
print_r($fullRecipe);
echo "</pre>";
