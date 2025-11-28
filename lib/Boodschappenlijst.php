<?php
class Boodschappenlijst {
    private $connection;
    private $gerechtRepo;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->gerechtRepo = new Gerecht($connection);
    }

    // Genereer boodschappenlijst op basis van geselecteerde gerechten
public function maakLijst(array $gerecht_ids): array {
    $lijst = [];

    foreach ($gerecht_ids as $gerecht_id) {
        // haal ingrediënten van dit gerecht op
        $ingredienten = $this->gerechtRepo->selectIngredient($gerecht_id);

        // check of er echt iets terugkomt
        if (!is_array($ingredienten)) {
            continue; // sla over als er niks is
        }

        foreach ($ingredienten as $i) {
            $artikel_id = $i['artikel']['id'];
            $naam       = $i['artikel']['naam'];
            $aantal     = (float)$i['aantal'];
            $prijs      = (float)$i['artikel']['prijs'];
            $eenheid    = $i['artikel']['eenheid'];
            $verpakking = $i['artikel']['verpakking'];

            if (isset($lijst[$artikel_id])) {
                $lijst[$artikel_id]['hoeveelheid'] += $aantal;
            } else {
                $lijst[$artikel_id] = [
                    'artikel_id' => $artikel_id,
                    'naam'       => $naam,
                    'hoeveelheid'=> $aantal,
                    'prijs'      => $prijs,
                    'eenheid'    => $eenheid,
                    'verpakking' => $verpakking
                ];
            }
        }
    }

    return $lijst;
}

//totalen prijs
public function totalePrijs(array $lijst): float {
    $totaal = 0.0;
    foreach ($lijst as $item) {
        $totaal += $item['hoeveelheid'] * $item['prijs'];
    }
    return $totaal;
}


}
?>
