<?php
/**
 * Admin Panel - Site Ayarları Yönetimi
 * 
 * Bu sayfa, site başlığı, iletişim bilgileri ve diğer genel ayarların yönetimini sağlar.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Oturum kontrolü
session_start();

// Include database connection
require_once '../config.php';

// Oturum kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısını al
try {
    $db = getDbConnection();
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Mesaj değişkenleri
$message = '';
$messageType = '';

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
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
        $message = 'Site başlığı boş olamaz.';
        $messageType = 'danger';
    } elseif (!empty($contactEmail) && !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        $message = 'Geçerli bir e-posta adresi girin.';
        $messageType = 'danger';
    } else {
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
                        $message = 'Veritabanı sütunu eklenemedi: ' . $e->getMessage();
                        $messageType = 'danger';
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
                $message = 'Site ayarları başarıyla güncellendi.';
                $messageType = 'success';
            } else {
                $message = 'Site ayarları güncellenirken bir hata oluştu.';
                $messageType = 'danger';
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Mevcut ayarları getir
try {
    $stmt = $db->query("SELECT * FROM site_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Eksik sütunlar için varsayılan değerleri atayalım
    if (!isset($settings['page_header_bg'])) {
        $settings['page_header_bg'] = 'page-header-bg.jpg';
    }
    if (!isset($settings['facebook_url'])) {
        $settings['facebook_url'] = '#';
    }
    if (!isset($settings['twitter_url'])) {
        $settings['twitter_url'] = '#';
    }
    if (!isset($settings['instagram_url'])) {
        $settings['instagram_url'] = '#';
    }
    if (!isset($settings['linkedin_url'])) {
        $settings['linkedin_url'] = '#';
    }
    if (!isset($settings['youtube_url'])) {
        $settings['youtube_url'] = '#';
    }
} catch (PDOException $e) {
    $settings = [
        'site_title' => '',
        'contact_phone' => '',
        'contact_email' => '',
        'contact_address' => '',
        'color_scheme' => 'blue-green',
        'page_header_bg' => 'page-header-bg.jpg',
        'facebook_url' => '#',
        'twitter_url' => '#',
        'instagram_url' => '#',
        'linkedin_url' => '#',
        'youtube_url' => '#'
    ];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// Sayfa başlığı
$pageTitle = 'Site Ayarları';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Site Ayarları</h1>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Genel Ayarlar</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="process_settings.php">
                        <div class="mb-3">
                            <label for="site_title" class="form-label">Site Başlığı</label>
                            <input type="text" class="form-control" id="site_title" name="site_title" value="<?php echo isset($settings['site_title']) ? htmlspecialchars($settings['site_title']) : ''; ?>" required>
                            <div class="form-text">Site başlığı, tarayıcı sekmesinde ve site üst kısmında görünecektir.</div>
                        </div>
                        
                        <h5 class="mt-4 mb-3">İletişim Bilgileri</h5>
                        
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Telefon Numarası</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo isset($settings['contact_phone']) ? htmlspecialchars($settings['contact_phone']) : ''; ?>">
                            <div class="form-text">Örnek: +90 555 123 4567</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">E-posta Adresi</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo isset($settings['contact_email']) ? htmlspecialchars($settings['contact_email']) : ''; ?>">
                            <div class="form-text">Örnek: info@example.com</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="contact_address" class="form-label">Adres</label>
                            <textarea class="form-control" id="contact_address" name="contact_address" rows="3"><?php echo isset($settings['contact_address']) ? htmlspecialchars($settings['contact_address']) : ''; ?></textarea>
                            <div class="form-text">Şirket veya işletmenizin tam adresi.</div>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Görünüm Ayarları</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="color_scheme" class="form-label">Site Renk Şeması</label>
                                    <select class="form-select" id="color_scheme" name="color_scheme">
                                        <option value="blue-green" <?php echo ($settings['color_scheme'] == 'blue-green') ? 'selected' : ''; ?>>Mavi-Yeşil (Varsayılan)</option>
                                        <option value="purple-pink" <?php echo ($settings['color_scheme'] == 'purple-pink') ? 'selected' : ''; ?>>Mor-Pembe</option>
                                        <option value="red-orange" <?php echo ($settings['color_scheme'] == 'red-orange') ? 'selected' : ''; ?>>Kırmızı-Turuncu</option>
                                        <option value="dark-blue" <?php echo ($settings['color_scheme'] == 'dark-blue') ? 'selected' : ''; ?>>Koyu Mavi</option>
                                        <option value="green-brown" <?php echo ($settings['color_scheme'] == 'green-brown') ? 'selected' : ''; ?>>Yeşil-Kahverengi</option>
                                    </select>
                                    <div id="colorPreview" class="mt-2 p-3 rounded text-white" style="background-color: <?php echo getColorPreviewBackground($settings['color_scheme']); ?>">
                                        Renk önizleme
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="page_header_bg" class="form-label">Sayfa Başlık Arkaplanı</label>
                                    <select class="form-select" id="page_header_bg" name="page_header_bg">
                                        <option value="page-header-bg.jpg" <?php echo ($settings['page_header_bg'] === 'page-header-bg.jpg') ? 'selected' : ''; ?>>Varsayılan Arkaplan</option>
                                        <option value="header-bg-1.jpg" <?php echo ($settings['page_header_bg'] === 'header-bg-1.jpg') ? 'selected' : ''; ?>>Arkaplan 1</option>
                                        <option value="header-bg-2.jpg" <?php echo ($settings['page_header_bg'] === 'header-bg-2.jpg') ? 'selected' : ''; ?>>Arkaplan 2</option>
                                        <option value="header-bg-3.jpg" <?php echo ($settings['page_header_bg'] === 'header-bg-3.jpg') ? 'selected' : ''; ?>>Arkaplan 3</option>
                                        <option value="header-bg-4.jpg" <?php echo ($settings['page_header_bg'] === 'header-bg-4.jpg') ? 'selected' : ''; ?>>Arkaplan 4</option>
                                    </select>
                                    <div class="mt-2">
                                        <img id="headerBgPreview" src="<?php echo SITE_URL; ?>/assets/img/<?php echo $settings['page_header_bg']; ?>" alt="Arkaplan Önizleme" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Sosyal Medya Ayarları</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                    <input type="url" class="form-control social-media-input" id="facebook_url" name="facebook_url" placeholder="Facebook URL" value="<?php echo isset($settings['facebook_url']) ? htmlspecialchars($settings['facebook_url']) : '#'; ?>" data-platform="facebook">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                    <input type="url" class="form-control social-media-input" id="twitter_url" name="twitter_url" placeholder="Twitter URL" value="<?php echo isset($settings['twitter_url']) ? htmlspecialchars($settings['twitter_url']) : '#'; ?>" data-platform="twitter">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                    <input type="url" class="form-control social-media-input" id="instagram_url" name="instagram_url" placeholder="Instagram URL" value="<?php echo isset($settings['instagram_url']) ? htmlspecialchars($settings['instagram_url']) : '#'; ?>" data-platform="instagram">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                    <input type="url" class="form-control social-media-input" id="linkedin_url" name="linkedin_url" placeholder="LinkedIn URL" value="<?php echo isset($settings['linkedin_url']) ? htmlspecialchars($settings['linkedin_url']) : '#'; ?>" data-platform="linkedin">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                    <input type="url" class="form-control social-media-input" id="youtube_url" name="youtube_url" placeholder="YouTube URL" value="<?php echo isset($settings['youtube_url']) ? htmlspecialchars($settings['youtube_url']) : '#'; ?>" data-platform="youtube">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div id="socialMediaPreview" class="d-flex">
                                    <!-- Dinamik önizleme burada gösterilecek -->
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mb-3">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                Sosyal medya bağlantılarının çalışması için tam URL'leri girin (örn: https://facebook.com/yourpage). 
                                Kullanılmayan platformlar için # karakteri girin veya boş bırakın. Boş bırakılan veya # olan bağlantılar sitede gösterilmeyecektir.
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Ayarları Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Yardım</h6>
                </div>
                <div class="card-body">
                    <p>Bu sayfada sitenizin genel ayarlarını yapılandırabilirsiniz. Bu ayarlar, sitenizin başlığı, iletişim bilgileri ve renk şeması gibi temel bilgileri içerir.</p>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Bilgi</h6>
                        <p class="mb-0">Site başlığı, tarayıcı sekmesinde ve site üst kısmında görünecektir. İletişim bilgileri ise iletişim sayfası ve site alt kısmında kullanılacaktır.</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Dikkat</h6>
                        <p class="mb-0">Tüm değişiklikler anında siteye yansıyacaktır. Lütfen bilgileri doğru girdiğinizden emin olun.</p>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Önizleme</h6>
                </div>
                <div class="card-body">
                    <h5><?php echo isset($settings['site_title']) ? htmlspecialchars($settings['site_title']) : 'Site Başlığı'; ?></h5>
                    
                    <div class="mt-3">
                        <?php if (!empty($settings['contact_phone'])): ?>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($settings['contact_phone']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['contact_email'])): ?>
                            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($settings['contact_email']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['contact_address'])): ?>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($settings['contact_address']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Sosyal Medya</h6>
                        <div class="social-preview">
                            <?php if (!empty($settings['facebook_url']) && $settings['facebook_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank" class="me-2" style="color: var(--primary-color); font-size: 1.2rem;">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['twitter_url']) && $settings['twitter_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank" class="me-2" style="color: var(--primary-color); font-size: 1.2rem;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['instagram_url']) && $settings['instagram_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" class="me-2" style="color: var(--primary-color); font-size: 1.2rem;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['linkedin_url']) && $settings['linkedin_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank" class="me-2" style="color: var(--primary-color); font-size: 1.2rem;">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['youtube_url']) && $settings['youtube_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank" class="me-2" style="color: var(--primary-color); font-size: 1.2rem;">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php 
                            if (
                                (empty($settings['facebook_url']) || $settings['facebook_url'] == '#') &&
                                (empty($settings['twitter_url']) || $settings['twitter_url'] == '#') &&
                                (empty($settings['instagram_url']) || $settings['instagram_url'] == '#') &&
                                (empty($settings['linkedin_url']) || $settings['linkedin_url'] == '#') &&
                                (empty($settings['youtube_url']) || $settings['youtube_url'] == '#')
                            ): ?>
                                <p class="text-muted small">Sosyal medya bağlantısı ayarlanmadı.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Renk şeması için arkaplan rengini döndürür
 * 
 * @param string $colorScheme Renk şeması
 * @return string Renk şeması için arkaplan rengi
 */
