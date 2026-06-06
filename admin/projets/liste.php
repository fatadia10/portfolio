<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location:  ../../connexion.php');
    exit;
}

$projets = $pdo->query("SELECT * FROM projets ORDER BY date_creation DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Projets — Administration</title>
    <link rel="stylesheet" href="../../style.css" />
    <style>
        body { background: var(--bg); }
        .admin-header { background: var(--violet-deep); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 1.3rem; color: white; }
        .admin-nav { background: var(--violet-main); padding: 0.8rem 2rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .admin-nav a { color: rgba(255,255,255,0.85); font-size: 0.9rem; font-weight: 600; text-decoration: none; }
        .admin-nav a:hover { color: white; }
        .admin-content { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { background: var(--violet-mist); padding: 0.7rem 1rem; text-align: left; color: var(--violet-main); font-weight: 600; }
        td { padding: 0.7rem 1rem; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
<div class="admin-header">
        <h1>🛡️ Administration — Bineta DIA</h1>
        <span>Bonjour, <?= htmlspecialchars($_SESSION['admin_prenom']) ?> | <a href="../deconnexion.php" style="color:#fca5a5;">Déconnexion</a></span>
    </div>
    <nav class="admin-nav">
        <a href="../dashboard.php">🏠 Dashboard</a>
        <a href="liste.php">📁 Projets</a>
        <a href="../messages/liste.php">💬 Messages</a>
        <a href="../demandes/liste.php">📋 Demandes</a>
        <a href="../utilisateurs/liste.php">👥 Administrateurs</a>
    </nav>

    <div class="admin-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2>📁 Gestion des projets</h2>
            <a href="ajouter.php" class="btn btn-primary">+ Ajouter un projet</a>
        </div>

        <div class="section-card">
            <?php if (empty($projets)): ?>
                <p style="text-align:center; color:var(--text-light); padding:2rem;">Aucun projet pour le moment.</p>
            <?php else: ?>
            <table>
                <tr>
                    <th>Titre</th>
                    <th>Technologies</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($projets as $projet): ?>
                <tr>
                    <td><?= htmlspecialchars($projet['titre']) ?></td>
                    <td><?= htmlspecialchars($projet['technologies']) ?></td>
                    <td><?= $projet['date_creation'] ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $projet['id'] ?>" class="btn-sm btn-edit">✏️ Modifier</a>
                        <form method="POST" action="supprimer.php" style="display:inline;"
                              onsubmit="return confirm('Supprimer ce projet ?')">
                            <input type="hidden" name="id" value="<?= $projet['id'] ?>" />
                            <input type="hidden" name="csrf_token" value="<?= generer_csrf() ?>" />
                            <button type="submit" class="btn-sm btn-delete">🗑️ Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
    
