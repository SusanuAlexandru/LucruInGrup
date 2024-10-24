<?php
session_start();
require_once './bootstrap.php';
ob_start(); // Începe bufferingul pentru a preveni erorile de tip "headers already sent"

// Filtrarea după clasă
$clasa_filtrata = isset($_GET['clasa']) ? htmlspecialchars($_GET['clasa']) : '';

// Crearea instanței ElevRepository
$elevRepository = new ElevRepository($databaseConnection);

$clase = $elevRepository->readClase(); // Obține toate clasele

// Verifică dacă este filtrare activă
if ($clasa_filtrata) {
    $elevi = $elevRepository->readEleviByClasa($clasa_filtrata); // Metodă pentru a citi elevii după clasă
} else {
    $elevi = $elevRepository->readElevi(); // Citește toți elevii
}

// Procesarea formularului de adăugare elev
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume_prenume = htmlspecialchars($_POST['nume_prenume']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $clasa = htmlspecialchars($_POST['clasa']);
    $data_nasterii = htmlspecialchars($_POST['data_nasterii']);
    $nr_parinte = htmlspecialchars($_POST['nr_parinte']);

    // Verifică dacă email-ul este valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Email invalid.';
        header('Location: index.php');
        exit;
    }

    // Continuă cu inserarea datelor
    $elevRepository->createElev($nume_prenume, $email, $clasa, $data_nasterii, $nr_parinte);

    // Adaugă mesaj de succes
    $_SESSION['message'] = 'Elevul a fost adăugat cu succes!';
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Elevi Management</title>
</head>
<body>
<div class="container mt-5">
    <div class="col-6 m-auto">
        <h2>Adaugă Elev</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nume_prenume" class="form-label">Nume Prenume</label>
                <input type="text" class="form-control" id="nume_prenume" name="nume_prenume" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="clasa" class="form-label">Clasa</label>
                <input type="text" class="form-control" id="clasa" name="clasa" required>
            </div>
            <div class="mb-3">
                <label for="data_nasterii" class="form-label">Data Nașterii</label>
                <input type="date" class="form-control" id="data_nasterii" name="data_nasterii" required>
            </div>
            <div class="mb-3">
                <label for="nr_parinte" class="form-label">Număr Părinte</label>
                <input type="text" class="form-control" id="nr_parinte" name="nr_parinte" required value="+373">
            </div>
            <button type="submit" class="btn btn-primary">Adaugă</button>
        </form>
    </div>

    <div class="container mt-5">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="d-flex gap-5">
            <div class="col-2 my-5">
                <!-- Formular de filtrare -->
                <form method="GET" action="">
                    <div class="form-group">
                        <label for="clasa"><h4>Filtrează după clasă:</h4></label>
                        <select name="clasa" id="clasa" class="form-control">
                            <option value="">Toate clasele</option>
                            <?php foreach ($clase as $clasa): ?>
                                <option value="<?php echo htmlspecialchars($clasa); ?>" 
                                    <?php if ($clasa_filtrata == $clasa) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($clasa); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Filtrează</button>
                </form>
            </div>
            <div class="col-10">
                <h2 class="mb-4">Lista Elevilor</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nume Prenume</th>
                            <th>Email</th>
                            <th>Clasa</th>
                            <th>Data Nașterii</th>
                            <th>Număr Părinte</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $contor = 1; // Initializăm contorul ?>
                        <?php foreach ($elevi as $elev): ?>
                            <tr>
                                <!-- Aici folosim $contor pentru a numerota rândurile -->
                                <td><?php echo $contor++; ?></td> 
                                <td><?php echo htmlspecialchars($elev['nume_prenume']); ?></td>
                                <td><?php echo htmlspecialchars($elev['email']); ?></td>
                                <td><?php echo htmlspecialchars($elev['clasa']); ?></td>
                                <td><?php echo htmlspecialchars($elev['data_nasterii']); ?></td>
                                <td><?php echo htmlspecialchars($elev['nr_parinte']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $elev['id']; ?>" class="btn btn-warning btn-sm">Actualizează</a>
                                    <a href="delete.php?id=<?php echo $elev['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Ești sigur că vrei să ștergi acest elev?');">Șterge</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
