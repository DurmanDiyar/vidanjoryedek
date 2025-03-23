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

// Form gönderildi mi kontrol et
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $messageContent = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Basit doğrulama
    if (empty($name) || empty($email) || empty($messageContent)) {
        $message = 'Lütfen zorunlu alanları doldurun.';
        $messageType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Lütfen geçerli bir e-posta adresi girin.';
        $messageType = 'danger';
    } else {
        // Veritabanına kaydet
        try {
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$name, $email, $phone, $messageContent]);
            
            if ($result) {
                $message = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.';
                $messageType = 'success';
                
                // Form alanlarını temizle
                $name = $email = $phone = $subject = $messageContent = '';
            } else {
                $message = 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
                $messageType = 'danger';
            }
        } catch (PDOException $e) {
            $message = 'Mesajınız gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
            $messageType = 'danger';
        }
    }
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
<div class="page-header py-5 mb-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4">İletişim</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo baseUrl(); ?>">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">İletişim</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- İletişim Bölümü -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <!-- İletişim Formu -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h2 class="mb-4">Bize Ulaşın</h2>
                
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
                ?>
                
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Ad Soyad</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mesajınız</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gönder</button>
                </form>
            </div>
            
            <!-- İletişim Bilgileri -->
            <div class="col-lg-6">
                <h2 class="mb-4">İletişim Bilgilerimiz</h2>
                <div class="contact-info mb-4">
                    <?php if (!empty($settings['contact_address'])): ?>
                        <div class="d-flex mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h5>Adres</h5>
                                <p><?php echo htmlspecialchars($settings['contact_address']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_phone'])): ?>
                        <div class="d-flex mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h5>Telefon</h5>
                                <p><a href="tel:<?php echo htmlspecialchars($settings['contact_phone']); ?>"><?php echo htmlspecialchars($settings['contact_phone']); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['contact_email'])): ?>
                        <div class="d-flex mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h5>E-posta</h5>
                                <p><a href="mailto:<?php echo htmlspecialchars($settings['contact_email']); ?>"><?php echo htmlspecialchars($settings['contact_email']); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Çalışma Saatleri -->
                <h3 class="mb-3">Çalışma Saatleri</h3>
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>Pazartesi - Cuma:</strong> 09:00 - 18:00</li>
                    <li class="mb-2"><strong>Cumartesi:</strong> 09:00 - 13:00</li>
                    <li><strong>Pazar:</strong> Kapalı</li>
                </ul>
                
                <!-- Sosyal Medya -->
                <h3 class="mb-3">Sosyal Medya</h3>
                <div class="social-media mb-4">
                    <?php if (!empty($settings['facebook_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank" class="me-2"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['twitter_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank" class="me-2"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['instagram_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" class="me-2"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['linkedin_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['youtube_url'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                    <?php endif; ?>
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
                <h2 class="text-center mb-4">Konum</h2>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12037.087739742902!2d28.97706277644958!3d41.034471233407515!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab7650656bd63%3A0x8ca058b28c20b6c3!2zVGFrc2ltIE1leWRhbsSxLCBHw7xtw7zFn3N1eXUsIDM0NDM1IEJleW_En2x1L8Swc3RhbmJ1bA!5e0!3m2!1str!2str!4v1684764333520!5m2!1str!2str" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include the footer
include '../includes/footer.php';
?>