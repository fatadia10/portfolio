<?php
session_start();
require 'config/connexion.php';
require 'fonctions.php';

enregistrer_visite($pdo, 'contact.php');
$csrf_token = generer_csrf();

$erreurs  = [];
$succes   = false;
$nom      = '';
$email    = '';
$sujet    = '';
$message  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_contact'])) {
    if (!verifier_csrf($_POST['csrf_token'] ?? '')) die('Requête invalide.');

    $nom     = nettoyer($_POST['prenom'] ?? '');
    $email   = nettoyer($_POST['email'] ?? '');
    $sujet   = nettoyer($_POST['sujet'] ?? '');
    $message = nettoyer($_POST['message'] ?? '');

    if (!champ_requis($nom))                              $erreurs['nom']     = 'Le nom est obligatoire.';
    if (!champ_requis($email) || !email_valide($email))   $erreurs['email']   = "L'adresse email est invalide.";
    if (!champ_requis($message))                          $erreurs['message'] = 'Le message ne peut pas être vide.';

    if (empty($erreurs)) {
        $pdo->prepare("INSERT INTO messages_contact (nom, email, message) VALUES (?, ?, ?)")
            ->execute([$nom, $email, $message]);
        $succes = true;
        $nom = $email = $sujet = $message = '';
        unset($_SESSION['csrf_token']);
        $csrf_token = generer_csrf();
    }
}

