<?php require 'fonctions.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bineta DIA — Portfolio Développeuse Web</title>
  <meta name="description" content="Portfolio de Bineta DIA, étudiante en Génie Logiciel, Cybersécurité et Administration Réseau." />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <?php require 'composants/navigation.php'; ?>

  <!-- HERO -->
  <section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="container">
      <div class="hero-content">
        <div class="hero-text">
          <div class="hero-badge">Étudiante en Génie Logiciel & Cybersécurité</div>
          <h1 class="hero-title">
            Bonjour, je suis<br>
            <span class="highlight">Bineta DIA</span>
          </h1>
          <p class="hero-desc">
            Passionnée par le développement web, la cybersécurité et la programmation. J'apprends chaque jour à créer des solutions numériques solides et élégantes.
          </p>
          <div class="hero-actions">
            <a href="projets.php" class="btn btn-primary">Voir mes projets ✦</a>
            <a href="contact.php" class="btn btn-outline">Me contacter</a>
          </div>
          <div class="hero-stats">
            <div class="stat-item">
              <div class="stat-number">5+</div>
              <div class="stat-label">Projets réalisés</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">2</div>
              <div class="stat-label">Filières en cours</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">10+</div>
              <div class="stat-label">Technologies apprises</div>
            </div>
          </div>
        </div>
        <div class="hero-visual">
          <div class="avatar-wrapper">
            <div class="avatar-ring"></div>
            <div class="avatar-circle"><img src="photo.jpg" alt="Bineta DIA" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" /></div>
            <div class="avatar-tag">
              <div class="avatar-tag-dot"></div>
              Disponible
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section" id="about">
    <div class="container">
      <div class="about-grid">
        <div class="about-img-wrap">
          <div class="about-img-frame" style="font-size:0;padding:0;"><img src="photo.jpg" alt="Bineta DIA" style="width:100%;height:100%;object-fit:cover;border-radius:2rem;" /></div>
          <div class="about-card">🎓 ESTM & UNCHK — 2024/2026</div>
        </div>
        <div class="about-text">
          <span class="section-label">À propos de moi</span>
          <h2>Code, sécurité<br>et passion du numérique</h2>
          <p>
            Je m'appelle Bineta DIA, étudiante en 2ème année de Génie Logiciel & Administration Réseau à l'ESTM, et simultanément en Cybersécurité à l'UNCHK. Ce double parcours me donne une vision complète du monde du numérique.
          </p>
          <p>
            Ce qui me passionne, c'est de comprendre comment les systèmes fonctionnent — et comment les sécuriser. Du code C à la création web, j'explore chaque jour de nouveaux horizons techniques.
          </p>
          <div class="about-details">
            <div class="detail-item">
              <span class="detail-label">Prénom & Nom</span>
              <span class="detail-value">Bineta DIA</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Statut</span>
              <span class="detail-value">Étudiante</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Formations</span>
              <span class="detail-value">ESTM & UNCHK</span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Email</span>
              <span class="detail-value">diabineta2006@gmail.com</span>
            </div>
          </div>
          <a href="contact.php" class="btn btn-primary">Travaillons ensemble ✦</a>
        </div>
      </div>
    </div>
  </section>

  <!-- SKILLS -->
  <section class="section skills-section" id="skills">
    <div class="container">
      <div class="section-header">
        <span class="section-label">Compétences</span>
        <h2>Ce que je sais faire</h2>
        <p>Les technologies que j'apprends et maîtrise au fil de mes formations.</p>
      </div>
      <div class="skills-grid">
        <div class="skill-card">
          <div class="skill-icon">🌐</div>
          <h4>HTML & CSS</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — Structure, design responsive</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 65%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">💻</div>
          <h4>Langage C</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Expérimenté — Programmation système</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 80%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">🐍</div>
          <h4>Python</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — Scripts & automatisation</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 60%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">⚙️</div>
          <h4>PHP</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Débutant — Développement back-end</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 30%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">🗄️</div>
          <h4>MySQL</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Débutant — Bases de données relationnelles</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 30%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">🐙</div>
          <h4>GitHub</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — Versioning & collaboration</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 60%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">🔌</div>
          <h4>Arduino / ESP32</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — IoT &amp; systèmes embarqués</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 55%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">☕</div>
          <h4>Java</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — POO &amp; héritage</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 55%"></div>
          </div>
        </div>
        <div class="skill-card">
          <div class="skill-icon">🗄️</div>
          <h4>SQLite</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — Bases de données embarquées</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 50%"></div>
          </div>
        </div>
                <div class="skill-card">
          <div class="skill-icon">🎨</div>
          <h4>Photoshop</h4>
          <p style="font-size:0.85rem;color:var(--text-light);">Intermédiaire — Retouche & design graphique</p>
          <div class="skill-bar-wrap">
            <div class="skill-bar" style="width: 60%"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- PROJECTS PREVIEW -->
  <section class="section" id="projects">
    <div class="container">
      <div class="section-header">
        <span class="section-label">Projets</span>
        <h2>Mes réalisations</h2>
        <p>Des projets concrets réalisés pendant mes formations à l'ESTM et à l'UNCHK.</p>
      </div>
      <div class="projects-grid">
        <div class="project-card">
          <div class="project-thumb project-thumb-1">🔐</div>
          <div class="project-info">
            <div class="project-tags">
              <span class="tag">Langage C</span><span class="tag">Cybersécurité</span>
            </div>
            <h3>SecureAccess Manager</h3>
            <p>Application console en C pour la gestion d'utilisateurs avec niveaux d'accès, vérification de mots de passe et sauvegarde en fichier binaire.</p>
            <div class="project-links">
              <a href="projets.php" class="project-link">Voir le projet →</a>
            </div>
          </div>
        </div>
        <div class="project-card">
          <div class="project-thumb project-thumb-4">🗑️</div>
          <div class="project-info">
            <div class="project-tags">
              <span class="tag">Arduino</span><span class="tag">ESP32</span><span class="tag">IoT</span>
            </div>
            <h3>Poubelle Intelligente</h3>
            <p>Système IoT avec ESP32 : ouverture automatique via capteur ultrasonique, affichage LCD, LEDs, buzzer et monitoring WiFi en temps réel.</p>
            <div class="project-links">
              <a href="projets.php" class="project-link">Voir le projet →</a>
            </div>
          </div>
        </div>
        <div class="project-card">
          <div class="project-thumb project-thumb-5">📒</div>
          <div class="project-info">
            <div class="project-tags">
              <span class="tag">Langage C</span><span class="tag">SQLite</span>
            </div>
            <h3>Répertoire de Contacts</h3>
            <p>Application console en C avec base de données SQLite. Gestion complète des contacts avec requêtes SQL (ajout, recherche, modification).</p>
            <div class="project-links">
              <a href="projets.php" class="project-link">Voir le projet →</a>
            </div>
          </div>
        </div>
      </div>
      <div style="text-align:center;margin-top:3rem;">
        <a href="projets.php" class="btn btn-outline">Voir tous les projets</a>
      </div>
    </div>
  </section>

  <!-- PARCOURS -->
  <section class="section experience-section" id="experience">
    <div class="container">
      <div class="section-header">
        <span class="section-label">Parcours</span>
        <h2>Mon chemin</h2>
        <p>Les étapes clés de mon parcours académique.</p>
      </div>
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-date">2025 / 2026</div>
          <div class="timeline-card">
            <h3>2ème année — Génie Logiciel & Admin. Réseau</h3>
            <div class="timeline-company">ESTM — Dakar</div>
            <p>Approfondissement en développement logiciel, administration réseau et gestion de systèmes informatiques.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-date">2025 / 2026</div>
          <div class="timeline-card">
            <h3>2ème année — Cybersécurité</h3>
            <div class="timeline-company">UNCHK — Dakar</div>
            <p>Approfondissement en sécurité des systèmes d'information, cryptographie et protection des données.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-date">2024 / 2025</div>
          <div class="timeline-card">
            <h3>1ère année — Génie Logiciel & Admin. Réseau + Cybersécurité</h3>
            <div class="timeline-company">ESTM & UNCHK — Dakar</div>
            <p>Début du double parcours : programmation en C, Python, bases du web (HTML/CSS/PHP) et introduction à la cybersécurité.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-date">2023 / 2024</div>
          <div class="timeline-card">
            <h3>Obtention du Baccalauréat</h3>
            <div class="timeline-company">Dakar, Sénégal</div>
            <p>Obtention du baccalauréat, ouvrant la voie aux études supérieures en informatique.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-date">2020 / 2021</div>
          <div class="timeline-card">
            <h3>Brevet de Fin d'Études Moyen (BFEM)</h3>
            <div class="timeline-company">Dakar, Sénégal</div>
            <p>Premier diplôme officiel, marquant la fin du cycle moyen avec succès.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php require 'composants/pied-de-page.php'; ?>

  <script>
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    });
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    hamburger.addEventListener('click', () => navLinks.classList.toggle('open'));
    hamburger.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') navLinks.classList.toggle('open');
    });
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => navLinks.classList.remove('open'));
    });
  </script>
</body>
</html>
