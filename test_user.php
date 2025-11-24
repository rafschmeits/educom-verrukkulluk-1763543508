<?php
require_once("lib/database.php");
require_once("lib/User.php");

$db = new database();
$conn = $db->getConnection();

$user = new User($conn);

// Test: pak user met ID 1
$userData = $user->selecteerUser(2);

if ($userData) {
    echo "<h2>" . htmlspecialchars($userData['user_name']) . "</h2>";
    echo "<p>Email: " . htmlspecialchars($userData['email']) . "</p>";
    echo "<p>Afbeelding: " . htmlspecialchars($userData['afbeelding']) . "</p>";
} else {
    echo "<p>Geen user gevonden.</p>";
}
?>
