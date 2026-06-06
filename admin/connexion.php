<?php
session_start();
require '../config/connexion.php';
require '../fonctions.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$csrf_token = generer_csrf();
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifier_csrf($_POST['csrf_token'] ?? '')) {
        die('Requête invalide.');
    }

    $email      = nettoyer($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']     = $admin['id'];
        $_SESSION['admin_prenom'] = $admin['prenom'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erreur = 'Identifiants incorrects.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion — Administration</title>
    <link rel="stylesheet" href="../style.css" />
    <style>
        body { background: var(--violet-mist); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { background: white; border-radius: var(--radius); padding: 3rem; width: 100%; max-width: 420px; box-shadow: var(--shadow-hover); }
        .login-card h1 { font-size: 1.8rem; margin-bottom: 0.5rem; color: var(--violet-main); }
        .login-card p { color: var(--text-light); margin-bottom: 2rem; font-size: 0.9rem; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; }
        input { width: 100%; padding: 0.8rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.95rem; outline: none; }
        input:focus { border-color: var(--violet-main); box-shadow: 0 0 0 3px rgba(108,63,197,0.1); }
        .error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.2rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>🔐 Administration</h1>
        <p>Connectez-vous pour accéder à l'espace admin.</p>

        <?php if ($erreur): ?>
            <div class="error"><?= $erreur ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>" />
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" required />
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required />
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Se connecter</button>
        </form>
    </div>
</body>
</html>
