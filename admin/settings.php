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
    // Debugging
    error_log("Form gönderildi: " . print_r($_POST, true));
    
    // Form verilerini al
    $siteTitle = isset($_POST['site_title']) ? trim($_POST['site_title']) : '';
    $contactPhone = isset($_POST['contact_phone']) ? trim($_POST['contact_phone']) : '';
    $contactEmail = isset($_POST['contact_email']) ? trim($_POST['contact_email']) : '';
    $contactAddress = isset($_POST['contact_address']) ? trim($_POST['contact_address']) : '';
    $colorScheme = isset($_POST['color_scheme']) ? trim($_POST['color_scheme']) : 'blue-green';
    $pageHeaderBg = isset($_POST['page_header_bg']) ? trim($_POST['page_header_bg']) : 'page-header-bg.jpg';
    $whatsappPhone = isset($_POST['whatsapp_phone']) ? $_POST['whatsapp_phone'] : '';
    $whatsappPhone = preg_replace('/[^0-9+]/', '', $whatsappPhone); // Sadece sayıları ve + işaretini tut
    
    // SEO ayarları
    $siteDescription = isset($_POST['site_description']) ? trim($_POST['site_description']) : '';
    $siteKeywords = isset($_POST['site_keywords']) ? trim($_POST['site_keywords']) : '';
    
    // Renk şeması onaylama (color_scheme_confirmed varsa onu kullan)
    if (isset($_POST['color_scheme_confirmed']) && !empty($_POST['color_scheme_confirmed'])) {
        $colorScheme = trim($_POST['color_scheme_confirmed']);
        error_log("Form onaylanmış renk şeması: " . $colorScheme);
    }
    
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
                'youtube_url' => "ALTER TABLE site_settings ADD COLUMN youtube_url VARCHAR(255) DEFAULT '#'",
                'whatsapp_phone' => "ALTER TABLE site_settings ADD COLUMN whatsapp_phone VARCHAR(20) DEFAULT ''",
                'site_description' => "ALTER TABLE site_settings ADD COLUMN site_description TEXT DEFAULT 'Profesyonel kurumsal hizmetler sunan web sitemize hoş geldiniz.'",
                'site_keywords' => "ALTER TABLE site_settings ADD COLUMN site_keywords VARCHAR(255) DEFAULT 'kurumsal, hizmetler, profesyonel'"
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
    if (!isset($settings['whatsapp_phone'])) {
        $settings['whatsapp_phone'] = '';
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
        'youtube_url' => '#',
        'whatsapp_phone' => ''
    ];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// Sayfa başlığı
$pageTitle = 'Site Ayarları';

// Header'ı dahil et
include 'includes/header.php';

// Başarı mesajı göster
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> ' . $_SESSION['success_message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    // Mesajı gösterdikten sonra session'dan temizle
    unset($_SESSION['success_message']);
} else if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> ' . $_SESSION['error_message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    // Mesajı gösterdikten sonra session'dan temizle
    unset($_SESSION['error_message']);
} else if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> Site ayarları başarıyla güncellendi!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}

