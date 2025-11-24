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

    // Haal een user op via email (handig voor login)
    public function selecteerUserOpEmail(string $email): ?array {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        return $user ?: null;
    }
}
?>
