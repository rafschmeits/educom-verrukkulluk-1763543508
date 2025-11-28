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
        $records  = $infoRepo->selectInfo($gerecht_id); 

        $ratings = [];
        foreach ($records as $record) {
            if ($record['record_type'] === 'W' && isset($record['aantal'])) {
                $ratings[] = (float)$record['aantal'];
            }
        }

        if (empty($ratings)) {
            return null; 
        }

        return array_sum($ratings) / count($ratings); 
    }

       // selectSteps: haalt ALLE stappen (record_type = 'B') van een gerecht op
    public function selectSteps(int $gerecht_id): array {
        $infoRepo = new GerechtInfo($this->connection);
        $records  = $infoRepo->selectInfo($gerecht_id); 

        $steps = [];
        foreach ($records as $record) {
            if (isset($record['record_type']) && $record['record_type'] === 'B') {
                $steps[] = $record;
            }
        }

        return $steps; 
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

}
?>

