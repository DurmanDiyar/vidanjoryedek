<?php
/**
 * Configuration file for the corporate website
 * Contains database connection settings and site configuration
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'corporate_site');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site configuration
define('SITE_URL', 'http://localhost/proje2');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('ADMIN_URL', SITE_URL . '/admin');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Database connection using PDO
 * @return PDO Database connection object
 */
function getDbConnection() {
    static $pdo;
    
    if (!$pdo) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Get site settings from database
 * @return array Site settings
 */
function getSiteSettings() {
    static $settings = null;
    
    if ($settings === null) {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1");
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data) {
                $settings = [
                    'site_title' => $data['site_title'] ?? 'Kurumsal Web Sitesi',
                    'contact_phone' => $data['contact_phone'] ?? '+90 555 123 4567',
                    'contact_email' => $data['contact_email'] ?? 'info@example.com',
                    'contact_address' => $data['contact_address'] ?? 'Örnek Mahallesi, Örnek Caddesi No:123, İstanbul',
                    'color_scheme' => $data['color_scheme'] ?? 'blue-green',
                    'page_header_bg' => $data['page_header_bg'] ?? 'page-header-bg.jpg',
                    'facebook_url' => $data['facebook_url'] ?? '#',
                    'twitter_url' => $data['twitter_url'] ?? '#',
                    'instagram_url' => $data['instagram_url'] ?? '#',
                    'linkedin_url' => $data['linkedin_url'] ?? '#',
                    'youtube_url' => $data['youtube_url'] ?? '#',
                    'whatsapp_phone' => $data['whatsapp_phone'] ?? '',
                    'site_description' => $data['site_description'] ?? 'Profesyonel kurumsal hizmetler sunan web sitemize hoş geldiniz.',
                    'site_keywords' => $data['site_keywords'] ?? 'kurumsal, hizmetler, profesyonel'
                ];
            } else {
                $settings = [
                    'site_title' => 'Kurumsal Web Sitesi',
                    'contact_phone' => '+90 555 123 4567',
                    'contact_email' => 'info@example.com', 
                    'contact_address' => 'Örnek Mahallesi, Örnek Caddesi No:123, İstanbul',
                    'color_scheme' => 'blue-green',
                    'page_header_bg' => 'page-header-bg.jpg',
                    'facebook_url' => '#',
                    'twitter_url' => '#',
                    'instagram_url' => '#',
                    'linkedin_url' => '#', 
                    'youtube_url' => '#',
                    'whatsapp_phone' => '',
                    'site_description' => 'Profesyonel kurumsal hizmetler sunan web sitemize hoş geldiniz.',
                    'site_keywords' => 'kurumsal, hizmetler, profesyonel'
                ];
            }
        } catch (PDOException $e) {
            $settings = [
                'site_title' => 'Kurumsal Web Sitesi',
                'contact_phone' => '+90 555 123 4567',
                'contact_email' => 'info@example.com',
                'contact_address' => 'Örnek Mahallesi, Örnek Caddesi No:123, İstanbul',
                'color_scheme' => 'blue-green',
                'page_header_bg' => 'page-header-bg.jpg',
                'facebook_url' => '#',
                'twitter_url' => '#',
                'instagram_url' => '#',
                'linkedin_url' => '#',
                'youtube_url' => '#',
                'whatsapp_phone' => '',
                'site_description' => 'Profesyonel kurumsal hizmetler sunan web sitemize hoş geldiniz.',
                'site_keywords' => 'kurumsal, hizmetler, profesyonel'
            ];
        }
    }
    
    return $settings;
}

/**
 * Site renk şeması değişkenlerini döndürür
 * 
 * @return string Renk şeması CSS değişkenleri
 */
