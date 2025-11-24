<?php
require_once("lib/database.php");
require_once("lib/artikel.php");

$db = new database();
$conn = $db->getConnection();

$artikel = new Artikel($conn);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$artikelData = null;

if ($id > 0) {
    $artikelData = $artikel->selecteerArtikel($id);
}

if ($artikelData) {
    echo "<h2>" . htmlspecialchars($artikelData['naam']) . "</h2>";
    echo "<p>Omschrijving: " . htmlspecialchars($artikelData['omschrijving']) . "</p>";
    echo "<p>Prijs: €" . htmlspecialchars($artikelData['prijs']) . "</p>";
    echo "<p>Eenheid: " . htmlspecialchars($artikelData['eenheid']) . "</p>";
    echo "<p>Verpakking: " . htmlspecialchars($artikelData['verpakking']) . "</p>";
} else {
    echo "<p>Geen artikel gevonden.</p>";
}
?>
