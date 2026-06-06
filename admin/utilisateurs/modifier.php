<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: liste.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, prenom, nom, email FROM administrateurs WHERE id = ?");
$stmt->execute([$id]);
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: liste.php');
    exit;
}

$csrf_token = generer_csrf();
$erreurs = [];
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifier_csrf($_POST['csrf_token'] ?? '')) {
        die('Requête invalide.');
    }

    $prenom       = nettoyer($_POST['prenom'] ?? '');
    $nom          = nettoyer($_POST['nom'] ?? '');
    $email        = nettoyer($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (!champ_requis($prenom))  $erreurs[] = 'Le prénom est obligatoire.';
    if (!champ_requis($nom))     $erreurs[] = 'Le nom est obligatoire.';
    if (!email_valide($email))   $erreurs[] = 'L\'adresse email est invalide.';
    if (!empty($mot_de_passe) && strlen($mot_de_passe) < 8) {
        $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères.';
    }

    if (empty($erreurs)) {
      
        if (!empty($mot_de_passe)) {
            $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                "UPDATE administrateurs SET prenom=?, nom=?, email=?, mot_de_passe=? WHERE id=?"
            );
            $stmt->execute([$prenom, $nom, $email, $hash, $id]);
        } else {
            $stmt = $pdo->prepare(
                "UPDATE administrateurs SET prenom=?, nom=?, email=? WHERE id=?"
            );
            $stmt->execute([$prenom, $nom, $email, $id]);
        }
        $succes = true;
        $admin['prenom'] = $prenom;
        $admin['nom']    = $nom;
        $admin['email']  = $email;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier un admin — Administration</title>
    <link rel="stylesheet" href="../../style.css" />
    <style>
        body { background: var(--bg); }
        .admin-header { background: var(--violet-deep); color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .admin-header h1 { font-size: 1.3rem; color: white; }
        .admin-nav { background: var(--violet-main); padding: 0.8rem 2rem; display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .admin-nav a { color: rgba(255,255,255,0.85); font-size: 0.9rem; font-weight: 600; text-decoration: none; }
        .admin-nav a:hover { color: white; }
        .admin-content { max-width: 800px; margin: 2rem auto; padding: 0 2rem; }
        .form-card { background: white; border-radius: var(--radius); padding: 2rem; border: 1px solid var(--border); }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; }
        input { width: 100%; padding: 0.8rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none; }
        input:focus { border-color: var(--violet-main); }
        .error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1rem; font-size: 0.9rem; }
        .success { background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1rem; font-size: 0.9rem; }
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
            <h2>✏️ Modifier un administrateur</h2>
            <a href="liste.php" class="btn btn-outline">← Retour</a>
        </div>

        <div class="form-card">
            <?php if ($succes): ?>
                <div class="success">✅ Administrateur modifié avec succès !</div>
            <?php endif; ?>
            <?php foreach ($erreurs as $e): ?>
                <div class="error">❌ <?= $e ?></div>
            <?php endforeach; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>" />
                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($admin['prenom']) ?>" />
                </div>
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($admin['nom']) ?>" />
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" />
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe <small style="color:var(--text-light)">(laisser vide pour ne pas changer)</small></label>
                    <input type="password" name="mot_de_passe" placeholder="••••••••" />
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer ✦</button>
            </form>
        </div>
    </div>
</body>
</html>