function getColorPreviewBackground($colorScheme) {
    switch ($colorScheme) {
        case 'purple-pink':
            return '#6a1b9a';
        case 'red-orange':
            return '#b71c1c';
        case 'dark-blue':
            return '#1a237e';
        case 'green-brown':
            return '#2e7d32';
        case 'blue-green':
        default:
            return '#1a5f7a';
    }
}
?>

<script>
// Renk şeması seçimi değiştiğinde önizleme rengini güncelle
document.addEventListener('DOMContentLoaded', function() {
    const colorSchemeSelect = document.getElementById('color_scheme');
    const colorPreview = document.getElementById('colorPreview');
    const headerBgSelect = document.getElementById('page_header_bg');
    const headerBgPreview = document.getElementById('headerBgPreview');
    
    // Renk şeması değiştiğinde
    colorSchemeSelect.addEventListener('change', function() {
        let backgroundColor = '#1a5f7a'; // Varsayılan
        
        switch(this.value) {
            case 'purple-pink':
                backgroundColor = '#6a1b9a';
                break;
            case 'red-orange':
                backgroundColor = '#b71c1c';
                break;
            case 'dark-blue':
                backgroundColor = '#1a237e';
                break;
            case 'green-brown':
                backgroundColor = '#2e7d32';
                break;
        }
        
        colorPreview.style.backgroundColor = backgroundColor;
        
        // Canlı önizleme için CSS değişkenlerini güncelle
        updateRootVariables(this.value);
    });
    
    // Arkaplan değiştiğinde
    headerBgSelect.addEventListener('change', function() {
        headerBgPreview.src = '<?php echo SITE_URL; ?>/assets/img/' + this.value;
    });

    // Sosyal medya önizleme fonksiyonları
    const socialMediaInputs = document.querySelectorAll('.social-media-input');
    const socialMediaPreview = document.getElementById('socialMediaPreview');
    
    // Sosyal medya önizlemesini oluştur
    function updateSocialMediaPreview() {
        socialMediaPreview.innerHTML = '';
        
        // Her sosyal medya giriş alanı için
        socialMediaInputs.forEach(input => {
            const url = input.value.trim();
            const platform = input.dataset.platform;
            
            // URL # değilse ve boş değilse
            if (url && url !== '#') {
                const iconClass = `fa-${platform === 'linkedin' ? 'linkedin-in' : platform}`;
                const iconColor = getIconColor(platform);
                
                // Önizleme ikonu oluştur
                const previewIcon = document.createElement('a');
                previewIcon.setAttribute('href', '#');
                previewIcon.setAttribute('class', 'me-2');
                previewIcon.innerHTML = `<i class="fab ${iconClass}" style="font-size: 1.5rem; color: ${iconColor};"></i>`;
                
                socialMediaPreview.appendChild(previewIcon);
            }
        });
        
        // Hiç ikon yoksa mesaj göster
        if (socialMediaPreview.children.length === 0) {
            socialMediaPreview.innerHTML = '<small class="text-muted">Aktif sosyal medya bağlantısı yok</small>';
        }
    }
    
    // Platform için renk döndürür
    function getIconColor(platform) {
        switch (platform) {
            case 'facebook':
                return '#1877f2';
            case 'twitter':
                return '#1da1f2';
            case 'instagram':
                return '#e4405f';
            case 'linkedin':
                return '#0077b5';
            case 'youtube':
                return '#ff0000';
            default:
                return '#666666';
        }
    }
    
    // CSS root değişkenlerini güncelleme fonksiyonu
    function updateRootVariables(colorScheme) {
        let primaryColor, secondaryColor, accentColor;
        
        switch(colorScheme) {
            case 'purple-pink':
                primaryColor = '#6a1b9a';
                secondaryColor = '#9c27b0';
                accentColor = '#e91e63';
                break;
            case 'red-orange':
                primaryColor = '#b71c1c';
                secondaryColor = '#e53935';
                accentColor = '#ff9800';
                break;
            case 'dark-blue':
                primaryColor = '#1a237e';
                secondaryColor = '#3949ab';
                accentColor = '#00bcd4';
                break;
            case 'green-brown':
                primaryColor = '#2e7d32';
                secondaryColor = '#558b2f';
                accentColor = '#795548';
                break;
            case 'blue-green':
            default:
                primaryColor = '#1a5f7a';
                secondaryColor = '#2c8a8a';
                accentColor = '#4caf50';
                break;
        }
        
        // Root CSS değişkenlerini güncelle
        document.documentElement.style.setProperty('--primary-color', primaryColor);
        document.documentElement.style.setProperty('--secondary-color', secondaryColor);
        document.documentElement.style.setProperty('--accent-color', accentColor);
        
        // Admin panel bileşenlerini güncelle
        document.querySelectorAll('.card-header').forEach(el => {
            el.style.backgroundColor = secondaryColor;
        });
        
        document.querySelectorAll('.btn-primary').forEach(el => {
            el.style.backgroundColor = primaryColor;
            el.style.borderColor = primaryColor;
        });
    }
    
    // Form gönderiminden önce
    document.querySelector('form').addEventListener('submit', function(e) {
        // Form gönderiminden önce önbellek temizleme işlemi
        localStorage.setItem('settingsUpdated', Date.now());
        
        // Normal form gönderimi devam eder, yeniden yüklemeyi önlemiyoruz
        // Sadece cache buster ekliyoruz
        return true;
    });
    
    // Başlangıçta önizlemeyi güncelle
    updateSocialMediaPreview();
    
    // Sayfa yüklendiğinde, eğer ayarlar güncellenmiş ise
    if (localStorage.getItem('settingsUpdated')) {
        // Önbelleği temizle
        localStorage.removeItem('settingsUpdated');
        
        // CSS ve renk şeması değişkenleri güncellensin diye sayfayı hard refresh yapıyoruz
        if (window.location.search.indexOf('updated=1') === -1) {
            // Eğer zaten updated parametresi yoksa, ekleyerek sayfayı yeniliyoruz
            window.location.href = window.location.href + (window.location.search ? '&' : '?') + 'updated=1&t=' + new Date().getTime();
        }
    }
    
    // Sosyal medya giriş alanları değiştiğinde
    socialMediaInputs.forEach(input => {
        input.addEventListener('input', updateSocialMediaPreview);
    });
});
</script>

<?php
// Footer'ı dahil et
include 'includes/footer.php';
?> 