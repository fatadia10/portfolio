<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}


if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE demandes_projet SET lu = 1 WHERE id = ?");
    $stmt->execute([intval($_GET['id'])]);
}

$demandes = $pdo->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC")->fetchAll();


$demande_ouverte = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM demandes_projet WHERE id = ?");
    $stmt->execute([intval($_GET['id'])]);
    $demande_ouverte = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Demandes — Administration</title>
    <link rel="stylesheet" href="../../style.css" />
    <style>
        body { background: var(--bg); }
        .admin-header { background: var(--violet-deep); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 1.3rem; color: white; }
        .admin-nav { background: var(--violet-main); padding: 0.8rem 2rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .admin-nav a { color: rgba(255,255,255,0.85); font-size: 0.9rem; font-weight: 600; text-decoration: none; }
        .admin-nav a:hover { color: white; }
        .admin-content { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .section-card { background: white; border-radius: var(--radius); padding: 1.5rem; border: 1px solid var(--border); margin-bottom: 1.5rem; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { background: var(--violet-mist); padding: 0.7rem 1rem; text-align: left; color: var(--violet-main); font-weight: 600; }
        td { padding: 0.7rem 1rem; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
        .non-lu { font-weight: 700; background: #faf5ff; }
        .badge-nonlu { background: var(--violet-main); color: white; padding: 0.2rem 0.6rem; border-radius: 50px; font-size: 0.75rem; }
        .demande-detail { background: var(--violet-mist); border-radius: var(--radius); padding: 1.5rem; margin-bottom: 1.5rem; border-left: 4px solid var(--violet-main); }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>🛡️ Administration — Bineta DIA</h1>
        <span>Bonjour, <?= htmlspecialchars($_SESSION['admin_prenom']) ?> | <a href="../deconnexion.php" style="color:#fca5a5;">Déconnexion</a></span>
    </div>
    <nav class="admin-nav">
        <a href="../dashboard.php">🏠 Dashboard</a>
        <a href="../projets/liste.php">📁 Projets</a>
        <a href="../messages/liste.php">💬 Messages</a>
        <a href="liste.php">📋 Demandes</a>
        <a href="../utilisateurs/liste.php">👥 Administrateurs</a>
    </nav>

    <div class="admin-content">
        <h2 style="margin-bottom:1.5rem;">📋 Demandes de projet</h2>

        <?php if ($demande_ouverte): ?>
        <div class="demande-detail">
            <h3>Demande de <?= htmlspecialchars($demande_ouverte['nom']) ?></h3>
            <p style="font-size:0.85rem; color:var(--text-light);">
                📧 <?= htmlspecialchars($demande_ouverte['email']) ?> —
                📅 <?= $demande_ouverte['date_demande'] ?>
            </p>
            <p><strong>Type :</strong> <?= htmlspecialchars($demande_ouverte['type_projet']) ?></p>
            <?php if ($demande_ouverte['budget']): ?>
            <p><strong>Budget :</strong> <?= htmlspecialchars($demande_ouverte['budget']) ?></p>
            <?php endif; ?>
            <p style="margin-top:0.8rem;"><strong>Description :</strong></p>
            <p><?= nl2br(htmlspecialchars($demande_ouverte['description'])) ?></p>
            <a href="liste.php" style="font-size:0.85rem; color:var(--violet-main);">← Fermer</a>
        </div>
        <?php endif; ?>

        <div class="section-card">
            <?php if (empty($demandes)): ?>
                <p style="text-align:center; color:var(--text-light); padding:2rem;">Aucune demande reçue.</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($demandes as $demande): ?>
                <tr class="<?= $demande['lu'] ? '' : 'non-lu' ?>">
                    <td><?= htmlspecialchars($demande['nom']) ?></td>
                    <td><?= htmlspecialchars($demande['email']) ?></td>
                    <td><?= htmlspecialchars($demande['type_projet']) ?></td>
                    <td><?= $demande['date_demande'] ?></td>
                    <td>
                        <?php if (!$demande['lu']): ?>
                            <span class="badge-nonlu">Non lu</span>
                        <?php else: ?>
                            <span style="color:var(--text-light); font-size:0.8rem;">Lu</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?id=<?= $demande['id'] ?>" style="color:var(--violet-main); font-size:0.85rem; font-weight:600;">
                            👁️ Voir
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
