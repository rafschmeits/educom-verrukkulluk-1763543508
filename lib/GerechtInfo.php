<?php
class GerechtInfo {
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // selectInfo method: haalt ALLE info van een gerecht op
   public function selectInfo(int $gerecht_id): array {
    $stmt = $this->connection->prepare("
        SELECT * 
        FROM gerecht_info 
        WHERE gerecht_id = ?
        ORDER BY record_type ASC, stap ASC, datum ASC
    ");
    $stmt->bind_param("i", $gerecht_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $records = $result->fetch_all(MYSQLI_ASSOC);

    // Maak een User-object om usergegevens op te halen
    $userObj = new User($this->connection);

    // Loop door alle records en voeg user-info toe
    foreach ($records as &$record) {
        // Alleen bij record_type O (opmerking) of F (favoriet) user ophalen
        if (!empty($record['user_id']) && in_array($record['record_type'], ['O', 'F'])) {
            $record['user'] = $userObj->selecteerUser((int)$record['user_id']);
        }
    }


    return $records;
}


    // Haal alle opmerkingen (record_type = 'O') of favorieten (record_type = 'F') van een user bij een gerecht
    public function selectUserRecords(int $gerecht_id, string $recordType): array {
        $stmt = $this->connection->prepare("
            SELECT * 
            FROM gerecht_info 
            WHERE gerecht_id = ? AND record_type = ?
            ORDER BY datum ASC
        ");
        $stmt->bind_param("is", $gerecht_id, $recordType);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Methode: voeg favoriet toe
    public function addFavorite(int $gerecht_id, int $user_id): bool {
        $stmt = $this->connection->prepare("
            INSERT INTO gerecht_info (gerecht_id, user_id, record_type) 
            VALUES (?, ?, 'F')
        ");
        $stmt->bind_param("ii", $gerecht_id, $user_id);
        return $stmt->execute();
    }

    // Methode: verwijder favoriet
    public function deleteFavorite(int $gerecht_id, int $user_id): bool {
        $stmt = $this->connection->prepare("
            DELETE FROM gerecht_info 
            WHERE gerecht_id = ? AND user_id = ? AND record_type = 'F'
        ");
        $stmt->bind_param("ii", $gerecht_id, $user_id);
        return $stmt->execute();
    }
}
?>
