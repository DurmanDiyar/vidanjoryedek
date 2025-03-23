<?php
/**
 * SEO için veritabanı sütunları ekleme
 */
require_once 'config.php';

// Hata gösterme
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>SEO Sütunları Ekleme Aracı</h2>";

try {
    // Veritabanı bağlantısı
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "<p>Veritabanına başarıyla bağlanıldı: " . DB_NAME . "</p>";
    
    // Sütunların var olup olmadığını kontrol et
    $checkDescription = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'site_description'");
    $hasDescription = $checkDescription->rowCount() > 0;
    
    $checkKeywords = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'site_keywords'");
    $hasKeywords = $checkKeywords->rowCount() > 0;
    
    // site_description sütunu ekle
    if (!$hasDescription) {
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN site_description TEXT AFTER whatsapp_phone");
        // Varsayılan değer için ayrı bir güncelleme
        $pdo->exec("UPDATE site_settings SET site_description = 'Profesyonel kurumsal hizmetler sunan web sitemize hoş geldiniz.' WHERE site_description IS NULL");
        echo "<p style='color:green'>✅ site_description sütunu başarıyla eklendi.</p>";
    } else {
        echo "<p>❗ site_description sütunu zaten mevcut.</p>";
    }
    
    // site_keywords sütunu ekle
    if (!$hasKeywords) {
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN site_keywords TEXT AFTER site_description");
        // Varsayılan değer için ayrı bir güncelleme
        $pdo->exec("UPDATE site_settings SET site_keywords = 'kurumsal, hizmetler, profesyonel' WHERE site_keywords IS NULL");
        echo "<p style='color:green'>✅ site_keywords sütunu başarıyla eklendi.</p>";
    } else {
        // Sütun varsa veri tipini kontrol et ve TEXT değilse güncelle
        $columnTypeQuery = $pdo->query("SHOW COLUMNS FROM site_settings LIKE 'site_keywords'");
        $columnInfo = $columnTypeQuery->fetch(PDO::FETCH_ASSOC);
        
        if ($columnInfo && isset($columnInfo['Type']) && $columnInfo['Type'] != 'text') {
            $pdo->exec("ALTER TABLE site_settings MODIFY COLUMN site_keywords TEXT");
            echo "<p style='color:green'>✅ site_keywords sütun türü TEXT olarak güncellendi.</p>";
        } else {
            echo "<p>❗ site_keywords sütunu zaten mevcut.</p>";
        }
    }
    
    echo "<p style='font-weight:bold;color:green'>İşlem tamamlandı. <a href='admin/settings.php'>Site Ayarları</a> sayfasına giderek SEO ayarlarını düzenleyebilirsiniz.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Veritabanı hatası: " . $e->getMessage() . "</p>";
}
?> 