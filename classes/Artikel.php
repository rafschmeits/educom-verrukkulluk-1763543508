<?php
class Artikel {
    private int $id;
    private string $naam;
    private ?string $omschrijving;
    private float $prijs;
    private string $eenheid;
    private ?string $verpakking;

    // 🔹 Constructor
    public function __construct($id, $naam, $omschrijving, $prijs, $eenheid, $verpakking) {
        $this->id = $id;
        $this->naam = $naam;
        $this->omschrijving = $omschrijving;
        $this->prijs = $prijs;
        $this->eenheid = $eenheid;
        $this->verpakking = $verpakking;
    }

    // 🔹 selectArticle method (ophalen uit database)
    public static function selectArticle(PDO $pdo, string $naam): ?Artikel {
        $stmt = $pdo->prepare("SELECT * FROM artikel WHERE naam LIKE ?");
        $stmt->execute([$naam]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Artikel(
                $row['id'],
                $row['naam'],
                $row['omschrijving'],
                $row['prijs'],
                $row['eenheid'],
                $row['verpakking']
            );
        }
        return null;
    }

    // Getters (optioneel, handig voor output)
    public function getNaam(): string { return $this->naam; }
    public function getOmschrijving(): ?string { return $this->omschrijving; }
    public function getPrijs(): float { return $this->prijs; }
    public function getEenheid(): string { return $this->eenheid; }
    public function getVerpakking(): ?string { return $this->verpakking; }
} 
?>