<?php
/**
 * Galeri Sayfası
 * 
 * Bu sayfa şirketin proje ve etkinlik görsellerini ve videolarını sergiler.
 * Kategorilere göre filtreleme özelliği sunar.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
require_once '../config.php';

// Sayfa başlığını ayarla
$pageTitle = 'Galeri';

// Veritabanı bağlantısını al
$db = getDbConnection();

// Galeri öğelerini veritabanından çek
try {
    $stmt = $db->prepare("SELECT * FROM gallery ORDER BY uploaded_at DESC");
    $stmt->execute();
    $galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Kategorileri çek (tekrarsız)
    $stmt = $db->prepare("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    // Hata mesajını göster
    echo '<div style="color: red; padding: 20px; background-color: #ffe6e6; margin: 20px; border-radius: 5px;">';
    echo '<strong>Veritabanı Hatası:</strong> ' . $e->getMessage();
    echo '</div>';
    
    // Veritabanı hatası durumunda örnek galeri öğeleri
    $galleryItems = [
        [
            'id' => 1,
            'title' => 'Proje 1',
            'file_path' => '../assets/img/gallery1.jpg',
            'type' => 'image',
            'category' => 'Projeler',
            'description' => 'Proje 1 açıklaması'
        ],
        [
            'id' => 2,
            'title' => 'Proje 2',
            'file_path' => '../assets/img/gallery2.jpg',
            'type' => 'image',
            'category' => 'Projeler',
            'description' => 'Proje 2 açıklaması'
        ],
        [
            'id' => 3,
            'title' => 'Etkinlik 1',
            'file_path' => '../assets/img/gallery3.jpg',
            'type' => 'image',
            'category' => 'Etkinlikler',
            'description' => 'Etkinlik 1 açıklaması'
        ],
        [
            'id' => 4,
            'title' => 'Tanıtım Videosu',
            'file_path' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'type' => 'video',
            'category' => 'Videolar',
            'description' => 'Tanıtım videosu açıklaması'
        ]
    ];
    
    $categories = ['Projeler', 'Etkinlikler', 'Videolar'];
}

// Header'ı dahil et
include_once '../includes/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold text-white mb-3">Galeri</h1>
                <p class="lead text-white opacity-90 mb-0">Projelerimizden görüntüler</p>
            </div>
        </div>
    </div>
</div>

<!-- Galeri Bölümü -->
<section class="gallery-section py-5">
    <div class="container">
        <!-- Filtre Butonları -->
        <div class="filter-buttons text-center mb-5">
            <button type="button" class="btn btn-primary active" data-filter="*">Tümü</button>
            <?php foreach ($categories as $category): ?>
                <?php $filterClass = strtolower(str_replace(' ', '-', $category)); ?>
                <button type="button" class="btn btn-outline-primary" data-filter=".<?php echo $filterClass; ?>">
                    <?php echo $category; ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <!-- Galeri Öğeleri -->
        <div class="row gallery-container">
            <?php foreach ($galleryItems as $item): ?>
                <?php 
                $categoryClass = strtolower(str_replace(' ', '-', $item['category'])); 
                ?>
                <div class="col-lg-4 col-md-6 gallery-item <?php echo $categoryClass; ?>">
                    <?php if ($item['type'] == 'image'): ?>
                        <a href="<?php echo $item['file_path']; ?>" data-lightbox="gallery" data-title="<?php echo $item['title']; ?>">
                            <img src="<?php echo $item['file_path']; ?>" alt="<?php echo $item['title']; ?>" class="img-fluid">
                        </a>
                    <?php else: ?>
                        <div class="video-container">
                            <iframe src="<?php echo $item['file_path']; ?>" allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>
                    <div class="content">
                        <h4><?php echo $item['title']; ?></h4>
                        <p><?php echo $item['description']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- İletişim CTA -->
<section class="cta-section bg-primary text-white py-5 text-center">
    <div class="container">
        <h2>Projeleriniz için bizimle iletişime geçin</h2>
        <p class="mb-4">Profesyonel ekibimiz ile hayallerinizi gerçeğe dönüştürelim.</p>
        <a href="../pages/iletisim.php" class="btn btn-light btn-lg">Bize Ulaşın</a>
    </div>
</section>

<!-- Lightbox Kütüphanesi (sadece resim görüntüleme için) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css">
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox.min.js"></script>

<!-- Filtre ve Lightbox için JavaScript -->
<script>
// Sayfa yüklenir yüklenmez çalışacak script
(function() {
    // Butonları ve galeri öğelerini seç
    var filterButtons = document.querySelectorAll('.filter-buttons button');
    var galleryItems = document.querySelectorAll('.gallery-item');
    
    // Her butona onclick olayı ekle
    for (var i = 0; i < filterButtons.length; i++) {
        filterButtons[i].onclick = function() {
            // Tüm butonlardan active sınıfını kaldır
            for (var j = 0; j < filterButtons.length; j++) {
                filterButtons[j].classList.remove('active');
            }
            
            // Tıklanan butona active sınıfı ekle
            this.classList.add('active');
            
            // Seçilen filtre değerini al
            var filterValue = this.getAttribute('data-filter');
            console.log('Tıklanan buton: ' + filterValue); // Debug için
            
            // Galeri öğelerini filtrele
            for (var k = 0; k < galleryItems.length; k++) {
                var item = galleryItems[k];
                
                if (filterValue === '*') {
                    // Tüm öğeleri göster
                    item.style.display = '';
                } else {
                    // Filtre değerini temizle (başındaki nokta işaretini kaldır)
                    var filterClass = filterValue.replace('.', '');
                    
                    // Öğenin class listesinde aranan sınıf var mı kontrol et
                    if (item.classList.contains(filterClass)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                }
            }
        };
    }
})();

// Sayfa tamamen yüklenince Lightbox ayarlarını yap
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': "Resim %1 / %2"
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html> 