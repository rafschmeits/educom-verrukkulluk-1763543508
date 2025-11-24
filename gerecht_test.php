<?php
require_once("lib/database.php");
require_once("lib/GerechtInfo.php");

$db = new database();
$conn = $db->getConnection();

$gi = new GerechtInfo($conn);

// Haal het ID uit de URL, bv. test_gerechtinfo.php?id=1
$gerecht_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($gerecht_id > 0) {
    echo "<h1>Alle info voor gerecht ID $gerecht_id</h1>";

    // Basisinformatie + alle records
    $infoRecords = $gi->selectInfo($gerecht_id);
    if ($infoRecords) {
      foreach ($infoRecords as $row) {
    echo "<h3>Record #" . htmlspecialchars($row['id']) . "</h3>";
    echo "<p>Type: " . htmlspecialchars($row['record_type']) . "</p>";
    if (!empty($row['stap'])) {
        echo "<p>Stap: " . htmlspecialchars($row['stap']) . "</p>";
    }
    if (!empty($row['tekstveld'])) {
        echo "<p>Tekst: " . htmlspecialchars($row['tekstveld']) . "</p>";
    }
    if (!is_null($row['aantal'])) {
        echo "<p>Aantal: " . htmlspecialchars($row['aantal']) . "</p>";
    }
    echo "<p>User: " . htmlspecialchars($row['user_id']) . "</p>";
    echo "<p>Datum: " . htmlspecialchars($row['datum']) . "</p>";
    echo "<hr>";
}

    } else {
        echo "<p>Geen records gevonden.</p>";
    }

    // Opmerkingen apart
    $opmerkingen = $gi->selectUserRecords($gerecht_id, 'O');
    echo "<h2>Opmerkingen</h2>";
    if ($opmerkingen) {
        foreach ($opmerkingen as $o) {
            echo "<p>User " . htmlspecialchars($o['user_id']) . ": " 
                 . htmlspecialchars($o['tekstveld']) . " (" 
                 . htmlspecialchars($o['datum']) . ")</p>";
        }
    } else {
        echo "<p>Geen opmerkingen gevonden.</p>";
    }

    // Favorieten apart
    $favorieten = $gi->selectUserRecords($gerecht_id, 'F');
    echo "<h2>Favorieten</h2>";
    if ($favorieten) {
        foreach ($favorieten as $f) {
            echo "<p>User " . htmlspecialchars($f['user_id']) . " heeft dit gerecht als favoriet.</p>";
        }
    } else {
        echo "<p>Geen favorieten gevonden.</p>";
    }

} else {
    echo "<p>Geen geldig ID opgegeven. Gebruik bijvoorbeeld: test_gerechtinfo.php?id=1</p>";
}
?>

