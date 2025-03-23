<?php
/**
 * XML Sitemap Generator
 * Bu script, kurumsal web sitesi için XML sitemap oluşturur
 * Tüm sayfalara ve dinamik içeriklere bağlantılar içerir
 */

require_once 'config.php';

// Hataları gösterme
ini_set('display_errors', 0);

// Çıktı türünü XML olarak ayarla
header('Content-Type: application/xml; charset=utf-8');

try {
    // Veritabanı bağlantısı
    $pdo = getDbConnection();
    
    // XML başlangıcı
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    
    // Statik sayfalar
    $staticPages = [
        '' => '1.0',                // Ana sayfa 
        'pages/hizmetler.php' => '0.8',     // Hizmetler
        'pages/hakkimizda.php' => '0.8',    // Hakkımızda
        'pages/iletisim.php' => '0.8',      // İletişim
        'pages/referanslar.php' => '0.8',   // Referanslar
        'pages/galeri.php' => '0.7',        // Galeri
    ];
    
    // Şu anki tarih
    $today = date('Y-m-d');
    
    // Statik sayfaları ekle
    foreach ($staticPages as $page => $priority) {
        $url = SITE_URL . '/' . $page;
        echo "\t<url>" . PHP_EOL;
        echo "\t\t<loc>" . htmlspecialchars($url) . "</loc>" . PHP_EOL;
        echo "\t\t<lastmod>" . $today . "</lastmod>" . PHP_EOL;
        echo "\t\t<changefreq>weekly</changefreq>" . PHP_EOL;
        echo "\t\t<priority>" . $priority . "</priority>" . PHP_EOL;
        echo "\t</url>" . PHP_EOL;
    }
    
    // Dinamik hizmet detay sayfaları
    $stmt = $pdo->query("SELECT id, name, updated_at FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($services as $service) {
        $url = SITE_URL . '/pages/hizmet-detay.php?id=' . $service['id'];
        $lastmod = isset($service['updated_at']) ? date('Y-m-d', strtotime($service['updated_at'])) : $today;
        
        echo "\t<url>" . PHP_EOL;
        echo "\t\t<loc>" . htmlspecialchars($url) . "</loc>" . PHP_EOL;
        echo "\t\t<lastmod>" . $lastmod . "</lastmod>" . PHP_EOL;
        echo "\t\t<changefreq>monthly</changefreq>" . PHP_EOL;
        echo "\t\t<priority>0.7</priority>" . PHP_EOL;
        echo "\t</url>" . PHP_EOL;
    }
    
    // XML sonlandırma
    echo '</urlset>';
    
    // XML dosyasını kaydet
    $xml = ob_get_contents();
    file_put_contents('sitemap.xml', $xml);
    
} catch (PDOException $e) {
    // Hata durumunda basit bir XML çıktısı oluştur
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    
    // Sadece ana sayfayı ekle
    echo "\t<url>" . PHP_EOL;
    echo "\t\t<loc>" . htmlspecialchars(SITE_URL) . "</loc>" . PHP_EOL;
    echo "\t\t<lastmod>" . date('Y-m-d') . "</lastmod>" . PHP_EOL;
    echo "\t\t<changefreq>weekly</changefreq>" . PHP_EOL;
    echo "\t\t<priority>1.0</priority>" . PHP_EOL;
    echo "\t</url>" . PHP_EOL;
    
    echo '</urlset>';
    
    // Hatayı logla
    error_log("Sitemap oluşturma hatası: " . $e->getMessage());
}
?> 