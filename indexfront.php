<?php
// filepath: c:\xampp\htdocs\ttt\index.php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Visites Urbaines</title>

  <!-- Favicons -->
  <link href="./assets/img/favicon-urbanisme.png" rel="icon">
  <link href="./assets/img/apple-touch-icon-urbanisme.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="./assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="./assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="./assets/css/main.css" rel="stylesheet">
</head>

<body>
  <?php include './templates/header-index.php'; ?>

  <main id="main" class="container mt-5">
    <section class="hero-section text-center">
      <h1>Bienvenue sur Visites Urbaines</h1>
      <p>Explorez les projets de développement durable et découvrez des initiatives pour un urbanisme responsable.</p>
      <a href="./Views/frontoffice/login.php" class="btn btn-primary">Se connecter</a>
      <a href="./Views/frontoffice/register.php" class="btn btn-outline-secondary">Créer un compte</a>
    </section>

    <section class="features-section mt-5">
      <div class="row">
        <div class="col-md-4" data-aos="fade-up">
          <div class="feature-box">
            <i class="bi bi-building"></i>
            <h3>Projets Urbains</h3>
            <p>Découvrez des projets innovants pour des villes durables et intelligentes.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-box">
            <i class="bi bi-people"></i>
            <h3>Communauté</h3>
            <p>Rejoignez une communauté engagée pour un avenir urbain meilleur.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-box">
            <i class="bi bi-globe"></i>
            <h3>Durabilité</h3>
            <p>Apprenez-en plus sur les initiatives pour un développement durable.</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include './templates/footer-auth.php'; ?>

  <!-- Vendor JS Files -->
  <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/vendor/aos/aos.js"></script>
  <script src="./assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="./assets/js/main.js"></script>
</body>

</html>