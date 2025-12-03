<?php
session_start();

require_once("./vendor/autoload.php");
require_once("lib/gerecht.php");
require_once("lib/artikel.php");
require_once("lib/database.php");
require_once("lib/gerechtinfo.php");
require_once("lib/Ingredient.php");
require_once("lib/KeukenType.php");
require_once("lib/Recipe.php");
require_once("lib/user.php");
require_once("lib/boodschappenlijst.php");

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;

// Twig setup
$loader = new FilesystemLoader("./templates");
$twig = new Environment($loader, ["debug" => true]);
$twig->addExtension(new DebugExtension());

$connection = new mysqli("localhost", "root", "", "recepten_db");
if ($connection->connect_error) {
    die("Databaseverbinding mislukt: " . $connection->connect_error);
}

$gerechtRepo = new Gerecht($connection);
$user_id = 1;

// Favoriet toevoegen/verwijderen
if (isset($_GET['favorite']) && isset($_GET['id'])) {
    $gerecht_id = (int)$_GET['id'];
    $infoRepo = new GerechtInfo($connection);

    if ($_GET['favorite'] === 'add') {
        $infoRepo->addFavorite($gerecht_id, $user_id);
    } elseif ($_GET['favorite'] === 'delete') {
        $infoRepo->deleteFavorite($gerecht_id, $user_id);
    }

    // redirect terug naar juiste pagina
    if (isset($_GET['from']) && $_GET['from'] === 'favorieten') {
        header("Location: index.php?page=favorieten");
    } else {
        header("Location: index.php?id=" . $gerecht_id);
    }
    exit;
}

if (isset($_GET['id']) && !isset($_GET['page'])) {
    // Detailpagina
    $gerecht_id = (int)$_GET['id'];
    $gerecht = $gerechtRepo->getFullRecipe($gerecht_id, $user_id);

    echo $twig->render("detail.html.twig", [
        "title" => $gerecht['titel'] ?? "Onbekend gerecht",
        "page_type" => "detail",
        "gerecht" => $gerecht
    ]);


} elseif (isset($_GET['page']) && $_GET['page'] === 'boodschappenlijst') {
    // Boodschappenlijstpagina
    $boodschappen = new Boodschappenlijst($connection);

    // huidige lijst uit sessie
    $lijst = $_SESSION['boodschappenlijst'] ?? [];

// als er een gerecht-id is meegegeven, voeg die toe
if (isset($_GET['id'])) {
    $gerecht_id = (int)$_GET['id'];
    $nieuw = $boodschappen->boodschappenToevoegen([$gerecht_id]); // levert 'nodig', 'inhoudPak', 'prijsUnit' etc.

    foreach ($nieuw as $artikel_id => $item) {
        if (isset($lijst[$artikel_id])) {
            // ruwe benodigde hoeveelheid optellen
            $lijst[$artikel_id]['nodig'] += $item['nodig'];
        } else {
            // neem alle velden over
            $lijst[$artikel_id] = $item;
        }

        // afgeleide velden opnieuw berekenen
        $packs = (int)ceil($lijst[$artikel_id]['nodig'] / $lijst[$artikel_id]['inhoudPak']);
        $lijst[$artikel_id]['aantal'] = $packs; // voor weergave: aantal verpakkingen

        // prijs: kies juiste model (per eenheid of per verpakking)
        // Als prijs per eenheid (g/ml/stuk):
        $lijst[$artikel_id]['prijs_totaal'] = $lijst[$artikel_id]['nodig'] * $lijst[$artikel_id]['prijsUnit'];

        // Als jouw prijs juist per verpakking is, vervang bovenstaande regel door:
        // $lijst[$artikel_id]['prijs_totaal'] = $packs * $lijst[$artikel_id]['prijs'];
    }

    $_SESSION['boodschappenlijst'] = $lijst;
}


    // als er een artikel verwijderd moet worden
    if (isset($_GET['remove'])) {
        $remove_id = (int)$_GET['remove'];
        if (isset($lijst[$remove_id])) {
            unset($lijst[$remove_id]);
        }
        $_SESSION['boodschappenlijst'] = $lijst;
    }

    $totaal = $boodschappen->totalePrijs($lijst);

    echo $twig->render("boodschappenlijst.html.twig", [
        "title" => "Boodschappenlijst",
        "page_type" => "boodschappenlijst",
        "lijst" => $lijst,
        "totaal" => $totaal
    ]);

} elseif (isset($_GET['page']) && $_GET['page'] === 'favorieten') {
    // Favorietenpagina
    $favorieten = $gerechtRepo->selectAllFavorites($user_id);

    echo $twig->render("favorieten.html.twig", [
        "title" => "Favorieten",
        "page_type" => "favorieten",
        "favorieten" => $favorieten
    ]);
}

 else {
    // Homepage
    $alleGerechten = $gerechtRepo->selectAllRecipes();
    usort($alleGerechten, function($a, $b) {
        return strtotime($b['datum_toegevoegd']) <=> strtotime($a['datum_toegevoegd']);
    });
    $nieuwsteVier = array_slice($alleGerechten, 0, 4);

    $gerechten = [];
    foreach ($nieuwsteVier as $g) {
        $gerechten[] = $gerechtRepo->getFullRecipe((int)$g['id'], $user_id);
    }

    echo $twig->render("homepage.html.twig", [
        "title" => "Homepage",
        "page_type" => "homepage",
        "gerechten" => $gerechten
    ]);
}




