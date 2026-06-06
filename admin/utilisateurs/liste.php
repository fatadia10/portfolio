<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$admins = $pdo->query("SELECT id, prenom, nom, email, date_creation FROM administrateurs ORDER BY date_creation DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administrateurs — Administration</title>
    <link rel="stylesheet" href="../../style.css" />
    <style>
        body { background: var(--bg); }
        .admin-header { background: var(--violet-deep); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 1.3rem; color: white; }
        .admin-nav { background: var(--violet-main); padding: 0.8rem 2rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .admin-nav a { color: rgba(255,255,255,0.85); font-size: 0.9rem; font-weight: 600; text-decoration: none; }
        .admin-nav a:hover { color: white; }
        .admin-content { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .section-card { background: white; border-radius: var(--radius); padding: 1.5rem; border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { background: var(--violet-mist); padding: 0.7rem 1rem; text-align: left; color: var(--violet-main); font-weight: 600; }
        td { padding: 0.7rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        .btn-sm { padding: 0.4rem 0.9rem; font-size: 0.8rem; border-radius: 50px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
        .btn-edit { background: var(--violet-pale); color: var(--violet-main); }
        .btn-delete { background: #fef2f2; color: #dc2626; }
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
        <a href="../demandes/liste.php">📋 Demandes</a>
        <a href="liste.php">👥 Administrateurs</a>
    </nav>

    <div class="admin-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2>👥 Gestion des administrateurs</h2>
            <a href="ajouter.php" class="btn btn-primary">+ Ajouter un admin</a>
        </div>

        <div class="section-card">
            <table>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= htmlspecialchars($admin['prenom']) ?></td>
                    <td><?= htmlspecialchars($admin['nom']) ?></td>
                    <td><?= htmlspecialchars($admin['email']) ?></td>
                    <td><?= $admin['date_creation'] ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $admin['id'] ?>" class="btn-sm btn-edit">✏️ Modifier</a>
                        <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                        <form method="POST" action="supprimer.php" style="display:inline;"
                              onsubmit="return confirm('Supprimer cet administrateur ?')">
                            <input type="hidden" name="id" value="<?= $admin['id'] ?>" />
                            <input type="hidden" name="csrf_token" value="<?= generer_csrf() ?>" />
                            <button type="submit" class="btn-sm btn-delete">🗑️ Supprimer</button>
                        </form>
                        <?php else: ?>
                            <span style="color:var(--text-light); font-size:0.8rem;">Compte actif</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