// Eğer form içerisinde hata veya başarı mesajı varsa göster
if (!empty($message)) {
    echo '<div class="alert alert-' . $messageType . ' alert-dismissible fade show" role="alert">
            ' . $message . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Site Ayarları</h1>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Genel Ayarlar</h6>
                </div>
                <div class="card-body">
                    <form id="settingsForm" action="settings.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="form_submit" value="1">
                        <input type="hidden" name="settings_timestamp" value="<?php echo time(); ?>">
                        <h5 class="mb-4">Temel Bilgiler</h5>
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
                            <textarea class="form-control" id="contact_address" name="contact_address" rows="3"><?php echo htmlspecialchars($settings['contact_address']); ?></textarea>
                            <div class="form-text">Şirket veya işletmenizin tam adresi.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="whatsapp_phone" class="form-label">WhatsApp Telefon Numarası</label>
                            <input type="text" class="form-control" id="whatsapp_phone" name="whatsapp_phone" 
                            value="<?php echo isset($settings['whatsapp_phone']) ? htmlspecialchars($settings['whatsapp_phone']) : ''; ?>" 
                            placeholder="Örn: +905551234567">
                            <div class="form-text">WhatsApp butonu için telefon numarası (ülke kodu ile birlikte). Örn: +905551234567</div>
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
                                        <option value="green-teal" <?php echo ($settings['color_scheme'] == 'green-teal') ? 'selected' : ''; ?>>Yeşil-Turkuaz</option>
                                        <option value="green-brown" <?php echo ($settings['color_scheme'] == 'green-brown') ? 'selected' : ''; ?>>Yeşil-Kahverengi</option>
                                    </select>
                                    <div class="form-text">Sitenin genel renk temasını belirler. Tüm site elementleri bu temadan etkilenir.</div>
                                    
                                    <!-- Renk Şeması Önizleme -->
                                    <div class="mt-3">
                                        <label class="form-label">Renk Önizleme:</label>
                                        <div class="d-flex gap-2 mb-2">
                                            <div id="primaryColor" class="color-box rounded" style="width:40px;height:40px;background-color:var(--primary-color)" title="Ana Renk"></div>
                                            <div id="secondaryColor" class="color-box rounded" style="width:40px;height:40px;background-color:var(--secondary-color)" title="İkincil Renk"></div>
                                            <div id="accentColor" class="color-box rounded" style="width:40px;height:40px;background-color:var(--accent-color)" title="Vurgu Rengi"></div>
                                        </div>
                                        
                                        <!-- UI Öğeleri Önizleme -->
                                        <div class="border p-3 rounded bg-light mt-2">
                                            <h5 class="preview-title" style="color:var(--primary-color)">Önizleme Başlığı</h5>
                                            <p class="small">Bu önizleme, seçtiğiniz renk şemasının site genelinde nasıl görüneceğini gösterir.</p>
                                            <button class="btn btn-sm btn-primary me-2">Ana Buton</button>
                                            <button class="btn btn-sm btn-secondary">İkincil Buton</button>
                                        </div>
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
                                    <input type="url" class="form-control social-media-input" id="facebook_url" name="facebook_url" placeholder="Facebook URL (Opsiyonel)" value="<?php echo isset($settings['facebook_url']) && $settings['facebook_url'] !== '#' ? htmlspecialchars($settings['facebook_url']) : ''; ?>" data-platform="facebook">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                    <input type="url" class="form-control social-media-input" id="twitter_url" name="twitter_url" placeholder="Twitter URL (Opsiyonel)" value="<?php echo isset($settings['twitter_url']) && $settings['twitter_url'] !== '#' ? htmlspecialchars($settings['twitter_url']) : ''; ?>" data-platform="twitter">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                    <input type="url" class="form-control social-media-input" id="instagram_url" name="instagram_url" placeholder="Instagram URL (Opsiyonel)" value="<?php echo isset($settings['instagram_url']) && $settings['instagram_url'] !== '#' ? htmlspecialchars($settings['instagram_url']) : ''; ?>" data-platform="instagram">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                    <input type="url" class="form-control social-media-input" id="linkedin_url" name="linkedin_url" placeholder="LinkedIn URL (Opsiyonel)" value="<?php echo isset($settings['linkedin_url']) && $settings['linkedin_url'] !== '#' ? htmlspecialchars($settings['linkedin_url']) : ''; ?>" data-platform="linkedin">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                    <input type="url" class="form-control social-media-input" id="youtube_url" name="youtube_url" placeholder="YouTube URL (Opsiyonel)" value="<?php echo isset($settings['youtube_url']) && $settings['youtube_url'] !== '#' ? htmlspecialchars($settings['youtube_url']) : ''; ?>" data-platform="youtube">
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
                                Kullanılmayan platformlar için alanları boş bırakabilirsiniz. Boş bırakılan alanlar sitede gösterilmeyecektir.
                            </small>
                        </div>
                        
                        <!-- SEO Ayarları -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-search me-2"></i> SEO Ayarları</h5>
                            </div>
                            <div class="card-body">
                                <!-- Site Açıklaması -->
                                <div class="mb-3">
                                    <label for="site_description" class="form-label">Site Açıklaması <small class="text-muted">(Meta Description - En fazla 160 karakter)</small></label>
                                    <textarea class="form-control" id="site_description" name="site_description" rows="3" maxlength="160"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                                    <div class="form-text">Bu açıklama arama motorlarında ve sosyal medya paylaşımlarında görünecektir.</div>
                                </div>
                                
                                <!-- Anahtar Kelimeler -->
                                <div class="mb-3">
                                    <label for="site_keywords" class="form-label">Anahtar Kelimeler <small class="text-muted">(Meta Keywords - Virgülle ayrılmış)</small></label>
                                    <input type="text" class="form-control" id="site_keywords" name="site_keywords" value="<?php echo htmlspecialchars($settings['site_keywords'] ?? ''); ?>">
                                    <div class="form-text">Sitenizi en iyi tanımlayan anahtar kelimeleri virgülle ayırarak yazın (örn: kurumsal, inşaat, tadilat, hizmet).</div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> SEO ayarları sitenizin arama motorlarında daha iyi sıralama almasına yardımcı olur. Her sayfa için ayrıca özelleştirilmiş meta bilgileri otomatik olarak oluşturulacaktır.
                                </div>
                            </div>
                        </div>
                        <!-- /SEO Ayarları -->
                        
                        <!-- CSS Renk Şeması Ayarları -->
                        
                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" id="saveSettingsBtn">
                                <i class="fas fa-save me-1"></i> Ayarları Kaydet
                            </button>
                            <div class="spinner-border text-primary d-none" id="settingsSaveSpinner" role="status">
                                <span class="visually-hidden">Kaydediliyor...</span>
                            </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Renk şeması değişimini izle
    const colorSchemeSelect = document.getElementById('color_scheme');
    const saveButton = document.getElementById('saveSettingsBtn');
    const saveSpinner = document.getElementById('settingsSaveSpinner');
    
    // Renk şemasını değiştirme fonksiyonu
    function changeColorScheme(scheme) {
        // AJAX isteği ile renk şeması önizlemesini yükle
        fetch('update_preview_css.php?scheme=' + scheme + '&t=' + Date.now())
            .then(response => response.text())
            .then(css => {
                // Eski stil elementini kaldır (eğer varsa)
                const oldStyle = document.getElementById('preview-style');
                if (oldStyle) oldStyle.remove();
                
                // Yeni stil elementi oluştur
                const styleEl = document.createElement('style');
                styleEl.id = 'preview-style';
                styleEl.textContent = css;
                document.head.appendChild(styleEl);
                
                // Renk önizleme kutularını güncelle
                updateColorBoxes();
                
                // Önizleme başlık ve butonlarını güncelle
                updatePreviewElements();
                
                console.log('Renk şeması önizlemesi güncellendi: ' + scheme);
            })
            .catch(error => console.error('Renk şeması önizleme hatası:', error));
    }
    
    // Renkli kutuları güncelleme
    function updateColorBoxes() {
        const computedStyle = getComputedStyle(document.documentElement);
        
        // Renk kutularını güncelle
        document.getElementById('primaryColor').style.backgroundColor = computedStyle.getPropertyValue('--primary-color');
        document.getElementById('secondaryColor').style.backgroundColor = computedStyle.getPropertyValue('--secondary-color');
        document.getElementById('accentColor').style.backgroundColor = computedStyle.getPropertyValue('--accent-color');
    }
    
    // Önizleme elementlerini güncelleme
    function updatePreviewElements() {
        const computedStyle = getComputedStyle(document.documentElement);
        const primaryColor = computedStyle.getPropertyValue('--primary-color');
        
        // Başlık rengini güncelle
        document.querySelector('.preview-title').style.color = primaryColor;
        
        // Butonları güncelle (Bootstrap'in !important kurallarını geçersiz kılmak için inline style kullanıyoruz)
        const primaryBtn = document.querySelector('.btn-primary');
        primaryBtn.style.backgroundColor = primaryColor;
        primaryBtn.style.borderColor = primaryColor;
    }
    
    // Renk şeması değiştiğinde
    colorSchemeSelect.addEventListener('change', function() {
        changeColorScheme(this.value);
    });
    
    // Sayfa yüklendiğinde önizlemeyi başlat
    updateColorBoxes();
    
    // Form gönderildiğinde
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        // Yükleniyor göstergesini göster
        saveButton.classList.add('d-none');
        saveSpinner.classList.remove('d-none');
        
        // Seçilen renk şemasını hidden input olarak ekle
        const colorSchemeValue = colorSchemeSelect.value;
        
        // Hidden input varsa güncelle, yoksa oluştur
        let hiddenInput = document.getElementById('color_scheme_confirmed');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'color_scheme_confirmed';
            hiddenInput.id = 'color_scheme_confirmed';
            this.appendChild(hiddenInput);
        }
        hiddenInput.value = colorSchemeValue;
        
        // Form normal şekilde gönderilecek
        return true;
    });
    
    // Sayfa yüklendiğinde önbellek kontrolü
    if (window.location.search.includes('updated=1')) {
        // Sayfayı yeniden yükle ihtiyacı kaldırıldı
        console.log('Ayarlar güncellendi.');
    }
});
</script>

<?php
// Footer'ı dahil et
include 'includes/footer.php';
?> 