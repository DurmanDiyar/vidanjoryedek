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
$pageHeaderBg = isset($_POST['page_header_bg']) ? trim($_POST['page_header_bg']) : 'page-header-bg.jpg';

// Sosyal medya URL'leri
$facebookUrl = isset($_POST['facebook_url']) ? trim($_POST['facebook_url']) : '#';
$twitterUrl = isset($_POST['twitter_url']) ? trim($_POST['twitter_url']) : '#';
$instagramUrl = isset($_POST['instagram_url']) ? trim($_POST['instagram_url']) : '#';
$linkedinUrl = isset($_POST['linkedin_url']) ? trim($_POST['linkedin_url']) : '#';
$youtubeUrl = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '#';

// Boş URL'leri # karakteri ile değiştir
$facebookUrl = empty($facebookUrl) ? '#' : $facebookUrl;
$twitterUrl = empty($twitterUrl) ? '#' : $twitterUrl;
$instagramUrl = empty($instagramUrl) ? '#' : $instagramUrl;
$linkedinUrl = empty($linkedinUrl) ? '#' : $linkedinUrl;
$youtubeUrl = empty($youtubeUrl) ? '#' : $youtubeUrl;

// http:// veya https:// ile başlamıyorsa ve # değilse ekle
if (!empty($facebookUrl) && $facebookUrl !== '#' && !preg_match('/^https?:\/\//i', $facebookUrl)) {
    $facebookUrl = 'https://' . $facebookUrl;
}
if (!empty($twitterUrl) && $twitterUrl !== '#' && !preg_match('/^https?:\/\//i', $twitterUrl)) {
    $twitterUrl = 'https://' . $twitterUrl;
}
if (!empty($instagramUrl) && $instagramUrl !== '#' && !preg_match('/^https?:\/\//i', $instagramUrl)) {
    $instagramUrl = 'https://' . $instagramUrl;
}
if (!empty($linkedinUrl) && $linkedinUrl !== '#' && !preg_match('/^https?:\/\//i', $linkedinUrl)) {
    $linkedinUrl = 'https://' . $linkedinUrl;
}
if (!empty($youtubeUrl) && $youtubeUrl !== '#' && !preg_match('/^https?:\/\//i', $youtubeUrl)) {
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
    
    // site_settings tablosunda gerekli sütunlar var mı kontrol et
    $requiredColumns = [
        'page_header_bg' => "ALTER TABLE site_settings ADD COLUMN page_header_bg VARCHAR(100) DEFAULT 'page-header-bg.jpg'",
        'facebook_url' => "ALTER TABLE site_settings ADD COLUMN facebook_url VARCHAR(255) DEFAULT '#'",
        'twitter_url' => "ALTER TABLE site_settings ADD COLUMN twitter_url VARCHAR(255) DEFAULT '#'",
        'instagram_url' => "ALTER TABLE site_settings ADD COLUMN instagram_url VARCHAR(255) DEFAULT '#'",
        'linkedin_url' => "ALTER TABLE site_settings ADD COLUMN linkedin_url VARCHAR(255) DEFAULT '#'",
        'youtube_url' => "ALTER TABLE site_settings ADD COLUMN youtube_url VARCHAR(255) DEFAULT '#'"
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
            youtube_url = ?
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
            $youtubeUrl
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
            youtube_url
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
            $youtubeUrl
        ]);
    }
    
    if ($result) {
        $_SESSION['success_message'] = "Site ayarları başarıyla güncellendi.";
    } else {
        $_SESSION['error_message'] = "Site ayarları güncellenirken bir hata oluştu.";
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Veritabanı hatası: " . $e->getMessage();
}

// İşlem başarılı, kullanıcıyı settings sayfasına yönlendir
header("Location: settings.php?updated=1&t=" . time());
exit;