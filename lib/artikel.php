<?php
class Artikel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function selecteerArtikel(int $artikel_id): ?array {
        $stmt = $this->connection->prepare("SELECT * FROM artikel WHERE id = ?");
        $stmt->bind_param("i", $artikel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $artikel = $result->fetch_assoc();

        return $artikel ?: null;
    }
}
?>
