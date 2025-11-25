<?php
class Ingredient {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Haal ingrediënten op en roep Artikel-class aan
    public function selectIngredient(int $gerecht_id): array {
        $stmt = $this->connection->prepare("
            SELECT id, gerecht_id, artikel_id, aantal
            FROM ingredient
            WHERE gerecht_id = ?
        ");
        $stmt->bind_param("i", $gerecht_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $records = $result->fetch_all(MYSQLI_ASSOC);

        // Artikel-class gebruiken voor artikelinfo
        $artikelRepo = new Artikel($this->connection);
        foreach ($records as &$record) {
            $record['artikel'] = $artikelRepo->selecteerArtikel((int)$record['artikel_id']);
        }

        return $records;
    }
}
?>
