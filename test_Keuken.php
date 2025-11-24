<?php
require_once("lib/database.php");
require_once("lib/KeukenType.php");

$db = new database();
$conn = $db->getConnection();

$kt = new KeukenType($conn);

// Test: pak keuken_type met ID 1
$keukenData = $kt->selecteerKeukenType(6);

if ($keukenData) {
    echo "<h4>ID: " . htmlspecialchars($keukenData['id']) . "</h4>";
    echo "<p>Record type: " . htmlspecialchars($keukenData['record_type']) . "</p>";
    echo "<p>Omschrijving: " . htmlspecialchars($keukenData['omschrijving']) . "</p>";
} else {
    echo "<p>Geen keuken type gevonden.</p>";
}
?>
