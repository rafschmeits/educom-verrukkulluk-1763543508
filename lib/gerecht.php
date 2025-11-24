<?php
class Gerecht {
    private $connection;

    // Constructor
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
        $stmt = $this->connection->prepare("
            SELECT u.*
            FROM user u
            INNER JOIN gerecht g ON g.user_id = u.id
            WHERE g.id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // selectIngredient: alle ingrediënten van een gerecht (met artikelnaam, prijs en calorieën)
    public function selectIngredient(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT i.id, i.gerecht_id, i.artikel_id, i.aantal,
                   a.naam AS artikel_naam, a.prijs, a.calorieen
            FROM ingredient i
            INNER JOIN artikel a ON i.artikel_id = a.id
            WHERE i.gerecht_id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // calcPrice: totale prijs van een gerecht
    public function calcPrice(int $gerecht_id): float {
        $ingredients = $this->selectIngredient($gerecht_id);
        $total = 0.0;
        foreach ($ingredients as $i) {
            $total += (float)$i['prijs'] * (float)$i['aantal'];
        }
        return $total;
    }

    // calcCalories: totale calorieën van een gerecht
    public function calcCalories(int $gerecht_id): int {
        $ingredients = $this->selectIngredient($gerecht_id);
        $total = 0;
        foreach ($ingredients as $i) {
            $total += (float)$i['calorieen'] * (float)$i['aantal'];
        }
        return (int)$total;
    }

    // selectRating: alle waarderingen (record_type 'W')
    public function selectRating(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM gerecht_info
            WHERE gerecht_id = ? AND record_type = 'W'
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // selectSteps: alle stappen (record_type 'B'), gesorteerd op stap
    public function selectSteps(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM gerecht_info
            WHERE gerecht_id = ? AND record_type = 'B'
            ORDER BY stap ASC
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // selectRemarks: opmerkingen (record_type 'O'), gesorteerd op datum
    public function selectRemarks(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT *
            FROM gerecht_info
            WHERE gerecht_id = ? AND record_type = 'O'
            ORDER BY datum ASC
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // selectKitchen: keuken van een gerecht uit keuken_type via g.keuken_id
    public function selectKitchen(int $gerecht_id): ?array {
        $stmt = $this->connection->prepare("
            SELECT k.*
            FROM keuken_type k
            INNER JOIN gerecht g ON g.keuken_id = k.id
            WHERE g.id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // selectType: type van een gerecht uit keuken_type via g.type_id
    public function selectType(int $gerecht_id): ?array {
        $stmt = $this->connection->prepare("
            SELECT k.*
            FROM keuken_type k
            INNER JOIN gerecht g ON g.type_id = k.id
            WHERE g.id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // determineFavorite: is dit gerecht favoriet voor een user? (record_type 'F')
    public function determineFavorite(int $gerecht_id, int $user_id): bool {
        $stmt = $this->connection->prepare("
            SELECT 1
            FROM gerecht_info
            WHERE gerecht_id = ? AND user_id = ? AND record_type = 'F'
            LIMIT 1
        ");
        $stmt->bind_param("ii", $gerecht_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>

