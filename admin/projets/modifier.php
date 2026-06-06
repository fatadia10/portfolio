<?php
session_start();
require '../../config/connexion.php';
require '../../fonctions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../connexion.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ../../projets/liste.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->execute([$id]);
$projet = $stmt->fetch();

if (!$projet) {
    header('Location: ../../projets/liste.php');
    exit;
}

$csrf_token = generer_csrf();
$erreurs = [];
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifier_csrf($_POST['csrf_token'] ?? '')) {
        die('Requête invalide.');
    }

    $titre        = nettoyer($_POST['titre'] ?? '');
    $description  = nettoyer($_POST['description'] ?? '');
    $technologies = nettoyer($_POST['technologies'] ?? '');
    $lien         = nettoyer($_POST['lien'] ?? '');
    $image        = $projet['image']; 

    if (!champ_requis($titre))        $erreurs[] = 'Le titre est obligatoire.';
    if (!champ_requis($description))  $erreurs[] = 'La description est obligatoire.';
    if (!champ_requis($technologies)) $erreurs[] = 'Les technologies sont obligatoires.';

    
    if (!empty($_FILES['image']['name'])) {
        $ext_autorisees = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ext_autorisees)) {
            $erreurs[] = 'Format image non autorisé.';
        } else {
            
            if ($projet['image'] && file_exists('../../images/projets/' . $projet['image'])) {
                unlink('../../images/projets/' . $projet['image']);
            }
            $nom_image = uniqid('projet_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../../images/projets/' . $nom_image);
            $image = $nom_image;
        }
    }

    if (empty($erreurs)) {
        $stmt = $pdo->prepare(
            "UPDATE projets SET titre=?, description=?, technologies=?, lien=?, image=? WHERE id=?"
        );
        $stmt->execute([$titre, $description, $technologies, $lien ?: null, $image, $id]);
        $succes = true;
       
        $stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
        $stmt->execute([$id]);
        $projet = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier un projet — Administration</title>
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
        input, textarea { width: 100%; padding: 0.8rem 1rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm); font-size: 0.92rem; outline: none; font-family: inherit; }
        input:focus, textarea:focus { border-color: var(--violet-main); }
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
        <a href="liste.php">📁 Projets</a>
        <a href="../messages/liste.php">💬 Messages</a>
        <a href="../demandes/liste.php">📋 Demandes</a>
        <a href="../utilisateurs/liste.php">👥 Administrateurs</a>
    </nav>

    <div class="admin-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2>✏️ Modifier un projet</h2>
            <a href="liste.php" class="btn btn-outline">← Retour</a>
        </div>

        <div class="form-card">
            <?php if ($succes): ?>
                <div class="success">✅ Projet modifié avec succès !</div>
            <?php endif; ?>
            <?php foreach ($erreurs as $e): ?>
                <div class="error">❌ <?= $e ?></div>
            <?php endforeach; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>" />
                <div class="form-group">
                    <label>Titre *</label>
                    <input type="text" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" />
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" rows="4"><?= htmlspecialchars($projet['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Technologies *</label>
                    <input type="text" name="technologies" value="<?= htmlspecialchars($projet['technologies']) ?>" />
                </div>
                <div class="form-group">
                    <label>Lien externe</label>
                    <input type="url" name="lien" value="<?= htmlspecialchars($projet['lien'] ?? '') ?>" />
                </div>
                <div class="form-group">
                    <label>Image du projet</label>
                    <?php if ($projet['image']): ?>
                        <p style="font-size:0.85rem; color:var(--text-light); margin-bottom:0.5rem;">
                            Image actuelle : <?= htmlspecialchars($projet['image']) ?>
                        </p>
                    <?php endif; ?>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.gif" />
                    <small style="color:var(--text-light);">Laissez vide pour garder l'image actuelle</small>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications ✦</button>
            </form>
        </div>
    </div>
</body>
</html>
