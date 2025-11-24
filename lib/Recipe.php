<?php
class Recipe {
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Selecteer één gerecht op ID
    public function selectRecipe(int $id): ?array {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM gerecht 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // Selecteer alle gerechten
    public function selectAllRecipes(): array {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM gerecht
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Selecteer alle gerechten van een specifieke gebruiker
    public function selectRecipesByUser(int $user_id): array {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM gerecht 
            WHERE user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
