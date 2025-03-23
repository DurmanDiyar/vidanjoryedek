<?php
/**
 * İletişim Sayfası
 * 
 * Bu sayfa iletişim formu ve şirket iletişim bilgilerini içerir.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
require_once '../config.php';

// Sayfa başlığını ayarla
$pageTitle = 'İletişim';

// Veritabanı bağlantısını al
$db = getDbConnection();

// Form mesajını saklamak için değişkenler
$message = '';
$messageType = '';
$formValues = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'message' => '',
    'service' => ''
];
$urgent = false; // $urgent değişkenini varsayılan olarak false şeklinde tanımla

// Form gönderildi mi kontrol et ve işle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $formValues['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
    $formValues['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
    $formValues['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $formValues['message'] = isset($_POST['message']) ? trim($_POST['message']) : '';
    $formValues['service'] = isset($_POST['service']) ? trim($_POST['service']) : '';
    $urgent = isset($_POST['urgent']) ? 1 : 0;
    $errors = [];
    
    // Form doğrulama
    if (empty($formValues['name'])) {
        $errors[] = 'Ad Soyad alanı gereklidir';
    }
    
    if (empty($formValues['email'])) {
        $errors[] = 'E-posta alanı gereklidir';
    } elseif (!filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Geçerli bir e-posta adresi giriniz';
    }
    
    if (empty($formValues['phone'])) {
        $errors[] = 'Telefon alanı gereklidir';
    }
    
    if (empty($formValues['message'])) {
        $errors[] = 'Mesaj alanı gereklidir';
    }
    
    // Hata yoksa mesajı kaydet
    if (empty($errors)) {
        try {
            // Mesaj içeriğine hizmet türü ekle
            $messageContent = $formValues['message'];
            if (!empty($formValues['service'])) {
                $messageContent = "Hizmet Türü: " . $formValues['service'] . "\n\n" . $messageContent;
            }
            if ($urgent) {
                $messageContent = "[ACİL TALEP]\n" . $messageContent;
            }
            
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([
                $formValues['name'], 
                $formValues['email'], 
                $formValues['phone'], 
                $messageContent
            ]);
            
            if ($result) {
                $message = 'Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.';
                $messageType = 'success';
                
                // Form değerlerini sıfırla
                $formValues = [
                    'name' => '',
                    'email' => '',
                    'phone' => '',
                    'message' => '',
                    'service' => ''
                ];
                
                // POST'tan sonra yeniden yükleme sorununu önlemek için yönlendirme yap
                header("Location: iletisim.php?success=1");
                exit;
                
            } else {
                $message = 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.';
                $messageType = 'danger';
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . $e->getMessage();
            $messageType = 'danger';
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'danger';
    }
}

// URL parametresinden başarı mesajını kontrol et
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = 'Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.';
    $messageType = 'success';
}

// Site ayarlarını veritabanından çek
try {
    $stmt = $db->query("SELECT * FROM site_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Veritabanı hatası durumunda varsayılan ayarlar
    $settings = [
        'site_title' => 'Kurumsal Web Sitesi',
        'contact_phone' => '+90 555 123 4567',
        'contact_email' => 'info@example.com',
        'contact_address' => 'İstanbul, Türkiye'
    ];
}

// Include the header
include '../includes/header.php';
?>

<!-- Sayfa Başlığı -->
<div class="page-header py-5 mb-5 ">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4">İletişim</h1>
                <p class="lead">Vidanjör ve Kanal Açma Hizmetlerimiz 7/24 Hizmetinizdedir</p>
            </div>
        </div>
    </div>
</div>

<!-- İletişim Bölümü -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <!-- İletişim Bilgileri - Mobil görünümde önce gelecek -->
            <div class="col-lg-6 order-1 order-lg-2 mb-5 mb-lg-0">
                <h2 class="mb-4">İletişim Bilgilerimiz</h2>
                <div class="contact-info mb-4">
                    <div class="d-flex mb-3">
                        <div class="contact-icon me-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5>Adres</h5>
                            <p><?php echo htmlspecialchars($settings['contact_address']); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="contact-icon me-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h5>7/24 Acil Vidanjör Hattı</h5>
                            <p class="mb-1"><a href="tel:<?php echo preg_replace('/\s+/', '', $settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone']); ?></a></p>
                            <?php if (!empty($settings['whatsapp_phone'])): ?>
                            <p><a href="tel:<?php echo preg_replace('/\s+/', '', $settings['whatsapp_phone']); ?>"><?php echo htmlspecialchars($settings['whatsapp_phone']); ?></a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="contact-icon me-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5>E-posta</h5>
                            <p><a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email']); ?></a></p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="contact-icon me-3">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h5>Hizmet Bölgelerimiz</h5>
                            <p>İstanbul'un tüm ilçelerine hizmet vermekteyiz.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Çalışma Saatleri -->
                <h3 class="mb-3">Çalışma Saatleri</h3>
                <div class="bg-light p-3 rounded mb-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Acil Hizmet:</strong> <span class="text-danger">7/24 Kesintisiz Hizmet</span></li>
                        <li class="mb-2"><strong>Ofis Saatleri:</strong></li>
                        <li class="mb-2 ms-3">Pazartesi - Cumartesi: 08:00 - 20:00</li>
                        <li class="ms-3">Pazar: 10:00 - 18:00</li>
                    </ul>
                </div>
                
                <!-- Hizmet Alanları -->
                <h3 class="mb-3">Hizmet Alanlarımız</h3>
                <div class="service-tags mb-4">
                    <span class="badge bg-primary me-2 mb-2">Tıkanıklık Açma</span>
                    <span class="badge bg-primary me-2 mb-2">Kanalizasyon Temizleme</span>
                    <span class="badge bg-primary me-2 mb-2">Logar Temizliği</span>
                    <span class="badge bg-primary me-2 mb-2">Fosseptik Çukuru Temizleme</span>
                    <span class="badge bg-primary me-2 mb-2">Gider Açma</span>
                    <span class="badge bg-primary me-2 mb-2">Rögar Temizliği</span>
                    <span class="badge bg-primary me-2 mb-2">Acil Vidanjör Hizmeti</span>
                </div>
                
                <!-- Sosyal Medya -->
                <h3 class="mb-3">Sosyal Medya</h3>
                <div class="social-media mb-4">
                    <?php if (!empty($settings['facebook_url']) && $settings['facebook_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank" class="btn btn-outline-primary me-2 mb-2"><i class="fab fa-facebook-f me-2"></i>Facebook</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['instagram_url']) && $settings['instagram_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" class="btn btn-outline-primary me-2 mb-2"><i class="fab fa-instagram me-2"></i>Instagram</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['youtube_url']) && $settings['youtube_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank" class="btn btn-outline-primary me-2 mb-2"><i class="fab fa-youtube me-2"></i>YouTube</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['twitter_url']) && $settings['twitter_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank" class="btn btn-outline-primary me-2 mb-2"><i class="fab fa-twitter me-2"></i>Twitter</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['linkedin_url']) && $settings['linkedin_url'] != '#'): ?>
                    <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank" class="btn btn-outline-primary me-2 mb-2"><i class="fab fa-linkedin me-2"></i>LinkedIn</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- İletişim Formu - Mobil görünümde sonra gelecek -->
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="contact-form-wrapper bg-light p-4 rounded">
                    <h2 class="mb-4">Hızlı Teklif / Acil Hizmet Talebi</h2>
                    
                    <?php
                    // Form gönderildi mi kontrol et
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
                        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
                        $message = isset($_POST['message']) ? trim($_POST['message']) : '';
                        $errors = [];
                        
                        // Form doğrulama
                        if (empty($name)) {
                            $errors[] = 'Ad Soyad alanı gereklidir';
                        }
                        
                        if (empty($email)) {
                            $errors[] = 'E-posta alanı gereklidir';
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = 'Geçerli bir e-posta adresi giriniz';
                        }
                        
                        if (empty($message)) {
                            $errors[] = 'Mesaj alanı gereklidir';
                        }
                        
                        // Hata yoksa mesajı kaydet
                        if (empty($errors)) {
                            try {
                                $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
                                $result = $stmt->execute([$name, $email, $phone, $message]);
                                
                                if ($result) {
                                    echo '<div class="alert alert-success">Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.</div>';
                                } else {
                                    echo '<div class="alert alert-danger">Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.</div>';
                                }
                            } catch (PDOException $e) {
                                echo '<div class="alert alert-danger">Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger"><ul>';
                            foreach ($errors as $error) {
                                echo '<li>' . htmlspecialchars($error) . '</li>';
                            }
                            echo '</ul></div>';
                        }
                    }
                    
                    // URL parametresinden başarı mesajını kontrol et
                    if (isset($_GET['success']) && $_GET['success'] == 1) {
                        echo '<div class="alert alert-success">Mesajınız başarıyla gönderildi. En kısa sürede sizinle iletişime geçeceğiz.</div>';
                    }
                    ?>
                    
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo $formValues['name']; ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo $formValues['email']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" required value="<?php echo $formValues['phone']; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="service" class="form-label">Hizmet Türü</label>
                            <select class="form-select" id="service" name="service">
                                <option value="">Seçiniz</option>
                                <option value="Tıkanıklık Açma" <?php echo $formValues['service'] == 'Tıkanıklık Açma' ? 'selected' : ''; ?>>Tıkanıklık Açma</option>
                                <option value="Kanalizasyon Temizleme" <?php echo $formValues['service'] == 'Kanalizasyon Temizleme' ? 'selected' : ''; ?>>Kanalizasyon Temizleme</option>
                                <option value="Logar Temizliği" <?php echo $formValues['service'] == 'Logar Temizliği' ? 'selected' : ''; ?>>Logar Temizliği</option>
                                <option value="Fosseptik Çukuru Temizleme" <?php echo $formValues['service'] == 'Fosseptik Çukuru Temizleme' ? 'selected' : ''; ?>>Fosseptik Çukuru Temizleme</option>
                                <option value="Gider Açma" <?php echo $formValues['service'] == 'Gider Açma' ? 'selected' : ''; ?>>Gider Açma</option>
                                <option value="Rögar Temizliği" <?php echo $formValues['service'] == 'Rögar Temizliği' ? 'selected' : ''; ?>>Rögar Temizliği</option>
                                <option value="Acil Vidanjör Hizmeti" <?php echo $formValues['service'] == 'Acil Vidanjör Hizmeti' ? 'selected' : ''; ?>>Acil Vidanjör Hizmeti</option>
                                <option value="Diğer" <?php echo $formValues['service'] == 'Diğer' ? 'selected' : ''; ?>>Diğer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mesajınız <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Lütfen sorununuzu veya hizmet talebinizi detaylı bir şekilde açıklayın."><?php echo $formValues['message']; ?></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="urgentCheck" name="urgent" <?php echo isset($urgent) && $urgent ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="urgentCheck">Acil durum! En kısa sürede ulaşılsın.</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Harita -->
<section class="map-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Bize Ulaşın</h2>
                <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6219.549224618244!2d26.981132!3d38.791801!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14ba30262f8eaacb%3A0x3b14a798f66a3a03!2zWWVuaSwgNjUxLiBTay4sIDM1ODAwIEFsaWHEn2EvxLB6bWly!5e0!3m2!1str!2str!4v1742762077530!5m2!1str!2str" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Acil Durumlar Bilgi Bölümü -->
<section class="emergency-info py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="mb-4">Acil Tıkanıklık Sorunları İçin</h2>
                <p class="lead mb-4">Vidanjör ekibimiz 7/24 hizmetinizdedir. Acil durumlarda hemen bize ulaşın.</p>
                <div class="d-grid gap-2 d-md-flex">
                    <a href="tel:<?php echo preg_replace('/\s+/', '', $settings['contact_phone']); ?>" class="btn btn-danger btn-lg"><i class="fas fa-phone-alt me-2"></i>Hemen Ara</a>
                    <a href="<?php echo SITE_URL; ?>/pages/hizmetler.php" class="btn btn-outline-primary btn-lg">Hizmetlerimiz</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3">Hizmet Standartlarımız</h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Acil çağrılarda 30 dakika içinde yanıt</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Modern ve güçlü vidanjör araçları</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Profesyonel ve deneyimli ekip</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Şeffaf fiyatlandırma politikası</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3"></i>
                                <span>Çevre dostu atık bertarafı</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include the footer
include '../includes/footer.php';
?>