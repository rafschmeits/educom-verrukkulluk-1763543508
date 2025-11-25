<?php
class User {
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Haal een user op via ID
    public function selecteerUser(int $user_id): ?array {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        return $user ?: null;
    }
}
?>
