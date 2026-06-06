<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../connexion.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->execute([$id]);
$projet = $stmt->fetch();

if ($projet) {
    if ($projet['image'] && file_exists('../../images/projets/' . $projet['image'])) {
        unlink('../../images/projets/' . $projet['image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM projets WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: liste.php');
exit;
?>
