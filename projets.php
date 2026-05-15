<?php require 'fonctions.php'; ?>

<?php

$projets = [
    [
        'titre'        => 'SecureAccess Manager',
        'description'  => 'Application console en C pour la gestion d\'utilisateurs avec niveaux d\'accès et sauvegarde binaire.',
        'technologies' => ['Langage C', 'Cybersécurité'],
        'emoji'        => '🔐'
    ],
    [
        'titre'        => 'Poubelle Intelligente',
        'description'  => 'Système IoT avec ESP32 : ouverture automatique via capteur ultrasonique, LCD, LEDs et monitoring WiFi.',
        'technologies' => ['Arduino', 'ESP32', 'IoT', 'C++'],
        'emoji'        => '🗑️'
    ],
    [
        'titre'        => 'Répertoire de Contacts',
        'description'  => 'Application console en C avec base de données SQLite. CRUD complet avec requêtes SQL.',
        'technologies' => ['Langage C', 'SQLite', 'SQL'],
        'emoji'        => '📒'
    ],
    [
        'titre'        => 'Exercices PHP',
        'description'  => 'Premiers pas en PHP : fonctions, variables dynamiques, calcul d\'âge et pages interactives.',
        'technologies' => ['PHP', 'HTML'],
        'emoji'        => '🐘'
    ],
    [
        'titre'        => 'Portfolio personnel',
        'description'  => 'Portfolio multipage responsive en HTML/CSS déployé sur GitHub Pages.',
        'technologies' => ['HTML', 'CSS'],
        'emoji'        => '🌐'
    ],
];

$mot_cle   = nettoyer($_GET['q'] ?? '');
$resultats = [];

if ($mot_cle !== '') {
    foreach ($projets as $projet) {
        if (stripos($projet['titre'], $mot_cle) !== false ||
            stripos($projet['description'], $mot_cle) !== false) {
            $resultats[] = $projet;
        }
    }
} else {
    $resultats = $projets;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Projets — Bineta DIA</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .page-hero {
      padding: 140px 0 5rem;
      background: linear-gradient(135deg, var(--violet-mist), var(--white));
      text-align: center;
    }
    .page-hero p { max-width: 540px; margin: 1rem auto 0; color: var(--text-light); }
    .search-section { background: var(--white); padding: 3rem 0; border-bottom: 1px solid var(--border); }
    .search-wrap { max-width: 700px; margin: 0 auto; }
    .search-wrap h3 { margin-bottom: 0.5rem; }
    .search-wrap p { margin-bottom: 1.5rem; color: var(--text-light); font-size:0.9rem; }
    .search-form { display: flex; gap: 0.8rem; flex-wrap: wrap; }
    .search-form input { flex: 1; min-width: 200px; }
    .filter-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
    .chip {
      padding: 0.35rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
      cursor: pointer; border: 1.5px solid var(--border); background: var(--bg);
      color: var(--text-mid); transition: var(--transition);
    }
    .chip.active, .chip:hover { background: var(--violet-main); color: var(--white); border-color: var(--violet-main); }
    .projects-section { padding: 5rem 0; }
    .projects-big-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; }
    .no-result { text-align: center; padding: 4rem; color: var(--text-light); display: none; }
    .no-result.visible { display: block; }
  </style>
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <!-- PAGE HERO -->
  <section class="page-hero">
    <div class="container">
      <span class="section-label">Mes réalisations</span>
      <h1 style="font-size:clamp(2rem,5vw,3.5rem);margin-top:1rem;">Mes projets</h1>
      <p>Des projets réalisés à l'ESTM et à l'UNCHK. Chaque projet représente une étape de mon apprentissage.</p>
    </div>
  </section>

  <!-- SEARCH FORM -->
  <section class="search-section">
    <div class="container">
      <div class="search-wrap">
        <h3>🔍 Rechercher un projet</h3>
        <p>Tape un mot-clé ou filtre par technologie pour trouver un projet précis.</p>
       <form method="GET" action="projets.php" class="search-form">
    <input type="search" name="q"
           value="<?= $mot_cle ?>"
           placeholder="Ex : sécurité, PHP, C..." />
    <button type="submit" class="btn btn-primary">Rechercher</button>
    <?php if ($mot_cle): ?>
        <a href="projets.php" class="btn btn-outline">Réinitialiser</a>
    <?php endif; ?>
</form>
        <div class="filter-chips">
          <button class="chip active" onclick="filterByTag('all', this)">Tous</button>
          <button class="chip" onclick="filterByTag('c', this)">Langage C</button>
          <button class="chip" onclick="filterByTag('php', this)">PHP</button>
          <button class="chip" onclick="filterByTag('html', this)">HTML</button>
          <button class="chip" onclick="filterByTag('css', this)">CSS</button>
          <button class="chip" onclick="filterByTag('cybersécurité', this)">Cybersécurité</button>
          <button class="chip" onclick="filterByTag('arduino', this)">Arduino/IoT</button>
          <button class="chip" onclick="filterByTag('sqlite', this)">SQLite</button>
        </div>
      </div>
    </div>
  </section>

  <!-- PROJECTS GRID -->
  <section class="projects-section">
    <div class="container">
      <div class="projects-big-grid" id="projectsGrid">

        <?php foreach ($resultats as $projet): ?>
    <div class="project-card">
        <div class="project-thumb">
            <?= $projet['emoji'] ?>
        </div>
        <div class="project-info">
            <div class="project-tags">
                <?php foreach ($projet['technologies'] as $tech): ?>
                    <span class="tag"><?= htmlspecialchars($tech) ?></span>
                <?php endforeach; ?>
            </div>
            <h3><?= htmlspecialchars($projet['titre']) ?></h3>
            <p><?= htmlspecialchars($projet['description']) ?></p>
        </div>
    </div>
<?php endforeach; ?>

<?php if (empty($resultats)): ?>
    <p style="text-align:center; color:var(--text-light); padding:3rem;">
        Aucun projet trouvé pour "<?= $mot_cle ?>".
    </p>
<?php endif; ?><?php foreach ($resultats as $projet): ?>
    <div class="project-card">
        <div class="project-thumb">
            <?= $projet['emoji'] ?>
        </div>
        <div class="project-info">
            <div class="project-tags">
                <?php foreach ($projet['technologies'] as $tech): ?>
                    <span class="tag"><?= htmlspecialchars($tech) ?></span>
                <?php endforeach; ?>
            </div>
            <h3><?= htmlspecialchars($projet['titre']) ?></h3>
            <p><?= htmlspecialchars($projet['description']) ?></p>
        </div>
    </div>
<?php endforeach; ?>

<?php if (empty($resultats)): ?>
    <p style="text-align:center; color:var(--text-light); padding:3rem;">
        Aucun projet trouvé pour "<?= $mot_cle ?>".
    </p>
<?php endif; ?>
      <div class="no-result" id="noResult">
        <p style="font-size:2rem;">🔍</p>
        <p style="margin-top:1rem;">Aucun projet trouvé pour cette recherche.</p>
        <button class="btn btn-outline" onclick="resetSearch()" style="margin-top:1.5rem;">Voir tous les projets</button>
      </div>
    </div>
  </section>

  <?php require 'composants/pied-de-page.php'; ?>
  <script>
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    hamburger.addEventListener('click', () => navLinks.classList.toggle('open'));
</script>
   </body>
</html>
