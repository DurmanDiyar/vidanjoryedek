<?php
/**
 * Admin Panel - İletişim Mesajları Yönetimi
 * 
 * Bu sayfa, iletişim formundan gelen mesajların listelenmesi, görüntülenmesi ve silinmesi işlemlerini yönetir.
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

// Mesaj değişkenleri
$message = '';
$messageType = '';

// Mesaj silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $messageId = $_GET['delete'];
    
    try {
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
        $result = $stmt->execute([$messageId]);
        
        if ($result) {
            $message = 'Mesaj başarıyla silindi.';
            $messageType = 'success';
        } else {
            $message = 'Mesaj silinirken bir hata oluştu.';
            $messageType = 'danger';
        }
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Mesajı okundu olarak işaretleme
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $messageId = $_GET['mark_read'];
    
    try {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        $result = $stmt->execute([$messageId]);
        
        if ($result) {
            $message = 'Mesaj okundu olarak işaretlendi.';
            $messageType = 'success';
        } else {
            $message = 'Mesaj işaretlenirken bir hata oluştu.';
            $messageType = 'danger';
        }
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Mesajı okunmadı olarak işaretleme
if (isset($_GET['mark_unread']) && is_numeric($_GET['mark_unread'])) {
    $messageId = $_GET['mark_unread'];
    
    try {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 0 WHERE id = ?");
        $result = $stmt->execute([$messageId]);
        
        if ($result) {
            $message = 'Mesaj okunmadı olarak işaretlendi.';
            $messageType = 'success';
        } else {
            $message = 'Mesaj işaretlenirken bir hata oluştu.';
            $messageType = 'danger';
        }
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Görüntülenecek mesaj
$viewMessage = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $messageId = $_GET['view'];
    
    try {
        // Mesajı getir
        $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$messageId]);
        $viewMessage = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Mesajı okundu olarak işaretle
        if ($viewMessage && $viewMessage['is_read'] == 0) {
            $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
            $stmt->execute([$messageId]);
        }
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tüm mesajları listele
try {
    $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $contactMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Okunmamış mesaj sayısını hesapla
    $stmt = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0");
    $unreadCount = $stmt->fetchColumn();
} catch (PDOException $e) {
    $contactMessages = [];
    $unreadCount = 0;
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// Sayfa başlığı
$pageTitle = 'İletişim Mesajları';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">İletişim Mesajları</h1>
        <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger"><?php echo $unreadCount; ?> okunmamış mesaj</span>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Mesaj Listesi -->
        <div class="col-xl-5 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mesajlar</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($contactMessages)): ?>
                        <p class="text-center py-3">Henüz mesaj bulunmuyor.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($contactMessages as $contactMsg): ?>
                                <a href="messages.php?view=<?php echo $contactMsg['id']; ?>" class="list-group-item list-group-item-action <?php echo $contactMsg['is_read'] ? '' : 'fw-bold bg-light'; ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?php echo htmlspecialchars($contactMsg['name']); ?></h5>
                                        <small><?php echo date('d.m.Y H:i', strtotime($contactMsg['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo htmlspecialchars(mb_substr($contactMsg['message'], 0, 100)) . (mb_strlen($contactMsg['message']) > 100 ? '...' : ''); ?></p>
                                    <small><?php echo htmlspecialchars($contactMsg['email']); ?></small>
                                    <?php if (!$contactMsg['is_read']): ?>
                                        <span class="badge bg-danger float-end">Yeni</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Mesaj Detayı -->
        <div class="col-xl-7 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?php echo $viewMessage ? 'Mesaj Detayı' : 'Mesaj Seçin'; ?>
                    </h6>
                    <?php if ($viewMessage): ?>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                İşlemler
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php if ($viewMessage['is_read']): ?>
                                    <li><a class="dropdown-item" href="messages.php?mark_unread=<?php echo $viewMessage['id']; ?>">Okunmadı Olarak İşaretle</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="messages.php?mark_read=<?php echo $viewMessage['id']; ?>">Okundu Olarak İşaretle</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="messages.php?delete=<?php echo $viewMessage['id']; ?>" onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">Sil</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($viewMessage): ?>
                        <div class="message-details">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5>Gönderen</h5>
                                    <p><?php echo htmlspecialchars($viewMessage['name']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Tarih</h5>
                                    <p><?php echo date('d.m.Y H:i', strtotime($viewMessage['created_at'])); ?></p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5>E-posta</h5>
                                    <p><a href="mailto:<?php echo htmlspecialchars($viewMessage['email']); ?>"><?php echo htmlspecialchars($viewMessage['email']); ?></a></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Telefon</h5>
                                    <p>
                                        <?php if (!empty($viewMessage['phone'])): ?>
                                            <a href="tel:<?php echo htmlspecialchars($viewMessage['phone']); ?>"><?php echo htmlspecialchars($viewMessage['phone']); ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">Belirtilmemiş</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h5>Mesaj</h5>
                                <div class="p-3 bg-light rounded">
                                    <?php echo nl2br(htmlspecialchars($viewMessage['message'])); ?>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="mailto:<?php echo htmlspecialchars($viewMessage['email']); ?>" class="btn btn-primary">
                                    <i class="fas fa-reply"></i> Yanıtla
                                </a>
                                <a href="messages.php?delete=<?php echo $viewMessage['id']; ?>" class="btn btn-danger" onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
                                    <i class="fas fa-trash"></i> Sil
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-center">Detaylarını görmek için bir mesaj seçin.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Footer'ı dahil et
include 'includes/footer.php';
?> 