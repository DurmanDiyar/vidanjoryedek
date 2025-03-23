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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php
    // Sayfa bazlı title ve description belirleme
    $page_title = htmlspecialchars($settings['site_title'] ?? 'Kurumsal Web Sitesi');
    $page_description = htmlspecialchars($settings['site_description'] ?? 'Profesyonel kurumsal hizmetler sunan web sitemize hoş geldiniz.');
    $page_keywords = htmlspecialchars($settings['site_keywords'] ?? 'kurumsal, hizmetler, profesyonel');
    
    // Sayfa bazlı özel title ve description atama
    if (isset($current_page)) {
        switch ($current_page) {
            case 'hizmetler.php':
                $page_title = 'Hizmetlerimiz - ' . $page_title;
                $page_description = 'Sunduğumuz tüm profesyonel hizmetler. ' . ($settings['site_description'] ?? '');
                $page_keywords .= ', hizmet çeşitleri, çözümler';
                break;
            case 'hizmet-detay.php':
                // $service değişkeni tanımlı değilse boş bir array olarak ayarla
                if (!isset($service) || !is_array($service)) {
                    $service = [];
                }
                if (isset($service['name'])) {
                    $page_title = htmlspecialchars($service['name']) . ' - ' . $page_title;
                    $page_description = mb_substr(strip_tags($service['description'] ?? ''), 0, 160) . '...';
                    $page_keywords .= ', ' . htmlspecialchars($service['name']) . ', detaylı hizmet bilgisi';
                }
                break;
            case 'hakkimizda.php':
                $page_title = 'Hakkımızda - ' . $page_title;
                $page_description = 'Firmamız hakkında bilgi edinin. ' . ($settings['site_description'] ?? '');
                $page_keywords .= ', şirket profili, kurumsal kimlik, tarihçe';
                break;
            case 'iletisim.php':
                $page_title = 'İletişim - ' . $page_title;
                $page_description = 'Bizimle iletişime geçin. ' . ($settings['contact_address'] ?? '');
                $page_keywords .= ', iletişim bilgileri, adres, telefon';
                break;
            case 'referanslar.php':
                $page_title = 'Referanslarımız - ' . $page_title;
                $page_description = 'Çalıştığımız değerli firmalar ve müşteri referanslarımız.';
                $page_keywords .= ', müşteriler, partnerler, referanslar';
                break;
            case 'galeri.php':
                $page_title = 'Galeri - ' . $page_title;
                $page_description = 'Projelerimize ait görsel galeri.';
                $page_keywords .= ', görsel galeri, projeler, çalışmalar';
                break;
        }
    }
    ?>
    
    <!-- SEO Meta Etiketleri -->
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <meta name="author" content="<?php echo htmlspecialchars($settings['site_title']); ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/img/og-image.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo $page_title; ?>">
    <meta property="twitter:description" content="<?php echo $page_description; ?>">
    <meta property="twitter:image" content="<?php echo SITE_URL; ?>/assets/img/og-image.jpg">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    
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
    // Her yenilemede kesinlikle taze renk şemasını almak için zaman damgası
    $cacheBuster = time(); 
    // Debug için renk şemasını yazdır
    $currentSettings = getSiteSettings();
    error_log('Current color scheme in header: ' . ($currentSettings['color_scheme'] ?? 'undefined'));
    echo getColorSchemeVariables(); 
    ?>
    
    <!-- Ek Stil Geçersiz Kılmalar -->
    <style>
        /* Temel Stillerle Entegrasyon */
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
            background-color: var(--light-color);
            transition: all 0.3s ease;
        }
        
        /* Navigasyon Çubuğu Renk Uygulaması */
        .navbar {
            background-color: #fff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .navbar .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .navbar .nav-link:hover, 
        .navbar .nav-link.active {
            color: var(--primary-color) !important;
        }
        
        /* Top Bar Renk Uygulaması */
        .top-bar {
            background-color: var(--dark-color) !important;
            color: var(--light-color) !important;
            transition: background-color 0.3s ease;
        }
        
        .top-bar .social-links a {
            color: var(--light-color) !important;
            transition: color 0.3s ease;
        }
        
        .top-bar .social-links a:hover {
            color: var(--accent-color) !important;
            transform: translateY(-2px);
        }
        
        /* Ana İçerik Alanı Renk Uygulaması */
        .main-content {
            background-color: var(--main-bg-color);
            color: var(--dark-color);
            transition: all 0.3s ease;
        }
        
        /* Başlıklar ve Metinler */
        h1, h2, h3, h4, h5, h6 {
            color: var(--primary-color);
            transition: color 0.3s ease;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .bg-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        /* Butonlar ve Bağlantı Renkleri */
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--dark-color) !important;
            border-color: var(--dark-color) !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover, .btn-secondary:focus {
            background-color: var(--dark-color) !important;
            border-color: var(--dark-color) !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .btn-accent {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: var(--dark-color) !important;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary-color) !important;
            color: white !important;
            transform: translateY(-2px);
        }
        
        /* Kartlar ve İçerik Kutuları */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-radius: 8px 8px 0 0 !important;
        }
        
        .card-title {
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        
        /* Hizmet Kartları */
        .service-card {
            transition: all 0.3s ease;
            border: 1px solid #eee;
            border-top: 3px solid var(--primary-color);
            border-radius: 8px;
            background-color: white;
            padding: 25px 20px;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-top-color: var(--secondary-color);
        }
        
        /* Bölüm Başlıkları */
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
            transition: background-color 0.3s ease;
        }
        
        /* İkonlar */
        .service-icon, .feature-icon {
            color: var(--primary-color);
            font-size: 40px;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }
        
        /* Sayfa Başlık Alanı */
        .page-header {
            background-color: var(--header-bg-color);
            color: white;
            padding: 60px 0;
            position: relative;
            text-align: center;
        }
        
        .page-header h1 {
            color: white;
            font-weight: 700;
        }
        
        .page-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
        }
        
        /* Alt Bilgi (Footer) */
        footer {
            background-color: var(--footer-bg-color);
            color: var(--light-color);
            padding: 60px 0 30px;
            margin-top: 50px;
        }
        
        footer h5 {
            color: white;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        footer a {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
        }
        
        footer a:hover {
            color: var(--accent-color);
            text-decoration: none;
            padding-left: 5px;
        }
        
        /* Slider / Carousel */
        .carousel-caption {
            background-color: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 8px;
            bottom: 40px;
        }
        
        .carousel-caption h2 {
            color: white;
            font-weight: 700;
        }
        
        .carousel-indicators button {
            background-color: var(--primary-color);
        }
        
        /* Geçiş Animasyonları */
        .service-card, .gallery-item, .reference-card, .social-links a, .btn, .card, .nav-link, a {
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