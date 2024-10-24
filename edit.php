<?php
session_start(); // Asigură-te că sesiunea este activată
require_once './bootstrap.php';
$elevRepository = new ElevRepository($databaseConnection);

if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $elev = $elevRepository->getById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nume_prenume = $_POST['nume_prenume'];
        $email = $_POST['email'];
        $clasa = $_POST['clasa'];
        $data_nasterii = $_POST['data_nasterii'];
        $nr_parinte = $_POST['nr_parinte'];

        // Actualizează elevul
        $elevRepository->updateElev($id, $nume_prenume, $email, $clasa, $data_nasterii, $nr_parinte);
        
        // Mesaj corectat
        $_SESSION['message'] = "Elevul a fost actualizat cu succes!";
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Actualizează Elev</title>
</head>

<body>
    <div class="container mt-5 col-6 m-auto">
        <h2>Actualizează Elev</h2>
        <form action="?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label for="nume_prenume" class="form-label">Nume Prenume</label>
                <input type="text" class="form-control" id="nume_prenume" name="nume_prenume"
                    value="<?php echo htmlspecialchars($elev['nume_prenume']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($elev['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="clasa" class="form-label">Clasa</label>
                <input type="text" class="form-control" id="clasa" name="clasa"
                    value="<?php echo htmlspecialchars($elev['clasa']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="data_nasterii" class="form-label">Data Nașterii</label>
                <input type="date" class="form-control" id="data_nasterii" name="data_nasterii"
                    value="<?php echo htmlspecialchars($elev['data_nasterii']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nr_parinte" class="form-label">Număr Părinte</label>
                <input type="text" class="form-control" id="nr_parinte" name="nr_parinte"
                    value="<?php echo htmlspecialchars($elev['nr_parinte']); ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Actualizează</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
