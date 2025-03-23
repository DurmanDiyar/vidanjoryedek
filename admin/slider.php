<?php
/**
 * Admin Panel - Slider Yönetimi
 * 
 * Bu sayfa, slider içeriklerinin listelenmesi, eklenmesi, düzenlenmesi ve silinmesi işlemlerini yönetir.
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

// Uploads klasörü kontrolü
$uploadsDir = '../uploads/slider/';
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Slider silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $sliderId = $_GET['delete'];
    
    try {
        // Önce resim dosyasını bul
        $stmt = $db->prepare("SELECT image_path FROM slider WHERE id = ?");
        $stmt->execute([$sliderId]);
        $slider = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Slider'ı sil
        $stmt = $db->prepare("DELETE FROM slider WHERE id = ?");
        $result = $stmt->execute([$sliderId]);
        
        if ($result) {
            // Resim dosyasını sil (eğer varsa)
            if ($slider && !empty($slider['image_path']) && file_exists('../' . $slider['image_path'])) {
                unlink('../' . $slider['image_path']);
            }
            
            $message = 'Slider başarıyla silindi.';
            $messageType = 'success';
        } else {
            $message = 'Slider silinirken bir hata oluştu.';
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
    $sliderId = isset($_POST['slider_id']) ? intval($_POST['slider_id']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $displayOrder = isset($_POST['display_order']) ? intval($_POST['display_order']) : 0;
    
    // Resim yükleme işlemi
    $imagePath = '';
    $uploadError = '';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $uploadError = 'Sadece JPEG, PNG ve GIF formatları desteklenmektedir.';
        } elseif ($_FILES['image']['size'] > $maxFileSize) {
            $uploadError = 'Dosya boyutu 5MB\'dan büyük olamaz.';
        } else {
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $uploadsDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = 'uploads/slider/' . $fileName;
            } else {
                $uploadError = 'Dosya yüklenirken bir hata oluştu.';
            }
        }
    }
    
    // Hata kontrolü
    if (!empty($uploadError)) {
        $message = 'Resim yükleme hatası: ' . $uploadError;
        $messageType = 'danger';
    } else {
        try {
            // Yeni slider ekleme veya mevcut slider'ı güncelleme
            if ($sliderId > 0) {
                // Güncelleme
                if (!empty($imagePath)) {
                    // Eski resmi bul ve sil
                    $stmt = $db->prepare("SELECT image_path FROM slider WHERE id = ?");
                    $stmt->execute([$sliderId]);
                    $oldSlider = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($oldSlider && !empty($oldSlider['image_path']) && file_exists('../' . $oldSlider['image_path'])) {
                        unlink('../' . $oldSlider['image_path']);
                    }
                    
                    // Resimle birlikte güncelleme
                    $stmt = $db->prepare("UPDATE slider SET title = ?, description = ?, image_path = ?, display_order = ? WHERE id = ?");
                    $result = $stmt->execute([$title, $description, $imagePath, $displayOrder, $sliderId]);
                } else {
                    // Resim olmadan güncelleme
                    $stmt = $db->prepare("UPDATE slider SET title = ?, description = ?, display_order = ? WHERE id = ?");
                    $result = $stmt->execute([$title, $description, $displayOrder, $sliderId]);
                }
                
                if ($result) {
                    $message = 'Slider başarıyla güncellendi.';
                    $messageType = 'success';
                } else {
                    $message = 'Slider güncellenirken bir hata oluştu.';
                    $messageType = 'danger';
                }
            } else {
                // Yeni ekleme (resim zorunlu)
                if (empty($imagePath)) {
                    $message = 'Lütfen bir resim seçin.';
                    $messageType = 'danger';
                } else {
                    $stmt = $db->prepare("INSERT INTO slider (title, description, image_path, display_order) VALUES (?, ?, ?, ?)");
                    $result = $stmt->execute([$title, $description, $imagePath, $displayOrder]);
                    
                    if ($result) {
                        $message = 'Slider başarıyla eklendi.';
                        $messageType = 'success';
                    } else {
                        $message = 'Slider eklenirken bir hata oluştu.';
                        $messageType = 'danger';
                    }
                }
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Düzenlenecek slider
$editSlider = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $sliderId = $_GET['edit'];
    
    try {
        $stmt = $db->prepare("SELECT * FROM slider WHERE id = ?");
        $stmt->execute([$sliderId]);
        $editSlider = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tüm slider'ları listele
try {
    $stmt = $db->query("SELECT * FROM slider ORDER BY display_order ASC, id DESC");
    $sliders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $sliders = [];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// Sayfa başlığı
$pageTitle = 'Slider Yönetimi';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Slider Yönetimi</h1>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Slider Ekleme/Düzenleme Formu -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?php echo $editSlider ? 'Slider Düzenle' : 'Yeni Slider Ekle'; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                        <?php if ($editSlider): ?>
                            <input type="hidden" name="slider_id" value="<?php echo $editSlider['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Başlık</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $editSlider ? htmlspecialchars($editSlider['title']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo $editSlider ? htmlspecialchars($editSlider['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Resim</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" <?php echo $editSlider ? '' : 'required'; ?>>
                            <small class="form-text text-muted">Önerilen boyut: 1920x800 piksel. Maksimum dosya boyutu: 5MB</small>
                            
                            <?php if ($editSlider && !empty($editSlider['image_path'])): ?>
                                <div class="mt-2">
                                    <p>Mevcut Resim:</p>
                                    <img src="../<?php echo htmlspecialchars($editSlider['image_path']); ?>" class="img-thumbnail" style="max-height: 150px;">
                                    <p class="small text-muted mt-1">Yeni bir resim yüklerseniz, mevcut resim değiştirilecektir.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="display_order" class="form-label">Görüntüleme Sırası</label>
                            <input type="number" class="form-control" id="display_order" name="display_order" min="0" value="<?php echo $editSlider ? htmlspecialchars($editSlider['display_order']) : '0'; ?>">
                            <small class="form-text text-muted">Küçük sayılar önce gösterilir. Aynı sıra numarasına sahip öğeler için en son eklenen önce gösterilir.</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editSlider ? 'Güncelle' : 'Ekle'; ?>
                            </button>
                            <?php if ($editSlider): ?>
                                <a href="slider.php" class="btn btn-secondary">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Slider Listesi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sliderlar</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($sliders)): ?>
                        <p class="text-center">Henüz slider bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th width="80">Resim</th>
                                        <th>Başlık</th>
                                        <th>Açıklama</th>
                                        <th width="80">Sıra</th>
                                        <th width="120">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sliders as $slider): ?>
                                    <tr>
                                        <td><?php echo $slider['id']; ?></td>
                                        <td>
                                            <?php if (!empty($slider['image_path'])): ?>
                                                <img src="../<?php echo htmlspecialchars($slider['image_path']); ?>" class="img-thumbnail" style="max-height: 50px;">
                                            <?php else: ?>
                                                <span class="text-muted">Resim yok</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($slider['title']); ?></td>
                                        <td><?php echo mb_substr(htmlspecialchars($slider['description']), 0, 100) . (mb_strlen($slider['description']) > 100 ? '...' : ''); ?></td>
                                        <td><?php echo $slider['display_order']; ?></td>
                                        <td>
                                            <a href="slider.php?edit=<?php echo $slider['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="slider.php?delete=<?php echo $slider['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu slider\'ı silmek istediğinize emin misiniz?')">
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