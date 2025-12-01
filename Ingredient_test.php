<?php 
require_once("lib/database.php");
require_once("lib/Gerecht2_test.php");
require_once("lib/Boodschappenlijst.php");
require_once("lib/Ingredient.php");
require_once("lib/artikel.php");
require_once("lib/user.php");
require_once("lib/gerechtinfo.php"); 
require_once("lib/KeukenType.php");

$db = new Database();
$conn = $db->getConnection();

$gerechtRepo = new Gerecht($conn);
$full = $gerechtRepo->getFullRecipe(2, 1);

echo "<pre>";
print_r($full);
echo "</pre>";
