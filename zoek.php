<?php
// Database connectie
$dsn = "mysql:host=localhost;dbname=recepten_db;charset=utf8mb4";
$pdo = new PDO($dsn, "root", "");

// Class inladen
require_once __DIR__ . "/classes/Artikel.php";

// Zoekterm ophalen
$naam = $_GET['naam'] ?? '';

// Artikel zoeken via selectArticle
$artikel = Artikel::selectArticle($pdo, $naam);

if ($artikel) {
    echo "<h1>" . $artikel->getNaam() . "</h1>";
    echo "<p>Omschrijving: " . $artikel->getOmschrijving() . "</p>";
    echo "<p>Prijs: €" . number_format($artikel->getPrijs(), 2, ',', '.') . "</p>";
    echo "<p>Eenheid: " . $artikel->getEenheid() . "</p>";
    echo "<p>Verpakking: " . $artikel->getVerpakking() . "</p>";
} else {
    echo "<p>Geen artikel gevonden met naam: " . htmlspecialchars($naam) . "</p>";
}
?>