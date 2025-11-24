<?php
class KeukenType {
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // selectKitchenType method
    public function selecteerKeukenType(int $id): ?array {
        $stmt = $this->connection->prepare("SELECT * FROM keuken_type WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $keukenType = $result->fetch_assoc();

        return $keukenType ?: null;
    }
}
?>
