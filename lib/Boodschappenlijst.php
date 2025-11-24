<?php
require_once("lib/database.php");
require_once("lib/Gerecht.php");

$db = new database();
$conn = $db->getConnection();
$gr = new Gerecht($conn);

// Stel: gebruiker heeft gerechten 1 en 2 geselecteerd
$geselecteerdeGerechten = [1, 2];

$boodschappen = [];

foreach ($geselecteerdeGerechten as $gerecht_id) {
    $ingredienten = $gr->selectIngredient($gerecht_id);

    foreach ($ingredienten as $i) {
        $artikel = $i['artikel_naam'];
        $aantal = $i['aantal'];
        $prijs = $i['prijs'];
        $calorieen = $i['calorieen'];

        if (isset($boodschappen[$artikel])) {
            $boodschappen[$artikel]['aantal'] += $aantal;
        } else {
            $boodschappen[$artikel] = [
                'aantal' => $aantal,
                'prijs' => $prijs,
                'calorieen' => $calorieen
            ];
        }
    }
}

return $boodschappen;
