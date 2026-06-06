<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$csrf_token = generer_csrf();
$erreurs = [];
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifier_csrf($_POST['csrf_token'] ?? '')) {
        die('Requête invalide.');
    }

    $prenom      = nettoyer($_POST['prenom'] ?? '');
    $nom         = nettoyer($_POST['nom'] ?? '');
    $email       = nettoyer($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    if (!champ_requis($prenom))                        $erreurs[] = 'Le prénom est obligatoire.';
    if (!champ_requis($nom))                           $erreurs[] = 'Le nom est obligatoire.';
    if (!email_valide($email))                         $erreurs[] = 'L\'adresse email est invalide.';
    if (strlen($mot_de_passe) < 8)                     $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères.';

    if (empty($erreurs)) {
        $stmt = $pdo->prepare("SELECT id FROM administrateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreurs[] = 'Cet email est déjà utilisé.';
        }
    }

    if (empty($erreurs)) {
        $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            "INSERT INTO administrateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$prenom, $nom, $email, $hash]);
        $succes = true;
        unset($_SESSION['csrf_token']);
        $csrf_token = generer_csrf();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ajouter un admin — Administration</title>
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
            <h2>➕ Ajouter un administrateur</h2>
            <a href="liste.php" class="btn btn-outline">← Retour</a>
        </div>

        <div class="form-card">
            <?php if ($succes): ?>
                <div class="success">✅ Administrateur ajouté ! <a href="liste.php">Voir la liste</a></div>
            <?php endif; ?>
            <?php foreach ($erreurs as $e): ?>
                <div class="error">❌ <?= $e ?></div>
            <?php endforeach; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>" />
                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" placeholder="Prénom" />
                </div>
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" placeholder="Nom" />
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" placeholder="email@exemple.com" />
                </div>
                <div class="form-group">
                    <label>Mot de passe * <small style="color:var(--text-light)">(min. 8 caractères)</small></label>
                    <input type="password" name="mot_de_passe" placeholder="••••••••" />
                </div>
                <button type="submit" class="btn btn-primary">Ajouter ✦</button>
            </form>
        </div>
    </div>
</body>
</html>
