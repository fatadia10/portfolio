<?php require 'fonctions.php'; ?>

<?php
$erreurs = [];
$succes  = false;
$nom     = '';
$email   = '';
$sujet   = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_contact'])) {
    $nom     = nettoyer($_POST['prenom'] ?? '');
    $email   = nettoyer($_POST['email'] ?? '');
    $sujet   = nettoyer($_POST['sujet'] ?? '');
    $message = nettoyer($_POST['message'] ?? '');
    if (!champ_requis($nom))     $erreurs['nom']     = 'Le nom est obligatoire.';
    if (!champ_requis($email) || !email_valide($email)) $erreurs['email'] = 'L\'adresse email est invalide.';
    if (!champ_requis($message)) $erreurs['message'] = 'Le message ne peut pas être vide.';
    if (empty($erreurs)) {
        $succes = true;
        $nom = $email = $sujet = $message = '';
    }
}

$erreurs_projet  = [];
$succes_projet   = false;
$proj_prenom     = '';
$proj_nom        = '';
$proj_email      = '';
$proj_type       = '';
$proj_budget     = '';
$proj_delai      = '';
$proj_description = '';
$demande         = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_projet'])) {
    $proj_prenom      = nettoyer($_POST['prenom'] ?? '');
    $proj_nom         = nettoyer($_POST['nom'] ?? '');
    $proj_email       = nettoyer($_POST['email'] ?? '');
    $proj_type        = nettoyer($_POST['type'] ?? '');
    $proj_budget      = nettoyer($_POST['budget'] ?? '');
    $proj_delai       = nettoyer($_POST['delai'] ?? '');
    $proj_description = nettoyer($_POST['description'] ?? '');
    if (!champ_requis($proj_prenom))      $erreurs_projet['prenom']      = 'Le prénom est obligatoire.';
    if (!champ_requis($proj_nom))         $erreurs_projet['nom']         = 'Le nom est obligatoire.';
    if (!champ_requis($proj_email) || !email_valide($proj_email)) $erreurs_projet['email'] = 'L\'adresse email est invalide.';
    if (!champ_requis($proj_type))        $erreurs_projet['type']        = 'Le type de projet est obligatoire.';
    if (!champ_requis($proj_description)) $erreurs_projet['description'] = 'La description est obligatoire.';
    if (empty($erreurs_projet)) {
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
    .page-hero {
      padding: 140px 0 5rem;
      background: linear-gradient(135deg, var(--violet-mist), var(--white));
      text-align: center;
    }
    .page-hero p { max-width: 500px; margin: 1rem auto 0; color: var(--text-light); }
    .contact-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; margin-bottom: 3rem; }
    .contact-info { display: flex; flex-direction: column; gap: 1.5rem; }
    .info-card {
      background: var(--white); border-radius: var(--radius); padding: 1.5rem;
      border: 1px solid var(--border); display: flex; gap: 1rem; align-items: flex-start;
      transition: var(--transition);
    }
    .info-card:hover { box-shadow: var(--shadow); transform: translateY(-3px); }
    .info-icon {
      font-size: 1.6rem; width: 48px; height: 48px; display: flex; align-items: center;
      justify-content: center; background: var(--violet-pale); border-radius: var(--radius-sm); flex-shrink: 0;
    }
    .info-text h4 { margin-bottom: 0.2rem; font-size: 0.9rem; }
    .info-text p { font-size: 0.85rem; color: var(--text-light); }
    .info-text a { color: var(--violet-main); font-weight: 600; font-size: 0.9rem; }
    .success-msg {
      display: none; background: #f0fdf4; border: 1.5px solid #86efac; color: #166534;
      border-radius: var(--radius-sm); padding: 1rem 1.4rem; font-size: 0.9rem;
      font-weight: 600; margin-top: 1rem;
    }
    .success-msg.show { display: block; }
    .divider { border: none; border-top: 1px solid var(--border); margin: 3rem 0; }
    @media (max-width: 768px) { .contact-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

 <?php require 'composants/navigation.php'; ?>

  <!-- PAGE HERO -->
  <section class="page-hero">
    <div class="container">
      <span class="section-label">Restons en contact</span>
      <h1 style="font-size:clamp(2rem,5vw,3.5rem);margin-top:1rem;">Me contacter</h1>
      <p>Une question, une idée ou un projet ? Je lis tous les messages et réponds dans les 48h.</p>
    </div>
  </section>

  <!-- CONTACT SECTION -->
  <section class="section contact-section">
    <div class="container">

      <!-- FORM 1 : Contact -->
      <div class="section-header" style="text-align:left;margin-bottom:2rem;">
        <span class="section-label">Formulaire de contact</span>
        <h2 style="margin-top:0.5rem;">Envoyer un message</h2>
      </div>

      <div class="contact-grid">
        <div class="contact-info">
          <div class="info-card">
            <div class="info-icon">📧</div>
            <div class="info-text">
              <h4>Email</h4>
              <a href="mailto:diabineta2006@gmail.com">diabineta2006@gmail.com</a>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">📸</div>
            <div class="info-text">
              <h4>Instagram</h4>
              <a href="https://www.instagram.com/princessedia3" target="_blank">@princessedia3</a>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">💬</div>
            <div class="info-text">
              <h4>WhatsApp</h4>
              <a href="https://wa.me/221788701318" target="_blank">+221 78 870 13 18</a>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">💼</div>
            <div class="info-text">
              <h4>LinkedIn</h4>
              <a href="https://www.linkedin.com/in/bineta-dia-949042400/" target="_blank">linkedin.com/in/bineta-dia</a>
            </div>
          </div>
                    <div class="info-card">
            <div class="info-icon">🐙</div>
            <div class="info-text">
              <h4>GitHub</h4>
              <a href="#">github.com/bineta-dia</a>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">🎓</div>
            <div class="info-text">
              <h4>Formations</h4>
              <p>ESTM & UNCHK — Dakar</p>
            </div>
          </div>
          <div class="info-card">
            <div class="info-icon">📍</div>
            <div class="info-text">
              <h4>Localisation</h4>
              <p>Dakar, Sénégal</p>
            </div>
          </div>
        </div>

        <div class="form-card">
          <h3>💬 Écrivez-moi</h3>
          <p>Pour toute question, collaboration ou simplement dire bonjour !</p>
          <?php if ($succes): ?>
    <div style="background:#f0fdf4; border-left:4px solid #22c55e; padding:1rem; margin-bottom:1rem; border-radius:8px;">
        Merci, votre message a bien été envoyé !
      
    </div>
          <?php endif; ?>
          <form id="contactForm" method="POST" novalidate>
           <input type="hidden" name="form_contact" value="1" />
            <div class="form-row">
              <div class="form-group">
                <label for="contact-prenom">Prénom *</label>
                <input type="text" id="contact-prenom" name="prenom" 
                value="<?= $nom ?>" placeholder="Votre prénom" />
                <?php if (isset($erreurs['prenom'])): ?>
    <span style="color:red; font-size:0.8rem;"><?= $erreurs['prenom'] ?></span>
        <?php endif; ?>
              </div>
              <div class="form-group">
                <label for="contact-nom">Nom *</label>
                <input type="text" id="contact-nom" name="nom" 
                value="<?= $nom ?>" placeholder="Votre nom" />
              </div>
            </div>
            <div class="form-group">
              <label for="contact-email">Email *</label>
              <input type="email" id="contact-email" name="email"
               value="<?= $email ?>" placeholder="votre@email.com"/>
               <?php if (isset($erreurs['email'])): ?>
    <span style="color:red; font-size:0.8rem;"><?= $erreurs['email'] ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="contact-sujet">Sujet</label>
              <input type="text" id="contact-sujet" name="sujet" 
              value="<?= $sujet ?>" placeholder="Ex : Collaboration, stage, question..." />
            </div>
            <div class="form-group">
              <label for="contact-message">Message *</label>
              <textarea id="contact-message" name="message" rows="5" 
              placeholder="Votre message ici..."><?= $message ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le message ✦</button>
          </form>
          <div class="success-msg" id="contactSuccess">
            ✅ Merci pour votre message ! Bineta vous répondra dans les 48h.
          </div>
        </div>
      </div>

      <hr class="divider" />

      <!-- FORM 2 : Demande de projet -->
      <div id="project-form">
        <div class="section-header" style="text-align:left;margin-bottom:2rem;">
          <span class="section-label">Projet à réaliser</span>
          <h2 style="margin-top:0.5rem;">Demander un projet</h2>
        </div>

        <div class="form-card-full">
          <h3> Décrivez votre projet</h3>
          <p>Vous avez une idée de site web ou d'application ? Remplissez ce formulaire et je vous recontacte rapidement.</p>
          <?php if ($succes_projet): ?>
    <div style="background:#f0fdf4; border-left:4px solid #22c55e; padding:1.5rem; margin-bottom:1.5rem; border-radius:8px;">
        <strong>✅ Demande reçue ! Voici le récapitulatif :</strong><br><br>
        Nom complet : <?= $demande['prenom'] ?> <?= $demande['nom'] ?><br>
        Email : <?= $demande['email'] ?><br>
        Type de projet : <?= $demande['type'] ?><br>
        Budget : <?= $demande['budget'] ?: 'Non précisé' ?><br>
        Délai : <?= $demande['delai'] ?: 'Non précisé' ?><br>
        Description : <?= $demande['description'] ?>
    </div>
<?php endif; ?>

<form id="projectForm" method="POST" novalidate>
            <input type="hidden" name="form_projet" value="1" />
            <div class="form-row">
              <div class="form-group">
                <label for="proj-prenom">Prénom *</label>
                <input type="text" id="proj-prenom" name="prenom" value="<?= $proj_prenom ?>" placeholder="Votre prénom" />
                <?php if (isset($erreurs_projet['prenom'])): ?>
    <span style="color:red; font-size:0.8rem;"><?= $erreurs_projet['prenom'] ?></span>
        <?php endif; ?>
              </div>
            <div class="form-group">
           <label for="proj-nom">Nom *</label>
           <input type="text" id="proj-nom" name="nom" value="<?= $proj_nom ?>" placeholder="Votre nom" />
           <?php if (isset($erreurs_projet['nom'])): ?>
           <span style="color:red; font-size:0.8rem;"><?= $erreurs_projet['nom'] ?></span>
            <?php endif; ?>
              </div>
              <div class="form-group">
               <label for="proj-email">Email *</label>
             <input type="email" id="proj-email" name="email" value="<?= $proj_email ?>" placeholder="votre@email.com" />
             <?php if (isset($erreurs_projet['email'])): ?>
            <span style="color:red; font-size:0.8rem;"><?= $erreurs_projet['email'] ?></span>
           <?php endif; ?>
            </div>
        
              <div class="form-group">
                <label for="proj-tel">Téléphone</label>
                <input type="tel" id="proj-tel" name="telephone" placeholder="+221 77 000 00 00" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="proj-type">Type de projet *</label>
                <select id="proj-type" name="type" required>
                  <option value="">Sélectionner...</option>
                  <option value="vitrine">Site vitrine</option>
                  <option value="portfolio">Portfolio</option>
                  <option value="ecommerce">Boutique en ligne</option>
                  <option value="blog">Blog</option>
                  <option value="application">Application web</option>
                  <option value="autre">Autre</option>
                </select>
                 <?php if (isset($erreurs_projet['type'])): ?>
                 <span style="color:red; font-size:0.8rem;"><?= $erreurs_projet['type'] ?></span>
                  <?php endif; ?>
              </div>
              <div class="form-group">
                <label for="proj-budget">Budget estimé</label>
                <select id="proj-budget" name="budget">
                  <option value="">Sélectionner...</option>
                  <option value="petit">Moins de 50 000 FCFA</option>
                  <option value="moyen">50 000 – 150 000 FCFA</option>
                  <option value="grand">150 000 – 300 000 FCFA</option>
                  <option value="libre">À discuter</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="proj-delai">Délai souhaité</label>
              <input type="text" id="proj-delai" name="delai" value="<?= $proj_delai ?>" placeholder="Ex : 1 mois, avant fin mai..." />
            </div>
            <div class="form-group">
              <label for="proj-description">Description du projet *</label>
              <textarea id="proj-description" name="description" rows="5" placeholder="Décrivez votre projet : objectif, public cible, fonctionnalités souhaitées..." required><?= $proj_description ?></textarea>
            <?php if (isset($erreurs_projet['description'])): ?>
              <span style="color:red; font-size:0.8rem;"><?= $erreurs_projet['description'] ?></span>
            <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="proj-ref">Sites de référence / inspiration</label>
              <input type="text" id="proj-ref" name="references" value="<?= $proj_references ?>" placeholder="URL ou noms de sites qui vous inspirent..." />
            </div>
            <button type="submit" class="btn btn-primary">Soumettre le projet ✦</button>
          </form>
          <div class="success-msg" id="projectSuccess">
            Votre demande a bien été reçue ! Bineta vous contacte dans les 48h.
          </div>
        </div>
      </div>

    </div>
  </section>

  <?php require 'composants/pied-de-page.php'; ?>
  <script>
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    hamburger.addEventListener('click', () => navLinks.classList.toggle('open'));


    document.getElementById('projectForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const required = this.querySelectorAll('[required]');
      let valid = true;
      required.forEach(field => {
        if (!field.value.trim()) { field.style.borderColor = '#ef4444'; valid = false; }
        else { field.style.borderColor = ''; }
      });
      if (valid) {
        document.getElementById('projectSuccess').classList.add('show');
        this.reset();
        setTimeout(() => document.getElementById('projectSuccess').classList.remove('show'), 5000);
      }
    });
  </script>
</body>
</html>
