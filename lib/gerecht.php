<?php
class Gerecht {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // selectRecipe: basisinfo van een gerecht
    public function selectRecipe(int $id): ?array {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM gerecht
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipe = $result->fetch_assoc();
        return $recipe ?: null;
    }

    // selectUser: gebruiker die het gerecht heeft toegevoegd
    public function selectUser(int $gerecht_id): ?array {
        // Haal het gerecht op om de user_id te weten
        $recipe = $this->selectRecipe($gerecht_id);
        if (!$recipe) {
            return null;
        }

        // Gebruik de bestaande methode uit User.php
        $userRepo = new User($this->connection);
        return $userRepo->selecteerUser((int)$recipe['user_id']);
    }

        // selectIngredient: alle ingrediënten van een gerecht
    public function selectIngredient(int $gerecht_id): array {
        $ingredientRepo = new Ingredient($this->connection);
        return $ingredientRepo->selectIngredient($gerecht_id);
    }

// calcCalories: totale calorieën van een gerecht
public function calcCalories(int $gerecht_id): int {
    $ingredients = $this->selectIngredient($gerecht_id);
    $totalKcal = 0;

    foreach ($ingredients as $i) {
        $aantal = (float)$i['aantal'];
        $kcal   = (int)$i['artikel']['Calorieen'];
        $totalKcal += $aantal * $kcal;
    }

    return $totalKcal;
}

// calcPrice: totale prijs van een gerecht
public function calcPrice(int $gerecht_id): float {
    $ingredients = $this->selectIngredient($gerecht_id);
    $totalPrice = 0.0;

    foreach ($ingredients as $i) {
        $aantal = (float)$i['aantal'];
        $prijs  = (float)$i['artikel']['prijs'];
        $totalPrice += $aantal * $prijs;
    }

    return $totalPrice;
}

   

  // selectRating: bereken gemiddelde waardering via GerechtInfo->selectInfo
  public function selectRating(int $gerecht_id): ?float {
    $infoRepo = new GerechtInfo($this->connection);
    $records  = $infoRepo->selectUserRecords($gerecht_id, 'W'); 

    $ratings = array_column($records, 'aantal');

    if (empty($ratings)) {
        return null;
    }

    return array_sum($ratings) / count($ratings);
}


// Stappen (bereiding)
public function selectSteps(int $gerecht_id): array {
    $infoRepo = new GerechtInfo($this->connection);
    return $infoRepo->selectUserRecords($gerecht_id, 'B'); 
}


    // methode opmerkingen ophalen
public function selectRemarks(int $gerecht_id): array {
    $infoRepo = new GerechtInfo($this->connection);
    $records  = $infoRepo->selectInfo($gerecht_id);

    $remarks = [];
    foreach ($records as $record) {
        if ($record['record_type'] === 'O') {
            $remarks[] = $record;
        }
    }
    return $remarks;
}


// methode keuken
public function selectKitchen(int $gerecht_id): ?array {
    $recipe = $this->selectRecipe($gerecht_id);
    if (!$recipe || empty($recipe['keuken_id'])) {
        return null;
    }

    $keukenTypeRepo = new KeukenType($this->connection);
    return $keukenTypeRepo->selecteerKeukenType((int)$recipe['keuken_id']);
}

//methode type
public function selectType(int $gerecht_id): ?array {
    $recipe = $this->selectRecipe($gerecht_id);
    if (!$recipe || empty($recipe['type_id'])) {
        return null;
    }

    $keukenTypeRepo = new KeukenType($this->connection);
    return $keukenTypeRepo->selecteerKeukenType((int)$recipe['type_id']);
}

//methode detime Favorite
public function determineFavorite(int $gerecht_id, int $user_id): bool {
    $infoRepo = new GerechtInfo($this->connection);
    $records  = $infoRepo->selectInfo($gerecht_id);

    foreach ($records as $record) {
        if (isset($record['record_type'], $record['user_id']) &&
            trim($record['record_type']) === 'F' &&
            (int)$record['user_id'] === (int)$user_id) {
            return true;
        }
    }
    return false;
}

    // ✅ Nieuwe functie: alles in één array teruggeven
    public function getFullRecipe(int $gerecht_id, int $user_id): array {
        $recipe = $this->selectRecipe($gerecht_id);

        if (!$recipe) {
            return [];
        }

       return [
        'id'              => $recipe['id'],
        'titel'           => $recipe['titel'],
        'korte_omschrijving' => $recipe['korte_omschrijving'],
        'lange_omschrijving' => $recipe['lange_omschrijving'],
        'afbeelding'      => $recipe['afbeelding'],
        'datum_toegevoegd'=> $recipe['datum_toegevoegd'],

        // vervang IDs door sub‑arrays
        'user'   => $this->selectUser($gerecht_id),
        'kitchen'=> $this->selectKitchen($gerecht_id),
        'type'   => $this->selectType($gerecht_id),

        // extra info
        'ingredients' => $this->selectIngredient($gerecht_id),
        'calories'    => $this->calcCalories($gerecht_id),
        'price'       => $this->calcPrice($gerecht_id),
        'rating'      => $this->selectRating($gerecht_id),
        'steps'       => $this->selectSteps($gerecht_id),
        'remarks'     => $this->selectRemarks($gerecht_id),
        'favorite'    => $this->determineFavorite($gerecht_id, $user_id)
    ];
}

}

?>


