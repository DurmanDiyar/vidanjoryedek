<?php
/**
 * Admin Panel - Galeri Yönetimi
 * 
 * Bu sayfa, galeri içeriklerinin listelenmesi, eklenmesi, düzenlenmesi ve silinmesi işlemlerini yönetir.
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
$uploadsDir = '../uploads/gallery/';
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Galeri öğesi silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $galleryId = $_GET['delete'];
    
    try {
        // Önce dosya yolunu bul
        $stmt = $db->prepare("SELECT file_path FROM gallery WHERE id = ?");
        $stmt->execute([$galleryId]);
        $gallery = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Galeri öğesini sil
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        $result = $stmt->execute([$galleryId]);
        
        if ($result) {
            // Dosyayı sil (eğer varsa)
            if ($gallery && !empty($gallery['file_path']) && file_exists('../' . $gallery['file_path'])) {
                unlink('../' . $gallery['file_path']);
            }
            
            $message = 'Galeri öğesi başarıyla silindi.';
            $messageType = 'success';
            
            // Başarılı silme işleminden sonra yönlendirme yap
            header("Location: gallery.php?deleted=1");
            exit;
        } else {
            $message = 'Galeri öğesi silinirken bir hata oluştu.';
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
    $galleryId = isset($_POST['gallery_id']) ? intval($_POST['gallery_id']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $type = isset($_POST['type']) ? trim($_POST['type']) : 'image';
    
    // Dosya yükleme işlemi
    $filePath = '';
    $uploadError = '';
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $allowedVideoTypes = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/mpeg'];
        $allowedTypes = array_merge($allowedImageTypes, $allowedVideoTypes);
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        
        $fileType = $_FILES['file']['type'];
        $fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        
        // MIME tipi kontrolünün yanında dosya uzantısına da bakıyoruz
        $isVideo = in_array($fileType, $allowedVideoTypes) || 
                   in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'wmv', 'mpg', 'mpeg']);
        $isImage = in_array($fileType, $allowedImageTypes) || 
                   in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']);
        
        if (!$isVideo && !$isImage) {
            $uploadError = 'Desteklenmeyen dosya formatı. Lütfen JPEG, PNG, GIF, MP4, WEBM, OGG formatında bir dosya seçin.';
        } elseif ($_FILES['file']['size'] > $maxFileSize) {
            $uploadError = 'Dosya boyutu 10MB\'dan büyük olamaz.';
        } else {
            $fileName = time() . '_' . basename($_FILES['file']['name']);
            $targetFilePath = $uploadsDir . $fileName;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
                $filePath = 'uploads/gallery/' . $fileName;
                
                // Dosya türünü kontrol et (eğer kullanıcı manuel değiştirirse override edilsin diye)
                if ($isImage) {
                    $type = 'image';
                } else if ($isVideo) {
                    $type = 'video';
                }
            } else {
                $uploadError = 'Dosya yüklenirken bir hata oluştu.';
            }
        }
    }
    
    // Hata kontrolü
    if (!empty($uploadError)) {
        $message = 'Dosya yükleme hatası: ' . $uploadError;
        $messageType = 'danger';
    } else {
        try {
            // Yeni galeri öğesi ekleme veya mevcut öğeyi güncelleme
            if ($galleryId > 0) {
                // Güncelleme
                if (!empty($filePath)) {
                    // Eski dosyayı bul ve sil
                    $stmt = $db->prepare("SELECT file_path FROM gallery WHERE id = ?");
                    $stmt->execute([$galleryId]);
                    $oldGallery = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($oldGallery && !empty($oldGallery['file_path']) && file_exists('../' . $oldGallery['file_path'])) {
                        unlink('../' . $oldGallery['file_path']);
                    }
                    
                    // Dosya ile birlikte güncelleme
                    $stmt = $db->prepare("UPDATE gallery SET title = ?, file_path = ?, type = ?, category = ?, description = ? WHERE id = ?");
                    $result = $stmt->execute([$title, $filePath, $type, $category, $description, $galleryId]);
                } else {
                    // Dosya olmadan güncelleme
                    $stmt = $db->prepare("UPDATE gallery SET title = ?, type = ?, category = ?, description = ? WHERE id = ?");
                    $result = $stmt->execute([$title, $type, $category, $description, $galleryId]);
                }
                
                if ($result) {
                    $message = 'Galeri öğesi başarıyla güncellendi.';
                    $messageType = 'success';
                    
                    // Form yeniden gönderimini önlemek için yönlendirme yap
                    header("Location: gallery.php?updated=1");
                    exit;
                } else {
                    $message = 'Galeri öğesi güncellenirken bir hata oluştu.';
                    $messageType = 'danger';
                }
            } else {
                // Yeni ekleme (dosya zorunlu)
                if (empty($filePath)) {
                    $message = 'Lütfen bir dosya seçin.';
                    $messageType = 'danger';
                } else {
                    $stmt = $db->prepare("INSERT INTO gallery (title, file_path, type, category, description) VALUES (?, ?, ?, ?, ?)");
                    $result = $stmt->execute([$title, $filePath, $type, $category, $description]);
                    
                    if ($result) {
                        $message = 'Galeri öğesi başarıyla eklendi.';
                        $messageType = 'success';
                        
                        // Form yeniden gönderimini önlemek için yönlendirme yap
                        header("Location: gallery.php?success=1");
                        exit;
                    } else {
                        $message = 'Galeri öğesi eklenirken bir hata oluştu.';
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

// Düzenlenecek galeri öğesi
$editGallery = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $galleryId = $_GET['edit'];
    
    try {
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$galleryId]);
        $editGallery = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tüm kategorileri al
try {
    $stmt = $db->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categories = [];
}

// Tüm galeri öğelerini listele
try {
    $stmt = $db->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
    $galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $galleryItems = [];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// URL parametrelerine göre mesaj gösterme
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = 'Galeri öğesi başarıyla eklendi.';
    $messageType = 'success';
}

if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $message = 'Galeri öğesi başarıyla güncellendi.';
    $messageType = 'success';
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $message = 'Galeri öğesi başarıyla silindi.';
    $messageType = 'success';
}

// Sayfa başlığı
$pageTitle = 'Galeri Yönetimi';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Galeri Yönetimi</h1>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Galeri Öğesi Ekleme/Düzenleme Formu -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?php echo $editGallery ? 'Galeri Öğesi Düzenle' : 'Yeni Galeri Öğesi Ekle'; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                        <?php if ($editGallery): ?>
                            <input type="hidden" name="gallery_id" value="<?php echo $editGallery['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Başlık</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $editGallery ? htmlspecialchars($editGallery['title']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Dosya Türü</label>
                            <select class="form-select" id="type" name="type" onchange="updateCategory()">
                                <option value="image" <?php echo ($editGallery && $editGallery['type'] == 'image') ? 'selected' : ''; ?>>Resim</option>
                                <option value="video" <?php echo ($editGallery && $editGallery['type'] == 'video') ? 'selected' : ''; ?>>Video</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select" id="category" name="category">
                                <option value="Resimler" <?php echo ($editGallery && $editGallery['category'] == 'Resimler') ? 'selected' : ''; ?>>Resimler</option>
                                <option value="Videolar" <?php echo ($editGallery && $editGallery['category'] == 'Videolar') ? 'selected' : ''; ?>>Videolar</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo $editGallery ? htmlspecialchars($editGallery['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">Dosya (Resim/Video)</label>
                            <input type="file" class="form-control" id="file" name="file" accept="image/*,video/*,.mp4,.webm,.ogg,.mov,.avi" <?php echo $editGallery ? '' : 'required'; ?>>
                            <small class="form-text text-muted">Desteklenen formatlar: JPEG, PNG, GIF, MP4, WEBM, OGG, MOV, AVI. Maksimum dosya boyutu: 10MB.<br>Önerilen resim boyutu: minimum 800x600 piksel. Resimler orijinal boyutunda yüklenir.</small>
                            
                            <?php if ($editGallery && !empty($editGallery['file_path'])): ?>
                                <div class="mt-2">
                                    <p>Mevcut Dosya:</p>
                                    <?php if ($editGallery['type'] == 'image'): ?>
                                        <img src="../<?php echo htmlspecialchars($editGallery['file_path']); ?>" class="img-thumbnail" style="max-height: 150px;">
                                    <?php else: ?>
                                        <video controls class="img-thumbnail" style="max-height: 150px;">
                                            <source src="../<?php echo htmlspecialchars($editGallery['file_path']); ?>" type="video/mp4">
                                            Tarayıcınız video etiketini desteklemiyor.
                                        </video>
                                    <?php endif; ?>
                                    <p class="small text-muted mt-1">Yeni bir dosya yüklerseniz, mevcut dosya değiştirilecektir.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editGallery ? 'Güncelle' : 'Ekle'; ?>
                            </button>
                            <?php if ($editGallery): ?>
                                <a href="gallery.php" class="btn btn-secondary">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Galeri Listesi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Galeri Öğeleri</h6>
                    
                    <!-- Kategori Filtresi -->
                    <?php if (!empty($categories)): ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="categoryFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Kategori Filtresi
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoryFilterDropdown">
                            <li><a class="dropdown-item" href="gallery.php">Tümü</a></li>
                            <?php foreach ($categories as $category): ?>
                                <li><a class="dropdown-item" href="gallery.php?category=<?php echo urlencode($category); ?>"><?php echo htmlspecialchars($category); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($galleryItems)): ?>
                        <p class="text-center">Henüz galeri öğesi bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th width="80">Önizleme</th>
                                        <th>Başlık</th>
                                        <th>Kategori</th>
                                        <th>Tür</th>
                                        <th width="120">Yüklenme Tarihi</th>
                                        <th width="120">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($galleryItems as $item): ?>
                                    <tr>
                                        <td><?php echo $item['id']; ?></td>
                                        <td>
                                            <?php if (!empty($item['file_path'])): ?>
                                                <?php if ($item['type'] == 'image'): ?>
                                                    <img src="../<?php echo htmlspecialchars($item['file_path']); ?>" class="img-thumbnail" style="max-height: 50px;">
                                                <?php else: ?>
                                                    <i class="fas fa-video fa-2x text-primary"></i>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Dosya yok</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td><?php echo $item['type'] == 'image' ? 'Resim' : 'Video'; ?></td>
                                        <td><?php echo date('d.m.Y H:i', strtotime($item['uploaded_at'])); ?></td>
                                        <td>
                                            <a href="gallery.php?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="gallery.php?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu galeri öğesini silmek istediğinize emin misiniz?')">
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

<script>
// Dosya türüne göre kategoriyi güncelle
function updateCategory() {
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category');
    
    if (typeSelect && categorySelect) {
        const selectedType = typeSelect.value;
        
        if (selectedType === 'image') {
            categorySelect.value = 'Resimler';
        } else if (selectedType === 'video') {
            categorySelect.value = 'Videolar';
        }
    }
}

// Dosya seçildiğinde kontrol et ve türe göre seçimler yap
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const typeSelect = document.getElementById('type');
    
    if (fileInput && typeSelect) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name.toLowerCase();
                const fileType = this.files[0].type;
                
                // Dosya uzantısını al
                const extension = fileName.split('.').pop();
                
                // Video formatlarını kontrol et
                const videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'avi', 'wmv', 'mpeg', 'mpg'];
                
                if (fileType.startsWith('video/') || videoExtensions.includes(extension)) {
                    typeSelect.value = 'video';
                } else {
                    typeSelect.value = 'image';
                }
                
                // Kategoriyi güncelle
                updateCategory();
            }
        });
    }
    
    // Form gönderildiğinde dosya boş kontrolü
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('file');
            const galleryId = document.querySelector('input[name="gallery_id"]');
            
            // Yeni ekleme ise (edit değilse) ve dosya seçilmemişse
            if (!galleryId && fileInput && fileInput.files.length === 0) {
                e.preventDefault();
                alert('Lütfen bir dosya seçin.');
            }
        });
    }
});
</script>
</body>
</html> 