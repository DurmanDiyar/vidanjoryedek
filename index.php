<?php
/**
 * Homepage for the corporate website
 */
require_once 'config.php';

// Get site settings
try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $settings = [
        'site_title' => 'Kurumsal Web Sitesi',
        'contact_phone' => '+90 555 123 4567',
        'contact_email' => 'info@example.com',
        'contact_address' => 'Örnek Mahallesi, Örnek Caddesi No:123, İstanbul'
    ];
}

// Include header
include_once 'includes/header.php';

// Get slider items from database
try {
    $stmt = $pdo->query("SELECT * FROM slider ORDER BY display_order ASC");
    $sliderItems = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback slider items if database error
    $sliderItems = [
        [
            'id' => 1,
            'image_path' => 'assets/img/slider1.jpg',
            'title' => 'Profesyonel Hizmet Anlayışı',
            'description' => 'Uzman ekibimiz ile kaliteli ve güvenilir hizmet sunuyoruz.',
            'display_order' => 1
        ],
        [
            'id' => 2,
            'image_path' => 'assets/img/slider2.jpg',
            'title' => 'Modern Çözümler',
            'description' => 'Teknolojik altyapımız ile modern çözümler üretiyoruz.',
            'display_order' => 2
        ]
    ];
}

// Get services from database
try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC LIMIT 6");
    $services = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback services if database error
    $services = [
        [
            'id' => 1,
            'name' => 'Teknik Danışmanlık',
            'description' => 'Profesyonel ekibimiz ile teknik konularda danışmanlık hizmeti sunuyoruz.',
            'icon' => 'fas fa-cogs'
        ],
        [
            'id' => 2,
            'name' => 'Proje Yönetimi',
            'description' => 'Projelerinizi başından sonuna kadar profesyonel bir şekilde yönetiyoruz.',
            'icon' => 'fas fa-tasks'
        ],
        [
            'id' => 3,
            'name' => 'Bakım ve Onarım',
            'description' => 'Düzenli bakım ve onarım hizmetleri ile sistemlerinizin sürekli çalışmasını sağlıyoruz.',
            'icon' => 'fas fa-tools'
        ]
    ];
}

// Get references from database
try {
    $stmt = $pdo->query("SELECT * FROM referencess ORDER BY id ASC LIMIT 8");
    $references = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback references if database error
    $references = [];
}
?>

<!-- Slider Section -->
<section class="slider-section">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($sliderItems as $index => $item): ?>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active"' : ''; ?> aria-label="Slide <?php echo $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        
        <div class="carousel-inner">
            <?php foreach ($sliderItems as $index => $item): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo htmlspecialchars($item['image_path']); ?>')">
                    <div class="carousel-caption">
                        <h1><?php echo htmlspecialchars($item['title']); ?></h1>
                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                        <div class="mt-4">
                            <a href="contact.php" class="btn btn-primary btn-lg">Bize Ulaşın</a>
                            <a href="services.php" class="btn btn-outline-light btn-lg ms-2">Hizmetlerimiz</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Önceki</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sonraki</span>
        </button>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="feature-box bg-light p-3 text-center rounded shadow-sm h-100">
                    <i class="fas fa-medal text-primary mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="h5 mb-2">Kaliteli Hizmet</h3>
                    <p class="mb-0 text-muted">15+ yıllık tecrübe ile kaliteli ve güvenilir hizmet</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box bg-light p-3 text-center rounded shadow-sm h-100">
                    <i class="fas fa-users text-primary mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="h5 mb-2">Uzman Ekip</h3>
                    <p class="mb-0 text-muted">Alanında uzman profesyonel ekip ile kusursuz çözümler</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box bg-light p-3 text-center rounded shadow-sm h-100">
                    <i class="fas fa-headset text-primary mb-3" style="font-size: 2.5rem;"></i>
                    <h3 class="h5 mb-2">7/24 Destek</h3>
                    <p class="mb-0 text-muted">Her zaman yanınızda olan müşteri destek ekibi</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">Hizmetlerimiz</h2>
            <p class="section-subtitle">Profesyonel çözümler ve kaliteli hizmet anlayışımızla sizlere sunduğumuz hizmetlerimiz</p>
        </div>
        
        <div class="row">
            <?php foreach ($services as $service): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <a href="service-detail.php?id=<?php echo $service['id']; ?>" class="btn btn-outline-primary">Detaylı Bilgi</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="services.php" class="btn btn-primary">Tüm Hizmetlerimiz</a>
        </div>
    </div>
