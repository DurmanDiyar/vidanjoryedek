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
    static $settings;
    
    if (!$settings) {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->query("SELECT * FROM site_settings WHERE id = 1");
            $settings = $stmt->fetch();
            
            if (!$settings) {
                // Default settings if not found in database
                $settings = [
                    'site_title' => 'Kurumsal Web Sitesi',
                    'contact_phone' => '+90 555 123 4567',
                    'contact_email' => 'info@example.com',
                    'contact_address' => 'İstanbul, Türkiye',
                    'color_scheme' => 'blue-green',
                    'page_header_bg' => 'page-header-bg.jpg',
                    'facebook_url' => '#',
                    'twitter_url' => '#',
                    'instagram_url' => '#',
                    'linkedin_url' => '#',
                    'youtube_url' => '#'
                ];
            }
            
            // Sosyal medya URL'leri yoksa varsayılan değerleri ata
            if (!isset($settings['facebook_url'])) $settings['facebook_url'] = '#';
            if (!isset($settings['twitter_url'])) $settings['twitter_url'] = '#';
            if (!isset($settings['instagram_url'])) $settings['instagram_url'] = '#';
            if (!isset($settings['linkedin_url'])) $settings['linkedin_url'] = '#';
            if (!isset($settings['youtube_url'])) $settings['youtube_url'] = '#';
            
        } catch (PDOException $e) {
            // Default settings if database error
            $settings = [
                'site_title' => 'Kurumsal Web Sitesi',
                'contact_phone' => '+90 555 123 4567',
                'contact_email' => 'info@example.com',
                'contact_address' => 'İstanbul, Türkiye',
                'color_scheme' => 'blue-green',
                'page_header_bg' => 'page-header-bg.jpg',
                'facebook_url' => '#',
                'twitter_url' => '#',
                'instagram_url' => '#',
                'linkedin_url' => '#',
                'youtube_url' => '#'
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
    // Veritabanından taze ayarları al ve önbelleğe almayı engelle
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query("SELECT color_scheme, page_header_bg FROM site_settings WHERE id = 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $colorScheme = isset($result['color_scheme']) ? $result['color_scheme'] : 'blue-green';
        $pageHeaderBg = isset($result['page_header_bg']) ? $result['page_header_bg'] : 'page-header-bg.jpg';
    } catch (PDOException $e) {
        // Hata durumunda varsayılan değerleri kullan
        $colorScheme = 'blue-green';
        $pageHeaderBg = 'page-header-bg.jpg';
    }
    
    $variables = [];
    
    // Renk şeması varyasyonları
    switch ($colorScheme) {
        case 'purple-pink':
            $variables = [
                '--primary-color' => '#6a1b9a',
                '--secondary-color' => '#9c27b0',
                '--accent-color' => '#e91e63',
                '--dark-color' => '#2c3e50',
                '--light-color' => '#f8f9fa',
                '--gray-color' => '#6c757d',
                '--heading-color' => '#333333'
            ];
            break;
            
        case 'red-orange':
            $variables = [
                '--primary-color' => '#b71c1c',
                '--secondary-color' => '#e53935',
                '--accent-color' => '#ff9800',
                '--dark-color' => '#2c3e50',
                '--light-color' => '#f8f9fa',
                '--gray-color' => '#6c757d',
                '--heading-color' => '#333333'
            ];
            break;
            
        case 'dark-blue':
            $variables = [
                '--primary-color' => '#1a237e',
                '--secondary-color' => '#3949ab',
                '--accent-color' => '#00bcd4',
                '--dark-color' => '#2c3e50',
                '--light-color' => '#f8f9fa',
                '--gray-color' => '#6c757d',
                '--heading-color' => '#333333'
            ];
            break;
            
        case 'green-brown':
            $variables = [
                '--primary-color' => '#2e7d32',
                '--secondary-color' => '#558b2f',
                '--accent-color' => '#795548',
                '--dark-color' => '#2c3e50',
                '--light-color' => '#f8f9fa',
                '--gray-color' => '#6c757d',
                '--heading-color' => '#333333'
            ];
            break;
            
        case 'blue-green':
        default:
            $variables = [
                '--primary-color' => '#1a5f7a',
                '--secondary-color' => '#2c8a8a',
                '--accent-color' => '#4caf50',
                '--dark-color' => '#2c3e50',
                '--light-color' => '#f8f9fa',
                '--gray-color' => '#6c757d',
                '--heading-color' => '#333333'
            ];
            break;
    }
    
    // Önbelleğe alma sorununu çözmek için timestamp ekliyoruz
    $cacheBuster = time();
    
    // CSS stilini oluştur
    $css = "<!-- Color scheme: $colorScheme (updated: $cacheBuster) -->\n<style>";
    $css .= "\n:root {";
    foreach ($variables as $key => $value) {
        $css .= "\n    $key: $value;";
    }
    $css .= "\n}";
    
    // Ana içerik alanları için CSS kuralları
    $css .= "\n\n/* Ana içerik alanı için renk uygulamaları */";
    $css .= "\n.btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }";
    $css .= "\n.btn-primary:hover, .btn-primary:focus { background-color: var(--secondary-color); border-color: var(--secondary-color); }";
    $css .= "\n.text-primary { color: var(--primary-color) !important; }";
    $css .= "\n.bg-primary { background-color: var(--primary-color) !important; }";
    $css .= "\n.section-title { color: var(--primary-color); }";
    $css .= "\n.service-card:hover { border-color: var(--primary-color); }";
    $css .= "\n.nav-link:hover, .nav-link.active { color: var(--primary-color) !important; }";
    $css .= "\n.main-content a:not(.btn) { color: var(--primary-color); }";
    $css .= "\n.main-content a:not(.btn):hover { color: var(--secondary-color); }";
    $css .= "\n.main-content .card-header { background-color: var(--primary-color); color: white; }";
    $css .= "\n.main-content .section-heading::after { background-color: var(--primary-color); }";
    
    // Header ve menu için CSS kuralları
    $css .= "\n\n/* Header ve Menü renk kuralları */";
    $css .= "\n.navbar .nav-link:hover, .navbar .nav-link.active { border-bottom: 2px solid var(--primary-color); }";
    $css .= "\n.top-bar .social-links a:hover { color: var(--accent-color) !important; }";
    
    // Slider için CSS kuralları
    $css .= "\n\n/* Slider ve butonlar için renk kuralları */";
    $css .= "\n.carousel-caption .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }";
    $css .= "\n.carousel-caption .btn-primary:hover { background-color: var(--secondary-color); border-color: var(--secondary-color); }";
    
    // Ortak sayfa başlığı stili
    $css .= "\n.page-header {";
    $css .= "\n    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('" . SITE_URL . "/assets/img/$pageHeaderBg') center center no-repeat;";
    $css .= "\n    background-size: cover;";
    $css .= "\n    color: white;";
    $css .= "\n    padding-top: 3rem;";
    $css .= "\n    padding-bottom: 3rem;";
    $css .= "\n    margin-bottom: 3rem;";
    $css .= "\n    text-align: center;";
    $css .= "\n    position: relative;";
    $css .= "\n}";
    
    $css .= "\n.page-header::before {";
    $css .= "\n    content: '';";
    $css .= "\n    position: absolute;";
    $css .= "\n    top: 0;";
    $css .= "\n    left: 0;";
    $css .= "\n    width: 100%;";
    $css .= "\n    height: 100%;";
    $css .= "\n    background-color: var(--primary-color);";
    $css .= "\n    opacity: 0.2;";
    $css .= "\n}";
    
    $css .= "\n.page-header .container {";
    $css .= "\n    position: relative;";
    $css .= "\n    z-index: 1;";
    $css .= "\n}";
    
    // Renk geçişleri için animasyon
    $css .= "\n/* Renk geçişi animasyonları */";
    $css .= "\na, button, .btn, .nav-link, .service-card, .card { transition: all 0.3s ease-in-out; }";
    
    $css .= "\n</style>";
    
    return $css;
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