<?php
class ElevRepository implements ElevRepositoryInterface
{
    private $pdo;

    public function __construct(DatabaseConnectionInterface $databaseConnection)
    {
        $this->pdo = $databaseConnection->connect();
    }

    // Creare elev
    public function createElev($nume_prenume, $email, $clasa, $data_nasterii, $nr_parinte)
    {
        $nume_prenume = htmlspecialchars($nume_prenume);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitizare pentru email
        $clasa = htmlspecialchars($clasa);
        $data_nasterii = htmlspecialchars($data_nasterii);
        $nr_parinte = htmlspecialchars($nr_parinte);

        // Validare email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email invalid.');
        }

        $sql = 'INSERT INTO users (nume_prenume, email, clasa, data_nasterii, nr_parinte) 
                VALUES (:nume_prenume, :email, :clasa, :data_nasterii, :nr_parinte)';

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute([
                ':nume_prenume' => $nume_prenume, 
                ':email' => $email, 
                ':clasa' => $clasa, 
                ':data_nasterii' => $data_nasterii, 
                ':nr_parinte' => $nr_parinte
            ]);
            $_SESSION['message'] = "Elevul a fost adăugat cu succes!";
        } catch (PDOException $e) {
            // Poți să loghezi eroarea aici sau să gestionezi excepția
            throw new Exception('Eroare la inserarea elevului: ' . $e->getMessage());
        }
    }

    // Citire elevi
    public function readElevi()
    {
        $sql = 'SELECT * FROM users';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Citire elev dupa ID
    public function getById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizare elev
    public function updateElev($id, $nume_prenume, $email, $clasa, $data_nasterii, $nr_parinte)
    {
        $sql = 'UPDATE users SET 
                    nume_prenume = :nume_prenume, 
                    email = :email, 
                    clasa = :clasa, 
                    data_nasterii = :data_nasterii, 
                    nr_parinte = :nr_parinte, 
                    updated_at = current_timestamp() 
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        
        try {
            $stmt->execute([
                ':nume_prenume' => $nume_prenume, 
                ':email' => $email, 
                ':clasa' => $clasa, 
                ':data_nasterii' => $data_nasterii, 
                ':nr_parinte' => $nr_parinte, 
                ':id' => $id
            ]);
            $_SESSION['message'] = "Elevul a fost actualizat cu succes!";
        } catch (PDOException $e) {
            throw new Exception('Eroare la actualizarea elevului: ' . $e->getMessage());
        }
    }

    // Ștergere elev
    public function deleteElev($id)
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        
        try {
            $stmt->execute([':id' => $id]);
            $_SESSION['message'] = "Elevul a fost șters cu succes!";
        } catch (PDOException $e) {
            throw new Exception('Eroare la ștergerea elevului: ' . $e->getMessage());
        }
    }

    // Obține toate clasele distincte din baza de date
    public function readClase()
    {
        $sql = 'SELECT DISTINCT clasa FROM users';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obține elevii după clasă
    public function readEleviByClasa($clasa)
    {
        $sql = 'SELECT * FROM users WHERE clasa = :clasa';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':clasa' => $clasa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
