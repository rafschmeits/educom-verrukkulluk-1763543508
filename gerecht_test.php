<?php
require_once("lib/database.php");
require_once("lib/User.php");
require_once("lib/GerechtInfo.php");

// Maak databaseverbinding
$db = new Database();
$conn = $db->getConnection();

// Maak objecten
$gerechtInfo = new GerechtInfo($conn);

// Test: haal alle info van gerecht met ID 1
$records = $gerechtInfo->selectInfo(1);

// Toon resultaat
echo "<h2>Test GerechtInfo</h2>";
echo "<pre>";
print_r($records);
echo "</pre>";
?>