function getColorSchemeVariables() {
    try {
        // Her seferinde yeni PDO bağlantısı oluştur
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $db = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        // Her zaman taze veri almak için önbelleği kapat
        $cacheBuster = time();
        
        // Site ayarlarını sorgula
        $stmt = $db->prepare("SELECT color_scheme FROM site_settings ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Renk şeması değerini al veya varsayılan değer kullan
        $colorScheme = isset($settings['color_scheme']) ? $settings['color_scheme'] : 'blue-green';
        
        // Debug: renk şemasını loglayalım
        error_log("getColorSchemeVariables() fonksiyonunda kullanılan renk şeması: " . $colorScheme);
        
        // Renk şeması değerine göre CSS değişkenlerini tanımla
        switch ($colorScheme) {
            case 'blue-green':
                return "
                    <style>
                        :root {
                            --primary-color: #1a73e8;
                            --secondary-color: #34a853;
                            --accent-color: #fbbc04;
                            --dark-color: #202124;
                            --light-color: #f8f9fa;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #1a73e8;
                            --footer-bg-color: #202124;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
            case 'red-orange':
                return "
                    <style>
                        :root {
                            --primary-color: #ea4335;
                            --secondary-color: #ff7043;
                            --accent-color: #ffca28;
                            --dark-color: #3e2723;
                            --light-color: #fff3e0;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #ea4335;
                            --footer-bg-color: #3e2723;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
            case 'purple-pink':
                return "
                    <style>
                        :root {
                            --primary-color: #673ab7;
                            --secondary-color: #e91e63;
                            --accent-color: #ffc107;
                            --dark-color: #311b92;
                            --light-color: #f3e5f5;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #673ab7;
                            --footer-bg-color: #311b92;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
            case 'green-teal':
                return "
                    <style>
                        :root {
                            --primary-color: #4caf50;
                            --secondary-color: #009688;
                            --accent-color: #ffd600;
                            --dark-color: #1b5e20;
                            --light-color: #e8f5e9;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #4caf50;
                            --footer-bg-color: #1b5e20;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
            case 'dark-blue':
                return "
                    <style>
                        :root {
                            --primary-color: #0d47a1;
                            --secondary-color: #29b6f6;
                            --accent-color: #ffd600;
                            --dark-color: #002171;
                            --light-color: #e3f2fd;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #0d47a1;
                            --footer-bg-color: #002171;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
            case 'green-brown':
                return "
                    <style>
                        :root {
                            --primary-color: #4caf50;
                            --secondary-color: #8d6e63;
                            --accent-color: #ffc107;
                            --dark-color: #1b5e20;
                            --light-color: #e8f5e9;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #4caf50;
                            --footer-bg-color: #3e2723;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
            default:
                return "
                    <style>
                        :root {
                            --primary-color: #1a73e8;
                            --secondary-color: #34a853;
                            --accent-color: #fbbc04;
                            --dark-color: #202124;
                            --light-color: #f8f9fa;
                            --main-bg-color: #ffffff;
                            --header-bg-color: #1a73e8;
                            --footer-bg-color: #202124;
                        }
                    </style>
                    <!-- Cache Buster: {$cacheBuster} -->
                ";
                break;
        }
    } catch (PDOException $e) {
        // Hata durumunda varsayılan renk şemasını döndür
        error_log("Renk şeması alınırken hata oluştu: " . $e->getMessage());
        $cacheBuster = time();
        return "
            <style>
                :root {
                    --primary-color: #1a73e8;
                    --secondary-color: #34a853;
                    --accent-color: #fbbc04;
                    --dark-color: #202124;
                    --light-color: #f8f9fa;
                    --main-bg-color: #ffffff;
                    --header-bg-color: #1a73e8;
                    --footer-bg-color: #202124;
                }
            </style>
            <!-- Cache Buster Fallback: {$cacheBuster} -->
        ";
    }
}

/**
 * Sanitize user input
 * @param string $input Input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 * @return string CSRF token
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool True if token is valid
 */
function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

/**
 * Get valid social media URL or return empty string
 * @param string $url Social media URL
 * @return string Valid URL or empty string
 */
function getSocialMediaUrl($url) {
    // URL boş veya # ise boş döndür
    if (empty($url) || $url === '#') {
        return '';
    }
    
    // URL validasyonu yap
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    
    // http:// veya https:// ile başlamıyorsa ekle
    if (!preg_match('/^https?:\/\//i', $url)) {
        $url = 'https://' . $url;
        
        // Düzeltmeden sonra geçerliyse döndür
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
    }
    
    // Geçersiz URL
    return '';
}

/**
 * Redirect to URL
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Get base URL for creating links
 * 
 * @param string $path Optional path to append to base URL
 * @return string Full URL
 */
function baseUrl($path = '') {
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    if (!empty(dirname($_SERVER['PHP_SELF'])) && dirname($_SERVER['PHP_SELF']) !== '/') {
        $baseUrl .= dirname($_SERVER['PHP_SELF']);
    }
    
    // Ensure base URL ends with a slash
    if (substr($baseUrl, -1) !== '/') {
        $baseUrl .= '/';
    }
    
    // Ensure path doesn't start with a slash
    if (strlen($path) > 0 && $path[0] === '/') {
        $path = substr($path, 1);
    }
    
    return $baseUrl . $path;
}