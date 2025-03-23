<?php
/**
 * Admin Panel - Referans Yönetimi
 * 
 * Bu sayfa, referansların listelenmesi, eklenmesi, düzenlenmesi ve silinmesi işlemlerini yönetir.
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
$uploadsDir = '../uploads/references/';
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Referans silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $referenceId = $_GET['delete'];
    
    try {
        // Önce logo dosyasını bul
        $stmt = $db->prepare("SELECT logo_path FROM referencess WHERE id = ?");
        $stmt->execute([$referenceId]);
        $reference = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Referansı sil
        $stmt = $db->prepare("DELETE FROM referencess WHERE id = ?");
        $result = $stmt->execute([$referenceId]);
        
        if ($result) {
            // Logo dosyasını sil (eğer varsa)
            if ($reference && !empty($reference['logo_path']) && file_exists('../' . $reference['logo_path'])) {
                unlink('../' . $reference['logo_path']);
            }
            
            $message = 'Referans başarıyla silindi.';
            $messageType = 'success';
        } else {
            $message = 'Referans silinirken bir hata oluştu.';
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
    $referenceId = isset($_POST['reference_id']) ? intval($_POST['reference_id']) : 0;
    $companyName = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $websiteUrl = isset($_POST['website_url']) ? trim($_POST['website_url']) : '';
    
    // Logo yükleme işlemi
    $logoPath = '';
    $uploadError = '';
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($_FILES['logo']['type'], $allowedTypes)) {
            $uploadError = 'Sadece JPEG, PNG ve GIF formatları desteklenmektedir.';
        } elseif ($_FILES['logo']['size'] > $maxFileSize) {
            $uploadError = 'Dosya boyutu 2MB\'dan büyük olamaz.';
        } else {
            $fileName = time() . '_' . basename($_FILES['logo']['name']);
            $targetFilePath = $uploadsDir . $fileName;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                $logoPath = 'uploads/references/' . $fileName;
            } else {
                $uploadError = 'Dosya yüklenirken bir hata oluştu.';
            }
        }
    }
    
    // Verileri doğrula
    if (empty($companyName)) {
        $message = 'Firma adı boş olamaz.';
        $messageType = 'danger';
    } elseif (!empty($uploadError)) {
        $message = 'Logo yükleme hatası: ' . $uploadError;
        $messageType = 'danger';
    } else {
        try {
            // Yeni referans ekleme veya mevcut referansı güncelleme
            if ($referenceId > 0) {
                // Güncelleme
                if (!empty($logoPath)) {
                    // Eski logoyu bul ve sil
                    $stmt = $db->prepare("SELECT logo_path FROM referencess WHERE id = ?");
                    $stmt->execute([$referenceId]);
                    $oldReference = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($oldReference && !empty($oldReference['logo_path']) && file_exists('../' . $oldReference['logo_path'])) {
                        unlink('../' . $oldReference['logo_path']);
                    }
                    
                    // Logo ile birlikte güncelleme
                    $stmt = $db->prepare("UPDATE referencess SET company_name = ?, logo_path = ?, description = ?, website_url = ? WHERE id = ?");
                    $result = $stmt->execute([$companyName, $logoPath, $description, $websiteUrl, $referenceId]);
                } else {
                    // Logo olmadan güncelleme
                    $stmt = $db->prepare("UPDATE referencess SET company_name = ?, description = ?, website_url = ? WHERE id = ?");
                    $result = $stmt->execute([$companyName, $description, $websiteUrl, $referenceId]);
                }
                
                if ($result) {
                    $message = 'Referans başarıyla güncellendi.';
                    $messageType = 'success';
                } else {
                    $message = 'Referans güncellenirken bir hata oluştu.';
                    $messageType = 'danger';
                }
            } else {
                // Yeni ekleme
                $stmt = $db->prepare("INSERT INTO referencess (company_name, logo_path, description, website_url) VALUES (?, ?, ?, ?)");
                $result = $stmt->execute([$companyName, $logoPath, $description, $websiteUrl]);
                
                if ($result) {
                    $message = 'Referans başarıyla eklendi.';
                    $messageType = 'success';
                } else {
                    $message = 'Referans eklenirken bir hata oluştu.';
                    $messageType = 'danger';
                }
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Düzenlenecek referans
$editReference = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $referenceId = $_GET['edit'];
    
    try {
        $stmt = $db->prepare("SELECT * FROM referencess WHERE id = ?");
        $stmt->execute([$referenceId]);
        $editReference = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tüm referansları listele
try {
    $stmt = $db->query("SELECT * FROM referencess ORDER BY id DESC");
    $references = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $references = [];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// Sayfa başlığı
$pageTitle = 'Referans Yönetimi';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Referans Yönetimi</h1>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Referans Ekleme/Düzenleme Formu -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?php echo $editReference ? 'Referans Düzenle' : 'Yeni Referans Ekle'; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                        <?php if ($editReference): ?>
                            <input type="hidden" name="reference_id" value="<?php echo $editReference['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Firma Adı</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo $editReference ? htmlspecialchars($editReference['company_name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="website_url" class="form-label">Web Sitesi URL</label>
                            <input type="url" class="form-control" id="website_url" name="website_url" value="<?php echo $editReference ? htmlspecialchars($editReference['website_url']) : ''; ?>" placeholder="https://www.example.com">
                            <small class="form-text text-muted">Opsiyonel. Tam URL girin (https:// dahil).</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $editReference ? htmlspecialchars($editReference['description']) : ''; ?></textarea>
                            <small class="form-text text-muted">Opsiyonel. Firma hakkında kısa bir açıklama veya referans yorumu.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*" <?php echo $editReference && !empty($editReference['logo_path']) ? '' : 'required'; ?>>
                            <small class="form-text text-muted">Önerilen boyut: 200x100 piksel. Maksimum dosya boyutu: 2MB</small>
                            
                            <?php if ($editReference && !empty($editReference['logo_path'])): ?>
                                <div class="mt-2">
                                    <p>Mevcut Logo:</p>
                                    <img src="../<?php echo htmlspecialchars($editReference['logo_path']); ?>" class="img-thumbnail" style="max-height: 100px;">
                                    <p class="small text-muted mt-1">Yeni bir logo yüklerseniz, mevcut logo değiştirilecektir.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editReference ? 'Güncelle' : 'Ekle'; ?>
                            </button>
                            <?php if ($editReference): ?>
                                <a href="references.php" class="btn btn-secondary">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Referans Listesi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Referanslar</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($references)): ?>
                        <p class="text-center">Henüz referans bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th width="80">Logo</th>
                                        <th>Firma Adı</th>
                                        <th>Web Sitesi</th>
                                        <th>Açıklama</th>
                                        <th width="120">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($references as $reference): ?>
                                    <tr>
                                        <td><?php echo $reference['id']; ?></td>
                                        <td>
                                            <?php if (!empty($reference['logo_path'])): ?>
                                                <img src="../<?php echo htmlspecialchars($reference['logo_path']); ?>" class="img-thumbnail" style="max-height: 50px;">
                                            <?php else: ?>
                                                <span class="text-muted">Logo yok</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($reference['company_name']); ?></td>
                                        <td>
                                            <?php if (!empty($reference['website_url'])): ?>
                                                <a href="<?php echo htmlspecialchars($reference['website_url']); ?>" target="_blank">
                                                    <?php echo htmlspecialchars($reference['website_url']); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo mb_substr(htmlspecialchars($reference['description']), 0, 100) . (mb_strlen($reference['description']) > 100 ? '...' : ''); ?></td>
                                        <td>
                                            <a href="references.php?edit=<?php echo $reference['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="references.php?delete=<?php echo $reference['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu referansı silmek istediğinize emin misiniz?')">
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