$erreurs_projet   = [];
$succes_projet    = false;
$proj_prenom      = '';
$proj_nom         = '';
$proj_email       = '';
$proj_type        = '';
$proj_budget      = '';
$proj_delai       = '';
$proj_description = '';
$proj_references  = '';
$demande          = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_projet'])) {
    if (!verifier_csrf($_POST['csrf_token'] ?? '')) die('Requête invalide.');

    $proj_prenom      = nettoyer($_POST['proj_prenom']      ?? '');
    $proj_nom         = nettoyer($_POST['proj_nom']         ?? '');
    $proj_email       = nettoyer($_POST['proj_email']       ?? '');
    $proj_type        = nettoyer($_POST['proj_type']        ?? '');
    $proj_budget      = nettoyer($_POST['proj_budget']      ?? '');
    $proj_delai       = nettoyer($_POST['proj_delai']       ?? '');
    $proj_description = nettoyer($_POST['proj_description'] ?? '');
    $proj_references  = nettoyer($_POST['references']       ?? '');

    if (!champ_requis($proj_prenom))                                          $erreurs_projet['prenom']      = 'Le prénom est obligatoire.';
    if (!champ_requis($proj_nom))                                             $erreurs_projet['nom']         = 'Le nom est obligatoire.';
    if (!champ_requis($proj_email) || !email_valide($proj_email))             $erreurs_projet['email']       = "L'adresse email est invalide.";
    if (!champ_requis($proj_type))                                            $erreurs_projet['type']        = 'Le type de projet est obligatoire.';
    if (!champ_requis($proj_description))                                     $erreurs_projet['description'] = 'La description est obligatoire.';

    if (empty($erreurs_projet)) {
        $pdo->prepare("INSERT INTO demandes_projet (nom, email, type_projet, description, budget) VALUES (?, ?, ?, ?, ?)")
            ->execute([
                $proj_prenom . ' ' . $proj_nom,
                $proj_email,
                $proj_type,
                $proj_description,
                $proj_budget ?: null
            ]);
        $succes_projet = true;
        $demande = [
            'prenom'      => $proj_prenom,
            'nom'         => $proj_nom,
            'email'       => $proj_email,
            'type'        => $proj_type,
            'budget'      => $proj_budget,
            'delai'       => $proj_delai,
            'description' => $proj_description,
        ];
        $proj_prenom = $proj_nom = $proj_email = $proj_type = '';
        $proj_budget = $proj_delai = $proj_description = '';
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
  <title>Contact — Bineta DIA</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .page-hero { padding: 140px 0 5rem; background: linear-gradient(135deg, var(--violet-mist), var(--white)); text-align: center; }
    .page-hero p { max-width: 500px; margin: 1rem auto 0; color: var(--text-light); }
    .contact-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; margin-bottom: 3rem; }
    .contact-info { display: flex; flex-direction: column; gap: 1.5rem; }
    .info-card { background: var(--white); border-radius: var(--radius); padding: 1.5rem; border: 1px solid var(--border); display: flex; gap: 1rem; align-items: flex-start; transition: var(--transition); }
    .info-card:hover { box-shadow: var(--shadow); transform: translateY(-3px); }
    .info-icon { font-size: 1.6rem; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: var(--violet-pale); border-radius: var(--radius-sm); flex-shrink: 0; }
    .info-text h4 { margin-bottom: 0.2rem; font-size: 0.9rem; }
    .info-text p  { font-size: 0.85rem; color: var(--text-light); }
    .info-text a  { color: var(--violet-main); font-weight: 600; font-size: 0.9rem; }
    .divider { border: none; border-top: 1px solid var(--border); margin: 3rem 0; }
    @media (max-width: 768px) { .contact-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

<?php require 'composants/navigation.php'; ?>

<section class="page-hero">
  <div class="container">
    <span class="section-label">Restons en contact</span>
    <h1 style="font-size:clamp(2rem,5vw,3.5rem);margin-top:1rem;">Me contacter</h1>
    <p>Une question, une idée ou un projet ? Je lis tous les messages et réponds dans les 48h.</p>
  </div>
</section>

<section class="section contact-section">
  <div class="container">

    
    <div class="section-header" style="text-align:left;margin-bottom:2rem;">
      <span class="section-label">Formulaire de contact</span>
      <h2 style="margin-top:0.5rem;">Envoyer un message</h2>
    </div>

    <div class="contact-grid">
      <div class="contact-info">
        <div class="info-card"><div class="info-icon">📧</div><div class="info-text"><h4>Email</h4><a href="mailto:diabineta2006@gmail.com">diabineta2006@gmail.com</a></div></div>
        <div class="info-card"><div class="info-icon">📸</div><div class="info-text"><h4>Instagram</h4><a href="https://www.instagram.com/princessedia3" target="_blank">@princessedia3</a></div></div>
        <div class="info-card"><div class="info-icon">💬</div><div class="info-text"><h4>WhatsApp</h4><a href="https://wa.me/221788701318" target="_blank">+221 78 870 13 18</a></div></div>
        <div class="info-card"><div class="info-icon">💼</div><div class="info-text"><h4>LinkedIn</h4><a href="https://www.linkedin.com/in/bineta-dia-949042400/" target="_blank">linkedin.com/in/bineta-dia</a></div></div>
        <div class="info-card"><div class="info-icon">🐙</div><div class="info-text"><h4>GitHub</h4><a href="https://github.com/fatadia10" target="_blank">github.com/fatadia10</a></div></div>
        <div class="info-card"><div class="info-icon">🎓</div><div class="info-text"><h4>Formations</h4><p>ESTM &amp; UNCHK — Dakar</p></div></div>
        <div class="info-card"><div class="info-icon">📍</div><div class="info-text"><h4>Localisation</h4><p>Dakar, Sénégal</p></div></div>
      </div>

      <div class="form-card">
        <h3>💬 Écrivez-moi</h3>
        <p>Pour toute question, collaboration ou simplement dire bonjour !</p>

        <?php if ($succes): ?>
          <div style="background:#f0fdf4;border-left:4px solid #22c55e;padding:1rem;margin-bottom:1rem;border-radius:8px;">
            ✅ Merci, votre message a bien été envoyé !
          </div>
        <?php endif; ?>

        <form method="POST" novalidate>
          <input type="hidden" name="form_contact"  value="1" />
          <input type="hidden" name="csrf_token"    value="<?= $csrf_token ?>" />
          <div class="form-row">
            <div class="form-group">
              <label for="c-prenom">Prénom *</label>
              <input type="text" id="c-prenom" name="prenom" value="<?= htmlspecialchars($nom) ?>" placeholder="Votre prénom" />
              <?php if (isset($erreurs['nom'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs['nom'] ?></span><?php endif; ?>
            </div>
            <div class="form-group">
              <label for="c-nom">Nom</label>
              <input type="text" id="c-nom" name="contact_nom" placeholder="Votre nom" />
            </div>
          </div>
          <div class="form-group">
            <label for="c-email">Email *</label>
            <input type="email" id="c-email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="votre@email.com" />
            <?php if (isset($erreurs['email'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs['email'] ?></span><?php endif; ?>
          </div>
          <div class="form-group">
            <label for="c-sujet">Sujet</label>
            <input type="text" id="c-sujet" name="sujet" value="<?= htmlspecialchars($sujet) ?>" placeholder="Ex : Collaboration, stage..." />
          </div>
          <div class="form-group">
            <label for="c-message">Message *</label>
            <textarea id="c-message" name="message" rows="5" placeholder="Votre message ici..."><?= htmlspecialchars($message) ?></textarea>
            <?php if (isset($erreurs['message'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs['message'] ?></span><?php endif; ?>
          </div>
          <button type="submit" class="btn btn-primary">Envoyer le message ✦</button>
        </form>
      </div>
    </div>

    <hr class="divider" />

    <!-- FORMULAIRE 2 : Demande de projet -->
    <div id="project-form">
      <div class="section-header" style="text-align:left;margin-bottom:2rem;">
        <span class="section-label">Projet à réaliser</span>
        <h2 style="margin-top:0.5rem;">Demander un projet</h2>
      </div>

      <div class="form-card-full">
        <h3>🚀 Décrivez votre projet</h3>
        <p>Vous avez une idée de site web ou d'application ? Remplissez ce formulaire et je vous recontacte rapidement.</p>

        <?php if ($succes_projet): ?>
          <div style="background:#f0fdf4;border-left:4px solid #22c55e;padding:1.5rem;margin-bottom:1.5rem;border-radius:8px;">
            <strong>✅ Demande reçue ! Voici le récapitulatif :</strong><br><br>
            Nom complet : <?= htmlspecialchars($demande['prenom']) ?> <?= htmlspecialchars($demande['nom']) ?><br>
            Email : <?= htmlspecialchars($demande['email']) ?><br>
            Type de projet : <?= htmlspecialchars($demande['type']) ?><br>
            Budget : <?= htmlspecialchars($demande['budget'] ?: 'Non précisé') ?><br>
            Délai : <?= htmlspecialchars($demande['delai'] ?: 'Non précisé') ?><br>
            Description : <?= htmlspecialchars($demande['description']) ?>
          </div>
        <?php endif; ?>

        <form method="POST" novalidate>
          <input type="hidden" name="form_projet" value="1" />
          <input type="hidden" name="csrf_token"  value="<?= $csrf_token ?>" />

          <div class="form-row">
            <div class="form-group">
              <label for="p-prenom">Prénom *</label>
              <input type="text" id="p-prenom" name="proj_prenom" value="<?= htmlspecialchars($proj_prenom) ?>" placeholder="Votre prénom" />
              <?php if (isset($erreurs_projet['prenom'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs_projet['prenom'] ?></span><?php endif; ?>
            </div>
            <div class="form-group">
              <label for="p-nom">Nom *</label>
              <input type="text" id="p-nom" name="proj_nom" value="<?= htmlspecialchars($proj_nom) ?>" placeholder="Votre nom" />
              <?php if (isset($erreurs_projet['nom'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs_projet['nom'] ?></span><?php endif; ?>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="p-email">Email *</label>
              <input type="email" id="p-email" name="proj_email" value="<?= htmlspecialchars($proj_email) ?>" placeholder="votre@email.com" />
              <?php if (isset($erreurs_projet['email'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs_projet['email'] ?></span><?php endif; ?>
            </div>
            <div class="form-group">
              <label for="p-tel">Téléphone</label>
              <input type="tel" id="p-tel" name="telephone" placeholder="+221 77 000 00 00" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="p-type">Type de projet *</label>
              <select id="p-type" name="proj_type">
                <option value="">Sélectionner...</option>
                <option value="vitrine">Site vitrine</option>
                <option value="portfolio">Portfolio</option>
                <option value="ecommerce">Boutique en ligne</option>
                <option value="blog">Blog</option>
                <option value="application">Application web</option>
                <option value="autre">Autre</option>
              </select>
              <?php if (isset($erreurs_projet['type'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs_projet['type'] ?></span><?php endif; ?>
            </div>
            <div class="form-group">
              <label for="p-budget">Budget estimé</label>
              <select id="p-budget" name="proj_budget">
                <option value="">Sélectionner...</option>
                <option value="petit">Moins de 50 000 FCFA</option>
                <option value="moyen">50 000 – 150 000 FCFA</option>
                <option value="grand">150 000 – 300 000 FCFA</option>
                <option value="libre">À discuter</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="p-delai">Délai souhaité</label>
            <input type="text" id="p-delai" name="proj_delai" value="<?= htmlspecialchars($proj_delai) ?>" placeholder="Ex : 1 mois, avant fin mai..." />
          </div>

          <div class="form-group">
            <label for="p-description">Description du projet *</label>
            <textarea id="p-description" name="proj_description" rows="5" placeholder="Décrivez votre projet..."><?= htmlspecialchars($proj_description) ?></textarea>
            <?php if (isset($erreurs_projet['description'])): ?><span style="color:red;font-size:.8rem"><?= $erreurs_projet['description'] ?></span><?php endif; ?>
          </div>

          <div class="form-group">
            <label for="p-ref">Sites de référence / inspiration</label>
            <input type="text" id="p-ref" name="references" value="<?= htmlspecialchars($proj_references) ?>" placeholder="URL ou noms de sites qui vous inspirent..." />
          </div>

          <button type="submit" class="btn btn-primary">Soumettre le projet ✦</button>
        </form>
      </div>
    </div>

  </div>
</section>

<?php require 'composants/pied-de-page.php'; ?>
<script>
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('navLinks');
  hamburger.addEventListener('click', () => navLinks.classList.toggle('open'));
</script>
</body>
</html>