</section>

<!-- CALL TO ACTION SECTION -->
<section class="py-5">
    <div class="container">
        <div class="cta-section py-5 px-4">
            <div class="row align-items-center">
                <div class="col-lg-8 cta-content">
                    <h3 class="cta-title">Profesyonel Hizmet mi Arıyorsunuz?</h3>
                    <p class="cta-description">Size özel çözümler için hemen iletişime geçin. Uzman ekibimiz yanıtlamak için hazır.</p>
                </div>
                <div class="col-lg-4 text-lg-end text-center mt-4 mt-lg-0">
                    <a href="pages/iletisim.php" class="btn cta-btn">Bize Ulaşın <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /CALL TO ACTION SECTION -->

<!-- About Us Section -->
<section class="about-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image position-relative">
                    <img src="<?php echo SITE_URL; ?>/assets/img/about.jpg" alt="Hakkımızda" class="img-fluid rounded shadow">
                    <div class="experience-badge position-absolute bg-primary text-white py-3 px-4 rounded shadow" style="bottom: -20px; right: 30px;">
                        <span class="h2 fw-bold d-block">15+</span>
                        <span>Yıllık Deneyim</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content ps-lg-4">
                    <h6 class="text-primary fw-bold mb-2 text-uppercase">Hakkımızda</h6>
                    <h2 class="mb-4">Profesyonel Çözüm Ortağınız</h2>
                    <p class="lead">
                        Firmamız, sektörde uzun yıllara dayanan deneyimi ile müşterilerine kaliteli ve güvenilir hizmet sunmaktadır.
                    </p>
                    <p>
                        Profesyonel ekibimiz ile ihtiyaçlarınıza uygun çözümler üretiyoruz. Müşteri memnuniyetini ön planda tutarak, her projede en iyi sonucu elde etmek için çalışıyoruz. Teknolojik gelişmeleri yakından takip ederek, modern çözümler sunuyoruz.
                    </p>
                    
                    <div class="row g-3 mt-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 bg-primary text-white rounded p-2">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>500+ Tamamlanan Proje</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 bg-primary text-white rounded p-2">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>50+ Uzman Personel</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 bg-primary text-white rounded p-2">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>300+ Mutlu Müşteri</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 bg-primary text-white rounded p-2">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>7/24 Teknik Destek</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo SITE_URL; ?>/pages/hakkimizda.php" class="btn btn-primary mt-4">Daha Fazla Bilgi</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- REFERENCES SECTION -->
<section class="references py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="section-title">Referanslarımız</h2>
                <p class="section-description mb-5">Birlikte çalıştığımız değerli firmalarımız.</p>
            </div>
        </div>
        <div class="row">
            <?php
            foreach ($references as $reference):
            ?>
            <div class="col-6 col-md-3 col-lg-2 mb-4">
                <div class="reference-item p-3 h-100 rounded bg-white shadow-sm">
                    <img src="uploads/<?php echo htmlspecialchars($reference['logo_path']); ?>" class="reference-logo" alt="<?php echo htmlspecialchars($reference['company_name']); ?>">
                    <h6 class="reference-name small text-center mt-2"><?php echo htmlspecialchars($reference['company_name']); ?></h6>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="pages/referanslar.php" class="btn btn-outline-primary">Tüm Referanslarımız <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
<!-- /REFERENCES SECTION -->

<?php
// Include footer
include_once 'includes/footer.php';
?> 