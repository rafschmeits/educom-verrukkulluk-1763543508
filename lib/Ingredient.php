<?php
class Ingredient {
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // selectIngredient method: haal alle ingrediënten van een gerecht op
    public function selectIngredient(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT i.id, i.gerecht_id, i.artikel_id, i.aantal,
                   a.naam AS artikel_naam
            FROM ingredient i
            INNER JOIN artikel a ON i.artikel_id = a.id
            WHERE i.gerecht_id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // selectArticle method: haal artikel-info op via artikel_id
    public function selectArticle(int $artikel_id): ?array {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM artikel 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $artikel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();

        return $article ?: null;
    }
}
?>
