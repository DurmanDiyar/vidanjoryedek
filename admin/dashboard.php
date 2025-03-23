<?php
/**
 * Admin Panel - Dashboard
 * 
 * Bu sayfa, admin panelinin ana sayfasıdır ve genel istatistikleri gösterir.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
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

// İstatistikleri getir
try {
    // Hizmet sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM services");
    $serviceCount = $stmt->fetchColumn();
    
    // Slider sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM slider");
    $sliderCount = $stmt->fetchColumn();
    
    // Galeri öğe sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM gallery");
    $galleryCount = $stmt->fetchColumn();
    
    // Referans sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM referencess");
    $referenceCount = $stmt->fetchColumn();
    
    // Toplam mesaj sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM contact_messages");
    $messageCount = $stmt->fetchColumn();
    
    // Okunmamış mesaj sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
    $unreadMessages = $stmt->fetchColumn();
    
    // Kullanıcı sayısı
    $stmt = $db->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    
    // Son 5 mesaj
    $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    $recentMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Son eklenen hizmetler
    $stmt = $db->query("SELECT * FROM services ORDER BY created_at DESC LIMIT 5");
    $recentServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = 'Veritabanı hatası: ' . $e->getMessage();
}

// Sayfa başlığı
$pageTitle = 'Dashboard';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="../index.php" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-external-link-alt fa-sm text-white-50"></i> Siteyi Görüntüle
        </a>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <!-- İstatistik Kartları -->
    <div class="row">
        <!-- Hizmet Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Hizmetler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $serviceCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="services.php" class="small text-primary">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Galeri Öğe Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Galeri</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $galleryCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-images fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="gallery.php" class="small text-success">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Referans Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Referanslar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $referenceCount; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="references.php" class="small text-info">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Mesaj Sayısı -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Mesajlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $messageCount; ?>
                                <?php if ($unreadMessages > 0): ?>
                                    <span class="badge bg-danger"><?php echo $unreadMessages; ?> yeni</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="messages.php" class="small text-warning">Detaylar <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Son Mesajlar -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Son Mesajlar</h6>
                    <a href="messages.php" class="btn btn-sm btn-primary">
                        Tümünü Gör
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentMessages)): ?>
                        <p class="text-center">Henüz mesaj bulunmuyor.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentMessages as $message): ?>
                                <a href="messages.php?view=<?php echo $message['id']; ?>" class="list-group-item list-group-item-action <?php echo $message['is_read'] ? '' : 'fw-bold bg-light'; ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($message['name']); ?></h6>
                                        <small><?php echo date('d.m.Y H:i', strtotime($message['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo htmlspecialchars(mb_substr($message['message'], 0, 100)) . (mb_strlen($message['message']) > 100 ? '...' : ''); ?></p>
                                    <?php if (!$message['is_read']): ?>
                                        <span class="badge bg-danger float-end">Yeni</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Son Eklenen Hizmetler -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Son Eklenen Hizmetler</h6>
                    <a href="services.php" class="btn btn-sm btn-primary">
                        Tümünü Gör
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentServices)): ?>
                        <p class="text-center">Henüz hizmet bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Hizmet Adı</th>
                                        <th>Fiyat</th>
                                        <th>Eklenme Tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentServices as $service): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td>
                                                <?php if (!empty($service['price'])): ?>
                                                    <?php echo number_format($service['price'], 2, ',', '.'); ?> TL
                                                <?php else: ?>
                                                    <span class="text-muted">Belirtilmemiş</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d.m.Y', strtotime($service['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hızlı Erişim Butonları -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hızlı Erişim</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="services.php" class="btn btn-primary btn-block">
                                <i class="fas fa-cogs"></i> Hizmet Ekle
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="slider.php" class="btn btn-success btn-block">
                                <i class="fas fa-sliders-h"></i> Slider Ekle
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="gallery.php" class="btn btn-info btn-block">
                                <i class="fas fa-images"></i> Galeri Ekle
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="references.php" class="btn btn-warning btn-block">
                                <i class="fas fa-handshake"></i> Referans Ekle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Footer'ı dahil et
include 'includes/footer.php';
?> 