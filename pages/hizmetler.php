<?php
/**
 * Services page for the corporate website
 */
require_once '../config.php';

// Get services from database
try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
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
        ],
        [
            'id' => 4,
            'name' => 'Montaj Hizmetleri',
            'description' => 'Uzman ekibimiz ile hızlı ve güvenilir montaj hizmetleri sunuyoruz.',
            'icon' => 'fas fa-hammer'
        ],
        [
            'id' => 5,
            'name' => 'Acil Servis',
            'description' => '7/24 acil servis hizmetimiz ile sorunlarınıza hızlı çözümler üretiyoruz.',
            'icon' => 'fas fa-ambulance'
        ],
        [
            'id' => 6,
            'name' => 'Keşif ve Planlama',
            'description' => 'Ücretsiz keşif ve detaylı planlama hizmetleri ile projelerinizi hayata geçiriyoruz.',
            'icon' => 'fas fa-search'
        ],
        [
            'id' => 7,
            'name' => 'Kalite Kontrol',
            'description' => 'Detaylı kalite kontrol süreçleri ile hizmetlerimizin en yüksek standartlarda olmasını sağlıyoruz.',
            'icon' => 'fas fa-check-circle'
        ],
        [
            'id' => 8,
            'name' => 'Eğitim ve Destek',
            'description' => 'Müşterilerimize kapsamlı eğitim ve destek hizmetleri sunuyoruz.',
            'icon' => 'fas fa-graduation-cap'
        ],
        [
            'id' => 9,
            'name' => 'Yedek Parça Temini',
            'description' => 'Orijinal yedek parça temini ile sistemlerinizin performansını koruyoruz.',
            'icon' => 'fas fa-cog'
        ]
    ];
}

// Include header
include_once '../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4">Hizmetlerimiz</h1>
                <p class="lead">Profesyonel ekibimiz ile sunduğumuz kaliteli hizmetler.</p>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<section class="services-page-section pb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-5">
                <p class="text-center">
                    Firmamız, müşterilerimizin ihtiyaçlarına uygun çözümler sunmak için geniş bir hizmet yelpazesi sunmaktadır. 
                    Profesyonel ekibimiz ve modern teknolojik altyapımız ile her projede en yüksek kaliteyi hedefliyoruz.
                </p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($services as $service): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo $service['icon']; ?>"></i>
                        </div>
                        <h3 class="service-title"><?php echo $service['name']; ?></h3>
                        <p class="service-description"><?php echo $service['description']; ?></p>
                        <a href="<?php echo SITE_URL; ?>/pages/hizmet-detay.php?id=<?php echo $service['id']; ?>" class="service-link">
                            Detaylı Bilgi <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Neden Bizi Tercih Etmelisiniz?</h2>
                <p>Müşteri memnuniyetini ön planda tutarak, kaliteli ve güvenilir hizmet sunuyoruz.</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-medal fa-3x text-primary"></i>
                        </div>
                        <h4>Kaliteli Hizmet</h4>
                        <p>En yüksek kalite standartlarında hizmet sunuyoruz. Her projede müşteri memnuniyetini ön planda tutuyoruz.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-user-tie fa-3x text-primary"></i>
                        </div>
                        <h4>Uzman Ekip</h4>
                        <p>Alanında uzman ve deneyimli ekibimiz ile her projede profesyonel çözümler sunuyoruz.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-headset fa-3x text-primary"></i>
                        </div>
                        <h4>7/24 Destek</h4>
                        <p>Müşterilerimize 7/24 teknik destek hizmeti sunuyoruz. Sorunlarınıza hızlı çözümler üretiyoruz.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-tools fa-3x text-primary"></i>
                        </div>
                        <h4>Modern Ekipman</h4>
                        <p>En son teknoloji ekipmanlar ile hizmet sunuyoruz. Sürekli yenilenen teknolojik altyapımız ile fark yaratıyoruz.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-clock fa-3x text-primary"></i>
                        </div>
                        <h4>Zamanında Teslimat</h4>
                        <p>Projelerinizi planlanan sürede tamamlıyoruz. Zamanında teslimat bizim için önceliklidir.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-hand-holding-usd fa-3x text-primary"></i>
                        </div>
                        <h4>Uygun Fiyat</h4>
                        <p>Kaliteli hizmeti uygun fiyatlarla sunuyoruz. Bütçenize uygun çözümler üretiyoruz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Process Section -->
<section class="process-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Hizmet Sürecimiz</h2>
                <p>Müşterilerimize en iyi hizmeti sunmak için izlediğimiz adımlar.</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="process-step text-center">
                    <div class="process-icon">
                        <span class="step-number">1</span>
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>İlk Görüşme</h4>
                    <p>İhtiyaçlarınızı anlamak için detaylı bir görüşme yapıyoruz.</p>
                </div>
            </div>
            
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="process-step text-center">
                    <div class="process-icon">
                        <span class="step-number">2</span>
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4>Planlama</h4>
                    <p>İhtiyaçlarınıza uygun detaylı bir plan hazırlıyoruz.</p>
                </div>
            </div>
            
            <div class="col-md-3 mb-4 mb-md-0">
                <div class="process-step text-center">
                    <div class="process-icon">
                        <span class="step-number">3</span>
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4>Uygulama</h4>
                    <p>Planı uzman ekibimiz ile hayata geçiriyoruz.</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="process-step text-center">
                    <div class="process-icon">
                        <span class="step-number">4</span>
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4>Teslimat ve Destek</h4>
                    <p>Projeyi teslim ediyor ve sürekli destek sağlıyoruz.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-9 text-white">
                <h3 class="mb-2">Hizmetlerimiz Hakkında Detaylı Bilgi Almak İster misiniz?</h3>
                <p class="mb-0">Hemen bizimle iletişime geçin, ihtiyaçlarınıza uygun çözümler sunalım.</p>
            </div>
            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo SITE_URL; ?>/pages/iletisim.php" class="btn btn-light btn-lg">Bize Ulaşın</a>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?> 