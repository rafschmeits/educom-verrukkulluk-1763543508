<?php
class Boodschappenlijst {
    private $gerechtRepo;

    public function __construct($connection) {
        $this->gerechtRepo = new Gerecht($connection);
    }

    // Functie 1: boodschappenToevoegen
    public function boodschappenToevoegen(array $gerecht_ids): array {
        $lijst = [];

        foreach ($gerecht_ids as $gerecht_id) {
            $ingredienten = $this->gerechtRepo->selectIngredient($gerecht_id);

            foreach ($ingredienten as $i) {
                $artikel_id = $i['artikel']['id'];
                $aantal     = (float)$i['aantal'];
                $prijs      = (float)$i['artikel']['prijs'];
                $naam       = $i['artikel']['naam'];
                $eenheid    = $i['artikel']['eenheid'];
                $verpakking = $i['artikel']['verpakking'];

                // gebruik aparte functie ArtikelOpLijst
                $bestaand = $this->artikelOpLijst($lijst, $artikel_id);

                if ($bestaand) {
                    $lijst[$artikel_id]['aantal'] += $aantal;
                } else {
                    $lijst[$artikel_id] = [
                        'artikel_id' => $artikel_id,
                        'naam'       => $naam,
                        'aantal'     => $aantal,
                        'prijs'      => $prijs,
                        'eenheid'    => $eenheid,
                        'verpakking' => $verpakking
                    ];
                }
            }
        }

        return $lijst;
    }

    // Functie 2: ArtikelOpLijst
    private function artikelOpLijst(array $lijst, int $artikel_id): bool {
        return isset($lijst[$artikel_id]);
    }

    // Extra: totale prijs
    public function totalePrijs(array $lijst): float {
        $totaal = 0.0;
        foreach ($lijst as $item) {
            $totaal += $item['aantal'] * $item['prijs'];
        }
        return $totaal;
    }
}

