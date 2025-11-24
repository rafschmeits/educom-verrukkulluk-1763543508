<?php
require_once("lib/database.php");
require_once("lib/Gerecht.php");

$db = new database();
$conn = $db->getConnection();

$gr = new Gerecht($conn);

// 👇 Stel: gebruiker heeft gerechten 1 en 2 geselecteerd
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

// ✅ Output boodschappenlijst
echo "<h2>Boodschappenlijst voor geselecteerde gerechten</h2>";
$totaalPrijs = 0;
$totaalCalorieen = 0;

foreach ($boodschappen as $artikel => $data) {
    $subtotaal = $data['prijs'] * $data['aantal'];
    $subcal = $data['calorieen'] * $data['aantal'];

    echo "<p><strong>" . htmlspecialchars($artikel) . "</strong> — "
       . htmlspecialchars($data['aantal']) . " eenheden "
       . "(€" . number_format($data['prijs'], 2, ',', '.') . " per eenheid, "
       . $data['calorieen'] . " kcal per eenheid) → "
       . "<em>Totaal: €" . number_format($subtotaal, 2, ',', '.') . ", "
       . $subcal . " kcal</em></p>";

    $totaalPrijs += $subtotaal;
    $totaalCalorieen += $subcal;
}

echo "<h3>Totale prijs: €" . number_format($totaalPrijs, 2, ',', '.') . "</h3>";
echo "<h3>Totale calorieën: " . $totaalCalorieen . " kcal</h3>";
?>
