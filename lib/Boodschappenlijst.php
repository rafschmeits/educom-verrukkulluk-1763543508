<?php
class Boodschappenlijst {
    private $gerechtRepo;

    public function __construct($connection) {
        $this->gerechtRepo = new Gerecht($connection);
    }

    // Bouw lijst: eerst ruwe 'nodig' per artikel optellen
    public function boodschappenToevoegen(array $gerecht_ids): array {
        $lijst = [];

        foreach ($gerecht_ids as $gerecht_id) {
            $ingredienten = $this->gerechtRepo->selectIngredient($gerecht_id);

            foreach ($ingredienten as $i) {
                $artikel_id = (int)$i['artikel']['id'];
                $nodig      = (float)$i['aantal'];                         // benodigde hoeveelheid (in eenheid, bv g)
                $prijsUnit  = (float)$i['artikel']['prijs'];               // prijs per eenheid (bv per g/ml/stuk)
                $naam       = $i['artikel']['naam'];
                $eenheid    = $i['artikel']['eenheid'];                    // g / ml / st
                $verpakking = $i['artikel']['verpakking'];                 // pak / fles / doos
                $afbeelding = $i['artikel']['afbeelding'];
                $inhoudPak  = (float)$i['artikel']['Hoeveelheid_verpakking']; // hoeveelheid per verpakking (bv 500 g)

                if (isset($lijst[$artikel_id])) {
                    $lijst[$artikel_id]['nodig'] += $nodig;
                } else {
                    $lijst[$artikel_id] = [
                        'artikel_id' => $artikel_id,
                        'naam'       => $naam,
                        'nodig'      => $nodig,       // totale benodigde hoeveelheid (in eenheid)
                        'prijsUnit'  => $prijsUnit,   // prijs per eenheid
                        'eenheid'    => $eenheid,
                        'verpakking' => $verpakking,
                        'afbeelding' => $afbeelding,
                        'inhoudPak'  => $inhoudPak
                    ];
                }
            }
        }

        // Afgeleide velden: aantal verpakkingen + totale prijs (per eenheid)
        foreach ($lijst as $aid => $item) {
            $packs = (int)ceil($item['nodig'] / $item['inhoudPak']);
            $lijst[$aid]['aantal'] = $packs; // aantal verpakkingen voor weergave
            $lijst[$aid]['prijs_totaal'] = $item['nodig'] * $item['prijsUnit']; // prijs per eenheid × totaal nodig
        }

        return $lijst;
    }

    public function totalePrijs(array $lijst): float {
        $totaal = 0.0;
        foreach ($lijst as $item) {
            // prijs is per eenheid; tel totale benodigde hoeveelheid af
            $totaal += $item['prijs_totaal'];
        }
        return $totaal;
    }
}
