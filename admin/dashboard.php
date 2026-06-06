<?php
session_start();
require '../config/connexion.php';
require '../fonctions.php';


if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit;
}


$nb_projets    = $pdo->query("SELECT COUNT(*) FROM projets")->fetchColumn();
$nb_messages   = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 0")->fetchColumn();
$nb_demandes   = $pdo->query("SELECT COUNT(*) FROM demandes_projet WHERE lu = 0")->fetchColumn();

$visites = $pdo->query("SELECT * FROM visites ORDER BY date_visite DESC LIMIT 5")->fetchAll();


$demandes = $pdo->query("SELECT * FROM demandes_projet ORDER BY date_demande DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard — Administration</title>
    <link rel="stylesheet" href="../style.css" />
    <style>
        body { background: var(--bg); }
        .admin-header { background: var(--violet-deep); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 1.3rem; color: white; }
        .admin-nav { background: var(--violet-main); padding: 0.8rem 2rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .admin-nav a { color: rgba(255,255,255,0.85); font-size: 0.9rem; font-weight: 600; text-decoration: none; }
        .admin-nav a:hover { color: white; }
        .admin-content { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: var(--radius); padding: 1.5rem; border: 1px solid var(--border); text-align: center; box-shadow: var(--shadow); }
        .stat-card .number { font-size: 2.5rem; font-weight: 900; color: var(--violet-main); font-family: 'Playfair Display', serif; }
        .stat-card .label { color: var(--text-light); font-size: 0.85rem; margin-top: 0.3rem; }
        .section-card { background: white; border-radius: var(--radius); padding: 1.5rem; border: 1px solid var(--border); margin-bottom: 1.5rem; }
        .section-card h2 { font-size: 1.1rem; margin-bottom: 1rem; color: var(--violet-dark); }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { background: var(--violet-mist); padding: 0.7rem 1rem; text-align: left; color: var(--violet-main); font-weight: 600; }
        td { padding: 0.7rem 1rem; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>

    <div class="admin-header">
        <h1>🛡️ Administration — Bineta DIA</h1>
        <span>Bonjour, <?= htmlspecialchars($_SESSION['admin_prenom']) ?> | <a href="deconnexion.php" style="color:#fca5a5;">Déconnexion</a></span>
    </div>

    <nav class="admin-nav">
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="projets/liste.php">📁 Projets</a>
        <a href="messages/liste.php">💬 Messages</a>
        <a href="demandes/liste.php">📋 Demandes</a>
        <a href="utilisateurs/liste.php">👥 Administrateurs</a>
    </nav>

    <div class="admin-content">
        <h2 style="margin-bottom:1.5rem;">Tableau de bord</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="number"><?= $nb_projets ?></div>
                <div class="label">Projets publiés</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $nb_messages ?></div>
                <div class="label">Messages non lus</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $nb_demandes ?></div>
                <div class="label">Demandes non lues</div>
            </div>
        </div>

        
        <div class="section-card">
            <h2>🕐 5 dernières visites</h2>
            <table>
                <tr>
                    <th>Adresse IP</th>
                    <th>Page</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($visites as $visite): ?>
                <tr>
                    <td><?= htmlspecialchars($visite['adresse_ip']) ?></td>
                    <td><?= htmlspecialchars($visite['page']) ?></td>
                    <td><?= $visite['date_visite'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        
        <div class="section-card">
            <h2>📋 5 dernières demandes</h2>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td><?= htmlspecialchars($demande['nom']) ?></td>
                    <td><?= htmlspecialchars($demande['email']) ?></td>
                    <td><?= htmlspecialchars($demande['type_projet']) ?></td>
                    <td><?= $demande['date_demande'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
