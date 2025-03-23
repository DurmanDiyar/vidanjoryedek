<?php
/**
 * Service Detail page for the corporate website
 */
require_once '../config.php';

// Get service ID from URL
$service_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get service details from database
try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();
    
    if (!$service) {
        // Redirect to services page if service not found
        header("Location: " . SITE_URL . "/pages/hizmetler.php");
        exit;
    }
} catch (PDOException $e) {
    // Fallback service if database error
    $service = [
        'id' => $service_id,
        'name' => 'Hizmet Başlığı',
        'description' => 'Hizmet açıklaması burada yer alacaktır.',
        'icon' => 'fas fa-cogs',
        'price' => '0.00'
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
                <h1 class="display-4"><?php echo $service['name']; ?></h1>
                <p class="lead">Hizmetlerimiz / <?php echo $service['name']; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Service Detail Section -->
<section class="service-detail-section pb-5">
    <div class="container">
        <div class="row">
            <!-- Service Content -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="service-detail-content">
                    <div class="service-icon mb-4">
                        <i class="<?php echo $service['icon']; ?> fa-4x text-primary"></i>
                    </div>
                    
                    <h2 class="mb-4"><?php echo $service['name']; ?> Hizmetimiz</h2>
                    
                    <p class="lead mb-4">
                        <?php echo $service['description']; ?>
                    </p>
                    
                    <!-- Extended Description (This would come from a more detailed field in the database) -->
                    <p>
                        Firmamız, müşterilerimizin ihtiyaçlarına uygun çözümler sunmak için <?php echo $service['name']; ?> hizmetini profesyonel ekibimiz ile sunmaktadır. 
                        Uzun yıllara dayanan deneyimimiz ve modern teknolojik altyapımız ile her projede en yüksek kaliteyi hedefliyoruz.
                    </p>
                    
                    <p>
                        <?php echo $service['name']; ?> hizmetimiz kapsamında, müşterilerimizin ihtiyaçlarını detaylı bir şekilde analiz ediyor ve en uygun çözümleri sunuyoruz. 
                        Profesyonel ekibimiz, alanında uzman kişilerden oluşmakta ve sürekli kendini geliştirmektedir.
                    </p>
                    
                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h4 class="card-title">Hizmet Avantajları</h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Profesyonel ekip</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Modern teknolojik altyapı</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Hızlı ve güvenilir hizmet</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Uygun fiyat garantisi</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> 7/24 teknik destek</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h4 class="card-title">Hizmet Süreci</h4>
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <div class="d-flex">
                                                <div class="process-number me-3">1</div>
                                                <div>
                                                    <h5>İhtiyaç Analizi</h5>
                                                    <p class="mb-0">İhtiyaçlarınızı detaylı bir şekilde analiz ediyoruz.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-3">
                                            <div class="d-flex">
                                                <div class="process-number me-3">2</div>
                                                <div>
                                                    <h5>Çözüm Önerisi</h5>
                                                    <p class="mb-0">İhtiyaçlarınıza uygun çözüm önerileri sunuyoruz.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="process-number me-3">3</div>
                                                <div>
                                                    <h5>Uygulama ve Takip</h5>
                                                    <p class="mb-0">Çözümü uygulayıp, sürekli takip ediyoruz.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Service Gallery -->
                    <div class="service-gallery mt-5">
                        <h3 class="mb-4">Hizmet Görselleri</h3>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="gallery-item">
                                    <img src="<?php echo SITE_URL; ?>/assets/img/service-gallery1.jpg" alt="<?php echo $service['name']; ?>" class="img-fluid rounded">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="gallery-item">
                                    <img src="<?php echo SITE_URL; ?>/assets/img/service-gallery2.jpg" alt="<?php echo $service['name']; ?>" class="img-fluid rounded">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="gallery-item">
                                    <img src="<?php echo SITE_URL; ?>/assets/img/service-gallery3.jpg" alt="<?php echo $service['name']; ?>" class="img-fluid rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ Section -->
                    <div class="service-faq mt-5">
                        <h3 class="mb-4">Sık Sorulan Sorular</h3>
                        <div class="accordion" id="serviceFaq">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                        <?php echo $service['name']; ?> hizmetinizin fiyatı nedir?
                                    </button>
                                </h2>
                                <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#serviceFaq">
                                    <div class="accordion-body">
                                        Hizmet fiyatlarımız, projenin kapsamına ve ihtiyaçlarınıza göre değişiklik göstermektedir. Detaylı bilgi ve fiyat teklifi için bizimle iletişime geçebilirsiniz.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                        <?php echo $service['name']; ?> hizmetinizin süresi ne kadardır?
                                    </button>
                                </h2>
                                <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#serviceFaq">
                                    <div class="accordion-body">
                                        Hizmet süremiz, projenin kapsamına ve ihtiyaçlarınıza göre değişiklik göstermektedir. Ortalama süre 1-4 hafta arasındadır. Detaylı bilgi için bizimle iletişime geçebilirsiniz.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                        <?php echo $service['name']; ?> hizmetiniz için garanti veriyor musunuz?
                                    </button>
                                </h2>
                                <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#serviceFaq">
                                    <div class="accordion-body">
                                        Evet, tüm hizmetlerimiz için 1 yıl garanti veriyoruz. Garanti süresi içerisinde oluşabilecek sorunları ücretsiz olarak çözüyoruz.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="service-sidebar">
                    <!-- Contact Form -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Bilgi Talep Formu</h3>
                            <form action="#" method="post">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Adınız Soyadınız *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-posta Adresiniz *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telefon Numaranız</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Mesajınız *</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Gönder</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Other Services -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Diğer Hizmetlerimiz</h3>
                            <ul class="list-unstyled">
                                <?php
                                try {
                                    $stmt = $pdo->query("SELECT id, name FROM services WHERE id != {$service_id} ORDER BY id ASC LIMIT 5");
                                    $otherServices = $stmt->fetchAll();
                                } catch (PDOException $e) {
                                    // Fallback other services if database error
                                    $otherServices = [
                                        ['id' => 1, 'name' => 'Teknik Danışmanlık'],
                                        ['id' => 2, 'name' => 'Proje Yönetimi'],
                                        ['id' => 3, 'name' => 'Bakım ve Onarım'],
                                        ['id' => 4, 'name' => 'Montaj Hizmetleri'],
                                        ['id' => 5, 'name' => 'Acil Servis']
                                    ];
                                    
                                    // Remove current service from fallback list
                                    foreach ($otherServices as $key => $otherService) {
                                        if ($otherService['id'] == $service_id) {
                                            unset($otherServices[$key]);
                                            break;
                                        }
                                    }
                                }
                                
                                foreach ($otherServices as $otherService):
                                ?>
                                    <li class="mb-2">
                                        <a href="<?php echo SITE_URL; ?>/pages/hizmet-detay.php?id=<?php echo $otherService['id']; ?>" class="text-decoration-none">
                                            <i class="fas fa-angle-right me-2 text-primary"></i><?php echo $otherService['name']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <a href="<?php echo SITE_URL; ?>/pages/hizmetler.php" class="btn btn-outline-primary w-100 mt-3">Tüm Hizmetlerimiz</a>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title mb-4">İletişim Bilgileri</h3>
                            <ul class="list-unstyled contact-info">
                                <li class="mb-3">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    <a href="tel:<?php echo $settings['contact_phone']; ?>" class="text-decoration-none"><?php echo $settings['contact_phone']; ?></a>
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    <a href="mailto:<?php echo $settings['contact_email']; ?>" class="text-decoration-none"><?php echo $settings['contact_email']; ?></a>
                                </li>
                                <li>
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    <?php echo $settings['contact_address']; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Services Section -->
<section class="related-services-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">İlgili Hizmetlerimiz</h2>
            </div>
        </div>
        
        <div class="row">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM services WHERE id != {$service_id} ORDER BY RAND() LIMIT 3");
                $relatedServices = $stmt->fetchAll();
            } catch (PDOException $e) {
                // Fallback related services if database error
                $relatedServices = [
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
                
                // Remove current service from fallback list
                foreach ($relatedServices as $key => $relatedService) {
                    if ($relatedService['id'] == $service_id) {
                        unset($relatedServices[$key]);
                        break;
                    }
                }
                
                // Limit to 3 services
                $relatedServices = array_slice($relatedServices, 0, 3);
            }
            
            foreach ($relatedServices as $relatedService):
            ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo $relatedService['icon']; ?>"></i>
                        </div>
                        <h3 class="service-title"><?php echo $relatedService['name']; ?></h3>
                        <p class="service-description"><?php echo $relatedService['description']; ?></p>
                        <a href="<?php echo SITE_URL; ?>/pages/hizmet-detay.php?id=<?php echo $relatedService['id']; ?>" class="service-link">
                            Detaylı Bilgi <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5" style="background-color: var(--primary-color);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-9 text-white">
                <h3 class="mb-2"><?php echo $service['name']; ?> Hizmetimiz Hakkında Detaylı Bilgi Almak İster misiniz?</h3>
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