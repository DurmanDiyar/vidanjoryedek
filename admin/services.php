<?php
/**
 * Admin Panel - Hizmet Yönetimi
 * 
 * Bu sayfa, hizmetlerin listelenmesi, eklenmesi, düzenlenmesi ve silinmesi işlemlerini yönetir.
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

// Hizmet silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $serviceId = $_GET['delete'];
    
    try {
        // Hizmeti sil
        $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
        $result = $stmt->execute([$serviceId]);
        
        if ($result) {
            $message = 'Hizmet başarıyla silindi.';
            $messageType = 'success';
            
            // Başarılı silme işleminden sonra yönlendirme yap
            header("Location: services.php?deleted=1");
            exit;
        } else {
            $message = 'Hizmet silinirken bir hata oluştu.';
            $messageType = 'danger';
        }
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $serviceId = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $icon = isset($_POST['icon']) ? trim($_POST['icon']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    
    // Verileri doğrula
    if (empty($name)) {
        $message = 'Hizmet adı boş olamaz.';
        $messageType = 'danger';
    } else {
        try {
            // Yeni hizmet ekleme veya mevcut hizmeti güncelleme
            if ($serviceId > 0) {
                // Güncelleme
                $stmt = $db->prepare("UPDATE services SET name = ?, description = ?, icon = ?, price = ? WHERE id = ?");
                $result = $stmt->execute([$name, $description, $icon, $price, $serviceId]);
                
                if ($result) {
                    $message = 'Hizmet başarıyla güncellendi.';
                    $messageType = 'success';
                    
                    // Başarılı güncellemeden sonra yönlendirme yap
                    header("Location: services.php?updated=1");
                    exit;
                } else {
                    $message = 'Hizmet güncellenirken bir hata oluştu.';
                    $messageType = 'danger';
                }
            } else {
                // Yeni ekleme
                $stmt = $db->prepare("INSERT INTO services (name, description, icon, price) VALUES (?, ?, ?, ?)");
                $result = $stmt->execute([$name, $description, $icon, $price]);
                
                if ($result) {
                    $message = 'Hizmet başarıyla eklendi.';
                    $messageType = 'success';
                    
                    // Başarılı eklemeden sonra yönlendirme yap
                    header("Location: services.php?success=1");
                    exit;
                } else {
                    $message = 'Hizmet eklenirken bir hata oluştu.';
                    $messageType = 'danger';
                }
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Düzenlenecek hizmet
$editService = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $serviceId = $_GET['edit'];
    
    try {
        $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$serviceId]);
        $editService = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tüm hizmetleri listele
try {
    $stmt = $db->query("SELECT * FROM services ORDER BY id DESC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// URL parametrelerinden mesaj durumunu kontrol et
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = 'Hizmet başarıyla eklendi.';
    $messageType = 'success';
} elseif (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $message = 'Hizmet başarıyla güncellendi.';
    $messageType = 'success';
} elseif (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $message = 'Hizmet başarıyla silindi.';
    $messageType = 'success';
}

// Sayfa başlığı
$pageTitle = 'Hizmet Yönetimi';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hizmet Yönetimi</h1>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Hizmet Ekleme/Düzenleme Formu -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?php echo $editService ? 'Hizmet Düzenle' : 'Yeni Hizmet Ekle'; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <?php if ($editService): ?>
                            <input type="hidden" name="service_id" value="<?php echo $editService['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Hizmet Adı</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $editService ? htmlspecialchars($editService['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo $editService ? htmlspecialchars($editService['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="icon" class="form-label">İkon (Font Awesome)</label>
                            <input type="text" class="form-control" id="icon" name="icon" value="<?php echo $editService ? htmlspecialchars($editService['icon']) : 'fas fa-cog'; ?>" placeholder="fas fa-cog">
                            <small class="form-text text-muted">Örnek: fas fa-cog, fas fa-tools, fas fa-wrench</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Fiyat (Opsiyonel)</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $editService ? htmlspecialchars($editService['price']) : '0.00'; ?>">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editService ? 'Güncelle' : 'Ekle'; ?>
                            </button>
                            <?php if ($editService): ?>
                                <a href="services.php" class="btn btn-secondary">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- İkon Örnekleri -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">İkon Örnekleri</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-cog fa-2x"></i>
                            <p class="small mt-2">fas fa-cog</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-tools fa-2x"></i>
                            <p class="small mt-2">fas fa-tools</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-wrench fa-2x"></i>
                            <p class="small mt-2">fas fa-wrench</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-hammer fa-2x"></i>
                            <p class="small mt-2">fas fa-hammer</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-screwdriver fa-2x"></i>
                            <p class="small mt-2">fas fa-screwdriver</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-truck fa-2x"></i>
                            <p class="small mt-2">fas fa-truck</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-home fa-2x"></i>
                            <p class="small mt-2">fas fa-home</p>
                        </div>
                        <div class="col-md-3 col-sm-4 mb-3">
                            <i class="fas fa-building fa-2x"></i>
                            <p class="small mt-2">fas fa-building</p>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="https://fontawesome.com/icons?d=gallery&s=solid&m=free" target="_blank" class="btn btn-sm btn-outline-primary">
                            Daha Fazla İkon Gör
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hizmet Listesi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hizmetler</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($services)): ?>
                        <p class="text-center">Henüz hizmet bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th width="60">İkon</th>
                                        <th>Hizmet Adı</th>
                                        <th>Açıklama</th>
                                        <th width="100">Fiyat</th>
                                        <th width="120">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td><?php echo $service['id']; ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($service['icon'])): ?>
                                                <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                                            <?php else: ?>
                                                <i class="fas fa-cog"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($service['name']); ?></td>
                                        <td><?php echo mb_substr(htmlspecialchars($service['description']), 0, 100) . (mb_strlen($service['description']) > 100 ? '...' : ''); ?></td>
                                        <td><?php echo number_format($service['price'], 2, ',', '.') . ' ₺'; ?></td>
                                        <td>
                                            <a href="services.php?edit=<?php echo $service['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="services.php?delete=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
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
</div>

<?php
// Footer'ı dahil et
include 'includes/footer.php';
?> 