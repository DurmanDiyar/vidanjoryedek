<?php
/**
 * Referanslar Sayfası
 * 
 * Bu sayfa şirketin referanslarını ve iş ortaklarını sergiler.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
require_once '../config.php';

// Sayfa başlığını ayarla
$pageTitle = 'Referanslar';

// Veritabanı bağlantısını al
$db = getDbConnection();

// Referansları veritabanından çek
try {
    $stmt = $db->prepare("SELECT * FROM referencess ORDER BY company_name ASC");
    $stmt->execute();
    $references = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Hata mesajını göster
    echo '<div style="color: red; padding: 20px; background-color: #ffe6e6; margin: 20px; border-radius: 5px;">';
    echo '<strong>Veritabanı Hatası:</strong> ' . $e->getMessage();
    echo '</div>';
    
    // Veritabanı hatası durumunda örnek referanslar
    $references = [
        [
            'id' => 1,
            'company_name' => 'ABC Şirketi',
            'logo_path' => '../assets/img/ref1.png',
            'description' => 'ABC Şirketi ile uzun yıllardır çalışmaktayız.',
            'website_url' => 'https://www.example.com'
        ],
        [
            'id' => 2,
            'company_name' => 'XYZ Holding',
            'logo_path' => '../assets/img/ref2.png',
            'description' => 'XYZ Holding ile başarılı projelere imza attık.',
            'website_url' => 'https://www.example.com'
        ],
        [
            'id' => 3,
            'company_name' => 'DEF Teknoloji',
            'logo_path' => '../assets/img/ref3.png',
            'description' => 'DEF Teknoloji ile teknolojik çözümler ürettik.',
            'website_url' => 'https://www.example.com'
        ],
        [
            'id' => 4,
            'company_name' => 'GHI İnşaat',
            'logo_path' => '../assets/img/ref4.png',
            'description' => 'GHI İnşaat ile büyük projelerde yer aldık.',
            'website_url' => 'https://www.example.com'
        ],
        [
            'id' => 5,
            'company_name' => 'JKL Otomotiv',
            'logo_path' => '../assets/img/ref5.png',
            'description' => 'JKL Otomotiv ile sektöre yön verdik.',
            'website_url' => 'https://www.example.com'
        ],
        [
            'id' => 6,
            'company_name' => 'MNO Tekstil',
            'logo_path' => '../assets/img/ref6.png',
            'description' => 'MNO Tekstil ile uzun soluklu işbirliği yaptık.',
            'website_url' => 'https://www.example.com'
        ]
    ];
}

// Header'ı dahil et
include_once '../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold text-white mb-3">Referanslar</h1>
                <p class="lead text-white opacity-90 mb-0">Bizimle çalışan değerli iş ortaklarımız</p>
            </div>
        </div>
    </div>
</div>

<!-- Referanslar Bölümü -->
<section class="references-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto mb-5 text-center">
                <h2 class="section-title">İş Ortaklarımız</h2>
                <p class="section-description">Yıllar içinde birlikte çalıştığımız değerli iş ortaklarımız ve müşterilerimiz.</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($references as $reference): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="reference-item shadow">
                        <div class="reference-logo">
                            <img src="../uploads/references/<?php echo $reference['logo_path']; ?>" alt="<?php echo htmlspecialchars($reference['company_name']); ?>" class="img-fluid">
                        </div>
                        <div class="reference-content">
                            <h4 class="h5"><?php echo htmlspecialchars($reference['company_name']); ?></h4>
                            <p class="description-text"><?php echo mb_substr(htmlspecialchars($reference['description']), 0, 150) . (mb_strlen($reference['description']) > 150 ? '...' : ''); ?></p>
                            <?php if (!empty($reference['website_url'])): ?>
                                <a href="<?php echo htmlspecialchars($reference['website_url']); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i> Web Sitesi
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>



<!-- İşbirliği CTA -->
<section class="cta-section bg-primary text-white py-5 text-center">
    <div class="container">
        <h2>Siz de referanslarımız arasında yer almak ister misiniz?</h2>
        <p class="mb-4">Profesyonel ekibimiz ile projelerinizi hayata geçirelim.</p>
        <a href="../pages/iletisim.php" class="btn btn-light btn-lg">Bizimle Çalışın</a>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html> 