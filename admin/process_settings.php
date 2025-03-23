<?php
/**
 * Admin Panel - Site Ayarları İşlem Dosyası
 * 
 * Bu dosya, site ayarları formundan gönderilen verileri işlemek ve
 * veritabanına kaydetmek için kullanılır.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Oturum başlat
session_start();

// Oturum kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı ve yardımcı fonksiyonları içe aktar
require_once '../config.php';

// Form gönderilip gönderilmediğini kontrol et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Geçersiz istek yöntemi.";
    header('Location: settings.php');
    exit;
}

// Form verilerini al ve temizle
$siteTitle = isset($_POST['site_title']) ? trim($_POST['site_title']) : '';
$contactPhone = isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : '';
$contactEmail = isset($_POST['contact_email']) ? trim($_POST['contact_email']) : '';
$contactAddress = isset($_POST['contact_address']) ? trim($_POST['contact_address']) : '';
$colorScheme = isset($_POST['color_scheme']) ? trim($_POST['color_scheme']) : 'blue-green';
// İkinci bir kontrol olarak JavaScript tarafından gönderilen değeri de kontrol et
$colorSchemeConfirmed = isset($_POST['color_scheme_confirmed']) ? trim($_POST['color_scheme_confirmed']) : '';
// Eğer onaylanmış renk şeması değeri mevcutsa onu kullan
if (!empty($colorSchemeConfirmed)) {
    $colorScheme = $colorSchemeConfirmed;
}

// Geçerli bir renk şeması mı kontrol et
$validColorSchemes = ['blue-green', 'red-orange', 'purple-pink', 'green-teal', 'dark-blue', 'green-brown'];
if (!in_array($colorScheme, $validColorSchemes)) {
    $colorScheme = 'blue-green'; // Geçersizse varsayılan değere döndür
}

// Color Scheme Debugging
error_log("Renk şeması kaydediliyor: " . $colorScheme);
// Debug amaçlı bir dosyaya yazdır
file_put_contents(__DIR__ . '/color_scheme_debug.log', date('Y-m-d H:i:s') . " - Renk şeması: " . $colorScheme . "\n", FILE_APPEND);

$pageHeaderBg = isset($_POST['page_header_bg']) ? trim($_POST['page_header_bg']) : 'page-header-bg.jpg';
$whatsappPhone = isset($_POST['whatsapp_phone']) ? trim($_POST['whatsapp_phone']) : '';

// Sosyal medya URL'leri
$facebookUrl = isset($_POST['facebook_url']) ? trim($_POST['facebook_url']) : '';
$twitterUrl = isset($_POST['twitter_url']) ? trim($_POST['twitter_url']) : '';
$instagramUrl = isset($_POST['instagram_url']) ? trim($_POST['instagram_url']) : '';
$linkedinUrl = isset($_POST['linkedin_url']) ? trim($_POST['linkedin_url']) : '';
$youtubeUrl = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';

// SEO ayarları
$siteDescription = isset($_POST['site_description']) ? trim($_POST['site_description']) : '';
$siteKeywords = isset($_POST['site_keywords']) ? trim($_POST['site_keywords']) : '';

// Boş URL'leri boş string olarak bırak
// $facebookUrl = empty($facebookUrl) ? '#' : $facebookUrl;
// $twitterUrl = empty($twitterUrl) ? '#' : $twitterUrl;
// $instagramUrl = empty($instagramUrl) ? '#' : $instagramUrl;
// $linkedinUrl = empty($linkedinUrl) ? '#' : $linkedinUrl;
// $youtubeUrl = empty($youtubeUrl) ? '#' : $youtubeUrl;

// http:// veya https:// ile başlamıyorsa ve boş değilse ekle
if (!empty($facebookUrl) && !preg_match('/^https?:\/\//i', $facebookUrl)) {
    $facebookUrl = 'https://' . $facebookUrl;
}
if (!empty($twitterUrl) && !preg_match('/^https?:\/\//i', $twitterUrl)) {
    $twitterUrl = 'https://' . $twitterUrl;
}
if (!empty($instagramUrl) && !preg_match('/^https?:\/\//i', $instagramUrl)) {
    $instagramUrl = 'https://' . $instagramUrl;
}
if (!empty($linkedinUrl) && !preg_match('/^https?:\/\//i', $linkedinUrl)) {
    $linkedinUrl = 'https://' . $linkedinUrl;
}
if (!empty($youtubeUrl) && !preg_match('/^https?:\/\//i', $youtubeUrl)) {
    $youtubeUrl = 'https://' . $youtubeUrl;
}

// Verileri doğrula
if (empty($siteTitle)) {
    $_SESSION['error_message'] = "Site başlığı boş olamaz.";
    header('Location: settings.php');
    exit;
}

if (!empty($contactEmail) && !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Geçerli bir e-posta adresi girin.";
    header('Location: settings.php');
    exit;
}

// Veritabanı bağlantısını al
try {
    $db = getDbConnection();
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Veritabanı bağlantı hatası: " . $e->getMessage();
    header('Location: settings.php');
    exit;
}

// Veritabanı işlemlerini gerçekleştir
try {
    // Mevcut ayarları kontrol et
    $stmt = $db->query("SELECT COUNT(*) FROM site_settings");
    $settingsExist = ($stmt->fetchColumn() > 0);
    
    // WhatsApp sütununu özel olarak kontrol et
    try {
        $whatsappColumnQuery = $db->query("SHOW COLUMNS FROM site_settings LIKE 'whatsapp_phone'");
        $hasWhatsappColumn = ($whatsappColumnQuery->rowCount() > 0);
        
        if (!$hasWhatsappColumn) {
            $db->exec("ALTER TABLE site_settings ADD COLUMN whatsapp_phone VARCHAR(20) DEFAULT ''");
            error_log("WhatsApp telefon sütunu eklendi.");
        }
    } catch (PDOException $e) {
        error_log("WhatsApp sütunu kontrolünde hata: " . $e->getMessage());
    }
    
    // site_settings tablosunda gerekli sütunlar var mı kontrol et
    $requiredColumns = [
        'page_header_bg' => "ALTER TABLE site_settings ADD COLUMN page_header_bg VARCHAR(100) DEFAULT 'page-header-bg.jpg'",
        'facebook_url' => "ALTER TABLE site_settings ADD COLUMN facebook_url VARCHAR(255) DEFAULT '#'",
        'twitter_url' => "ALTER TABLE site_settings ADD COLUMN twitter_url VARCHAR(255) DEFAULT '#'",
        'instagram_url' => "ALTER TABLE site_settings ADD COLUMN instagram_url VARCHAR(255) DEFAULT '#'",
        'linkedin_url' => "ALTER TABLE site_settings ADD COLUMN linkedin_url VARCHAR(255) DEFAULT '#'",
        'youtube_url' => "ALTER TABLE site_settings ADD COLUMN youtube_url VARCHAR(255) DEFAULT '#'",
        'whatsapp_phone' => "ALTER TABLE site_settings ADD COLUMN whatsapp_phone VARCHAR(20) DEFAULT ''",
        'site_description' => "ALTER TABLE site_settings ADD COLUMN site_description TEXT",
        'site_keywords' => "ALTER TABLE site_settings ADD COLUMN site_keywords TEXT"
    ];
    
    foreach ($requiredColumns as $column => $alterQuery) {
        $hasColumn = false;
        try {
            $columnsQuery = $db->query("SHOW COLUMNS FROM site_settings LIKE '$column'");
            $hasColumn = ($columnsQuery->rowCount() > 0);
        } catch (PDOException $e) {
            // Hata durumunda false olarak bırak
        }
        
        // Sütun yoksa ekle
        if (!$hasColumn) {
            try {
                $db->exec($alterQuery);
            } catch (PDOException $e) {
                // Sütun ekleme hatası
                $_SESSION['error_message'] = "Veritabanı sütunu eklenemedi: " . $e->getMessage();
                header('Location: settings.php');
                exit;
            }
        }
    }
    
    if ($settingsExist) {
        // Güncelleme
        $stmt = $db->prepare("UPDATE site_settings SET 
            site_title = ?, 
            contact_phone = ?, 
            contact_email = ?, 
            contact_address = ?, 
            color_scheme = ?, 
            page_header_bg = ?,
            facebook_url = ?,
            twitter_url = ?,
            instagram_url = ?,
            linkedin_url = ?,
            youtube_url = ?,
            whatsapp_phone = ?,
            site_description = ?,
            site_keywords = ?
            WHERE id = 1");
        $result = $stmt->execute([
            $siteTitle, 
            $contactPhone, 
            $contactEmail, 
            $contactAddress, 
            $colorScheme, 
            $pageHeaderBg,
            $facebookUrl,
            $twitterUrl,
            $instagramUrl,
            $linkedinUrl,
            $youtubeUrl,
            $whatsappPhone,
            $siteDescription,
            $siteKeywords
        ]);
    } else {
        // Yeni ekleme
        $stmt = $db->prepare("INSERT INTO site_settings (
            site_title, 
            contact_phone, 
            contact_email, 
            contact_address, 
            color_scheme, 
            page_header_bg,
            facebook_url,
            twitter_url,
            instagram_url,
            linkedin_url,
            youtube_url,
            whatsapp_phone,
            site_description,
            site_keywords
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $siteTitle, 
            $contactPhone, 
            $contactEmail, 
            $contactAddress, 
            $colorScheme, 
            $pageHeaderBg,
            $facebookUrl,
            $twitterUrl,
            $instagramUrl,
            $linkedinUrl,
            $youtubeUrl,
            $whatsappPhone,
            $siteDescription,
            $siteKeywords
        ]);
    }
    
    if ($result) {
        $_SESSION['success_message'] = "Site ayarları başarıyla güncellendi.";
        // Başarılı güncelleme için debug log
        error_log("Site ayarları başarıyla güncellendi: " . print_r([
            'site_title' => $siteTitle,
            'contact_phone' => $contactPhone,
            'contact_email' => $contactEmail,
            'color_scheme' => $colorScheme,
            'whatsapp_phone' => $whatsappPhone
        ], true));
    } else {
        $_SESSION['error_message'] = "Site ayarları güncellenirken bir hata oluştu.";
        error_log("Site ayarları güncellenirken bir hata oluştu!");
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
    error_log("Site ayarları veritabanı hatası: " . $e->getMessage());
    error_log("SQL sorgusu: " . $stmt->queryString);
}

// İşlem başarılı, kullanıcıyı settings sayfasına yönlendir
// Önbellekten kurtulmak için geçerli zamanı URL'ye ekle
$timestamp = time();

// Renk şeması değişikliği sonrası cache'i temizlemek için PHP header'larını ayarla
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT"); // Geçmiş tarih

// Yönlendirme yaparken mesajı session'da taşıyalım, URL parametreleri yerine
if (isset($_SESSION['success_message'])) {
    header("Location: settings.php?updated=1");
} else {
    header("Location: settings.php");
}
exit;