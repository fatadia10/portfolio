<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

if (!verifier_csrf($_POST['csrf_token'] ?? '')) {
    die('Requête invalide.');
}

$id = intval($_POST['id'] ?? 0);


if ($id === $_SESSION['admin_id']) {
    header('Location: liste.php');
    exit;
}

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM administrateurs WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: liste.php');
exit;
?>
