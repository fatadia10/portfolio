<?php
$page_courante = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar" id="navbar">
  <div class="container">
    <div class="nav-inner">
      <a href="index.php" class="nav-logo">B<span>.</span>DIA</a>
      <ul class="nav-links" id="navLinks">
        <li><a href="index.php#about" <?php if($page_courante==='index.php') echo 'style="color:var(--violet-main)"'; ?>>À propos</a></li>
        <li><a href="index.php#skills" <?php if($page_courante==='index.php') echo 'style="color:var(--violet-main)"'; ?>>Compétences</a></li>
        <li><a href="projets.php" <?php if($page_courante==='projets.php') echo 'style="color:var(--violet-main)"'; ?>>Projets</a></li>
        <li><a href="index.php#experience" <?php if($page_courante==='index.php') echo 'style="color:var(--violet-main)"'; ?>>Parcours</a></li>
        <li><a href="contact.php" <?php if($page_courante==='contact.php') echo 'style="color:var(--violet-main)"'; ?>>Contact</a></li>
      </ul>
      <div class="hamburger" id="hamburger" aria-label="Menu" role="button" tabindex="0">
        <span></span><span></span><span></span>
      </div>
    </div>
  </div>
</nav>
