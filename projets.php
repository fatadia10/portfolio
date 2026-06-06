<?php require 'fonctions.php'; ?>

<?php
session_start();
require 'config/connexion.php';
enregistrer_visite($pdo, 'projets.php');
$mot_cle   = nettoyer($_GET['q'] ?? '');
$resultats = [];
if ($mot_cle !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM projets 
         WHERE titre LIKE ? OR description LIKE ? 
         ORDER BY date_creation DESC"
    );
    $terme = '%' . $mot_cle . '%';
    $stmt->execute([$terme, $terme]);
} else {
    $stmt = $pdo->query(
        "SELECT * FROM projets ORDER BY date_creation DESC"
    );
}

$resultats = $stmt->fetchAll();
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
        <div class="project-thumb" style="background:linear-gradient(135deg, var(--violet-pale), var(--violet-mist)); display:flex; align-items:center; justify-content:center; font-size:3rem; aspect-ratio:16/10;">
            <?php if ($projet['image']): ?>
                <img src="images/projets/<?= htmlspecialchars($projet['image']) ?>" 
                     alt="<?= htmlspecialchars($projet['titre']) ?>"
                     style="width:100%;height:100%;object-fit:cover;" />
            <?php else: ?>
                🖥️
            <?php endif; ?>
        </div>
        <div class="project-info">
            <div class="project-tags">
                <?php foreach (explode(',', $projet['technologies']) as $tech): ?>
                    <span class="tag"><?= htmlspecialchars(trim($tech)) ?></span>
                <?php endforeach; ?>
            </div>
            <h3><?= htmlspecialchars($projet['titre']) ?></h3>
            <p><?= htmlspecialchars($projet['description']) ?></p>
            <?php if ($projet['lien']): ?>
                <a href="<?= htmlspecialchars($projet['lien']) ?>" target="_blank" class="project-link">Voir le projet →</a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<?php if (empty($resultats)): ?>
    <p style="text-align:center; color:var(--text-light); padding:3rem;">
        Aucun projet trouvé pour "<?= htmlspecialchars($mot_cle) ?>".
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
