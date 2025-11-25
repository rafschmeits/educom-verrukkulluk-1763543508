<?php
class Ingredient {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Haalt ingredient op 
    public function selectIngredient(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT id, gerecht_id, artikel_id, aantal
            FROM ingredient
            WHERE gerecht_id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
