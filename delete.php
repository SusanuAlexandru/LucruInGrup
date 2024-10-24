<?php
session_start();
require_once './bootstrap.php';
$elevRepository = new ElevRepository($databaseConnection);

// Logica pentru ștergere
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $elevRepository->deleteElev($id);
    header('Location: index.php'); // Redirecționează înapoi la index.php după ștergere
    exit;
}

// Citirea elevilor din baza de date
$elevi = $elevRepository->readElevi();
?>