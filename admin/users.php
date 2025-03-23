<?php
/**
 * Admin Panel - Kullanıcı Yönetimi
 * 
 * Bu sayfa, admin paneli kullanıcılarının listelenmesi, eklenmesi, düzenlenmesi ve silinmesi işlemlerini yönetir.
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

// Admin rolü kontrolü - sadece admin rolündeki kullanıcılar bu sayfaya erişebilir
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: dashboard.php');
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

// Kullanıcı silme işlemi
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $userId = $_GET['delete'];
    
    // Kendini silmeye çalışıyorsa engelle
    if ($userId == $_SESSION['admin_id']) {
        $message = 'Kendi hesabınızı silemezsiniz.';
        $messageType = 'danger';
    } else {
        try {
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $result = $stmt->execute([$userId]);
            
            if ($result) {
                $message = 'Kullanıcı başarıyla silindi.';
                $messageType = 'success';
            } else {
                $message = 'Kullanıcı silinirken bir hata oluştu.';
                $messageType = 'danger';
            }
        } catch (PDOException $e) {
            $message = 'Veritabanı hatası: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'viewer';
    
    // Verileri doğrula
    if (empty($username)) {
        $message = 'Kullanıcı adı boş olamaz.';
        $messageType = 'danger';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Geçerli bir e-posta adresi girin.';
        $messageType = 'danger';
    } elseif ($userId == 0 && empty($password)) {
        $message = 'Şifre boş olamaz.';
        $messageType = 'danger';
    } elseif (!empty($password) && strlen($password) < 6) {
        $message = 'Şifre en az 6 karakter olmalıdır.';
        $messageType = 'danger';
    } else {
        try {
            // Kullanıcı adı ve e-posta benzersiz olmalı
            if ($userId > 0) {
                // Güncelleme durumunda, mevcut kullanıcı hariç kontrol et
                $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?");
                $stmt->execute([$username, $email, $userId]);
            } else {
                // Yeni ekleme durumunda
                $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
            }
            
            if ($stmt->fetchColumn() > 0) {
                $message = 'Bu kullanıcı adı veya e-posta adresi zaten kullanılıyor.';
                $messageType = 'danger';
            } else {
                // Yeni kullanıcı ekleme veya mevcut kullanıcıyı güncelleme
                if ($userId > 0) {
                    // Güncelleme
                    if (!empty($password)) {
                        // Şifre ile güncelleme
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
                        $result = $stmt->execute([$username, $email, $hashedPassword, $role, $userId]);
                    } else {
                        // Şifre olmadan güncelleme
                        $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                        $result = $stmt->execute([$username, $email, $role, $userId]);
                    }
                    
                    if ($result) {
                        $message = 'Kullanıcı başarıyla güncellendi.';
                        $messageType = 'success';
                    } else {
                        $message = 'Kullanıcı güncellenirken bir hata oluştu.';
                        $messageType = 'danger';
                    }
                } else {
                    // Yeni ekleme
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                    $result = $stmt->execute([$username, $email, $hashedPassword, $role]);
                    
                    if ($result) {
                        $message = 'Kullanıcı başarıyla eklendi.';
                        $messageType = 'success';
                    } else {
                        $message = 'Kullanıcı eklenirken bir hata oluştu.';
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

// Düzenlenecek kullanıcı
$editUser = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $userId = $_GET['edit'];
    
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Veritabanı hatası: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tüm kullanıcıları listele
try {
    $stmt = $db->query("SELECT * FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
    $message = 'Veritabanı hatası: ' . $e->getMessage();
    $messageType = 'danger';
}

// Sayfa başlığı
$pageTitle = 'Kullanıcı Yönetimi';

// Header'ı dahil et
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kullanıcı Yönetimi</h1>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Kullanıcı Ekleme/Düzenleme Formu -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <?php echo $editUser ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı Ekle'; ?>
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <?php if ($editUser): ?>
                            <input type="hidden" name="user_id" value="<?php echo $editUser['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $editUser ? htmlspecialchars($editUser['username']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta Adresi</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <?php echo $editUser ? 'Şifre (değiştirmek istemiyorsanız boş bırakın)' : 'Şifre'; ?>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" <?php echo $editUser ? '' : 'required'; ?>>
                            <small class="form-text text-muted">En az 6 karakter olmalıdır.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?php echo ($editUser && $editUser['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="editor" <?php echo ($editUser && $editUser['role'] == 'editor') ? 'selected' : ''; ?>>Editör</option>
                                <option value="viewer" <?php echo ($editUser && $editUser['role'] == 'viewer') ? 'selected' : ''; ?>>İzleyici</option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>Admin:</strong> Tüm yetkilere sahiptir.<br>
                                <strong>Editör:</strong> İçerik ekleme ve düzenleme yetkisine sahiptir.<br>
                                <strong>İzleyici:</strong> Sadece görüntüleme yetkisine sahiptir.
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editUser ? 'Güncelle' : 'Ekle'; ?>
                            </button>
                            <?php if ($editUser): ?>
                                <a href="users.php" class="btn btn-secondary">İptal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Kullanıcı Listesi -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kullanıcılar</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <p class="text-center">Henüz kullanıcı bulunmuyor.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th>Kullanıcı Adı</th>
                                        <th>E-posta</th>
                                        <th>Rol</th>
                                        <th>Son Giriş</th>
                                        <th width="120">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php
                                            switch ($user['role']) {
                                                case 'admin':
                                                    echo '<span class="badge bg-danger">Admin</span>';
                                                    break;
                                                case 'editor':
                                                    echo '<span class="badge bg-warning">Editör</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge bg-secondary">İzleyici</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'Hiç giriş yapmadı'; ?>
                                        </td>
                                        <td>
                                            <a href="users.php?edit=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                                                <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-danger" disabled title="Kendi hesabınızı silemezsiniz">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
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