<?php
/**
 * Header template for the corporate website
 * Includes top bar, logo, and navigation menu
 */
require_once __DIR__ . '/../config.php';
$settings = getSiteSettings();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_title'] ?? 'Kurumsal Web Sitesi'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/topbar.css?v=<?php echo time(); ?>">
    
    <!-- Bootstrap JS (Header bölümünde) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Özel Renk Şeması Değişkenleri -->
    <?php 
    // Sayfayı her yenilemede taze renk şeması değişkenlerini almak için önbelleği atlama
    $cacheBuster = time(); 
    echo getColorSchemeVariables(); 
    ?>
    
    <!-- Ek Stil Geçersiz Kılmalar -->
    <style>
        /* Menü için özel stiller */
        .navbar .nav-link:hover, 
        .navbar .nav-link.active {
            color: var(--primary-color) !important;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Top bar için özel renk şeması uygulaması */
        .top-bar .social-links a:hover {
            color: var(--accent-color) !important;
        }
        
        /* Ana içerik alanı için stil geçişleri */
        .main-content {
            background-color: #fff;
            color: var(--dark-color);
        }
        
        /* Butonlar ve bağlantı renkleri */
        .main-content .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .main-content .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Genel bileşenler için renk şeması */
        .service-card {
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        /* Geçiş animasyonları */
        .service-card, .gallery-item, .reference-card, .social-links a {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar bg-dark text-light py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="contact-info">
                        <?php if (!empty($settings['contact_phone'])): ?>
                            <span class="me-3"><i class="fas fa-phone-alt me-2"></i><?php echo $settings['contact_phone']; ?></span>
                        <?php endif; ?>
                        <?php if (!empty($settings['contact_email'])): ?>
                            <span><i class="fas fa-envelope me-2"></i><?php echo $settings['contact_email']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <?php $facebookUrl = getSocialMediaUrl($settings['facebook_url'] ?? ''); ?>
                        <?php if (!empty($facebookUrl)): ?>
                            <a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" class="text-light" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        
                        <?php $twitterUrl = getSocialMediaUrl($settings['twitter_url'] ?? ''); ?>
                        <?php if (!empty($twitterUrl)): ?>
                            <a href="<?php echo htmlspecialchars($twitterUrl); ?>" target="_blank" class="text-light" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        
                        <?php $instagramUrl = getSocialMediaUrl($settings['instagram_url'] ?? ''); ?>
                        <?php if (!empty($instagramUrl)): ?>
                            <a href="<?php echo htmlspecialchars($instagramUrl); ?>" target="_blank" class="text-light" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        
                        <?php $linkedinUrl = getSocialMediaUrl($settings['linkedin_url'] ?? ''); ?>
                        <?php if (!empty($linkedinUrl)): ?>
                            <a href="<?php echo htmlspecialchars($linkedinUrl); ?>" target="_blank" class="text-light" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        
                        <?php $youtubeUrl = getSocialMediaUrl($settings['youtube_url'] ?? ''); ?>
                        <?php if (!empty($youtubeUrl)): ?>
                            <a href="<?php echo htmlspecialchars($youtubeUrl); ?>" target="_blank" class="text-light" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Header / Navbar -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>/index.php">
                    <img src="<?php echo SITE_URL; ?>/assets/img/logo.png" alt="<?php echo htmlspecialchars($settings['site_title'] ?? 'Kurumsal Web Sitesi'); ?>" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/index.php">Ana Sayfa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'hakkimizda.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/pages/hakkimizda.php">Hakkımızda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'hizmetler.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/pages/hizmetler.php">Hizmetlerimiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'galeri.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/pages/galeri.php">Galeri</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'referanslar.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/pages/referanslar.php">Referanslar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'iletisim.php') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>/pages/iletisim.php">İletişim</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Anasayfa içeriği buraya gelecek -->
    </main>
</body>
</html>