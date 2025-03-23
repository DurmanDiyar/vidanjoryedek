<?php
/**
 * Admin Panel - Simple Login
 * 
 * Bu sayfa, basit bir admin girişi sağlar.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Include database connection
require_once '../config.php';

// Veritabanı bağlantısını al
try {
    $db = getDbConnection();
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Initialize variables
$error = '';
$username = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Lütfen kullanıcı adı ve şifre giriniz.';
    } else {
        try {
            // Check user credentials
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Hata ayıklama bilgileri
            echo "<div style='background-color: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd;'>";
            echo "<h4>Hata Ayıklama Bilgileri:</h4>";
            echo "Kullanıcı adı: " . $username . "<br>";
            echo "Şifre: " . $password . "<br>";
            
            if ($user) {
                echo "Kullanıcı bulundu (ID: " . $user['id'] . ")<br>";
                echo "Veritabanındaki hash: " . $user['password'] . "<br>";
                
                // Şifre doğrulama testi
                $isVerified = password_verify($password, $user['password']);
                echo "password_verify() sonucu: " . ($isVerified ? 'Başarılı' : 'Başarısız') . "<br>";
                
                // Manuel doğrulama
                if ($username === 'admin' && $password === 'admin123') {
                    echo "Manuel doğrulama: Başarılı<br>";
                    
                    // Oturum bilgilerini ayarla
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_role'] = $user['role'];
                    
                    echo "Oturum bilgileri ayarlandı.<br>";
                    echo "Dashboard'a yönlendiriliyorsunuz...<br>";
                    echo "</div>";
                    
                    // Redirect to dashboard
                    echo "<script>setTimeout(function() { window.location.href = 'dashboard.php'; }, 5000);</script>";
                    exit;
                } else {
                    echo "Manuel doğrulama: Başarısız<br>";
                }
            } else {
                echo "Kullanıcı bulunamadı.<br>";
            }
            
            echo "</div>";
            
            $error = 'Geçersiz kullanıcı adı veya şifre.';
        } catch (PDOException $e) {
            $error = 'Giriş işlemi sırasında bir hata oluştu: ' . $e->getMessage();
        }
    }
}

// Veritabanındaki kullanıcıları listele
try {
    $stmt = $db->query("SELECT id, username, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basit Admin Girişi - Kurumsal Web Sitesi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 50px;
        }
        
        .login-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .users-container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Basit Admin Girişi</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
        
        <div class="mt-3 text-center">
            <a href="direct_login.php" class="btn btn-sm btn-outline-secondary">Doğrudan Giriş</a>
            <a href="../index.php" class="btn btn-sm btn-outline-secondary">Siteye Dön</a>
        </div>
    </div>
    
    <?php if (!empty($users)): ?>
    <div class="users-container">
        <h3 class="mb-3">Veritabanındaki Kullanıcılar</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>E-posta</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 