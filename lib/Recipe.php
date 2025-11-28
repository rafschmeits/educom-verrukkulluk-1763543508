<?php
class Recipe {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Haal meerdere gerechten op via een lijst van IDs
    public function selectRecipes(array $ids): array {
        if (empty($ids)) {
            return [];
        }

        // Maak een dynamische placeholder string (?, ?, ?)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM gerecht 
            WHERE id IN ($placeholders)
        ");

        // Bind alle IDs als integers
        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);

        $stmt->execute();
        $result = $stmt->get_result();

        $recipes = [];
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
        return $recipes;
    }
}
?>
