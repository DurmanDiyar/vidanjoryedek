<?php
/**
 * Admin Kullanıcısı Oluşturma Scripti
 * 
 * Bu script, veritabanında admin kullanıcısı oluşturur.
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
require_once 'config.php';

try {
    // Veritabanı bağlantısını al
    $db = getDbConnection();
    
    // Kullanıcı adı ve şifre
    $username = 'admin';
    $email = 'admin@example.com';
    $password = 'admin123';
    $role = 'admin';
    
    // Şifreyi hash'le
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Hash'i göster
    echo "Oluşturulan şifre hash'i: " . $hashedPassword . "<br>";
    
    // Kullanıcı var mı kontrol et
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Kullanıcı varsa güncelle
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
        $result = $stmt->execute([$hashedPassword, $username]);
        
        if ($result) {
            echo "Admin kullanıcısı güncellendi.<br>";
            echo "Kullanıcı adı: " . $username . "<br>";
            echo "Şifre: " . $password . "<br>";
        } else {
            echo "Admin kullanıcısı güncellenirken bir hata oluştu.<br>";
        }
    } else {
        // Kullanıcı yoksa oluştur
        $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$username, $email, $hashedPassword, $role]);
        
        if ($result) {
            echo "Admin kullanıcısı oluşturuldu.<br>";
            echo "Kullanıcı adı: " . $username . "<br>";
            echo "Şifre: " . $password . "<br>";
        } else {
            echo "Admin kullanıcısı oluşturulurken bir hata oluştu.<br>";
        }
    }
    
    // Veritabanındaki kullanıcıları listele
    $stmt = $db->query("SELECT id, username, email, password, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Veritabanındaki Kullanıcılar:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Kullanıcı Adı</th><th>E-posta</th><th>Şifre Hash</th><th>Rol</th></tr>";
    
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['password'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Varsayılan hash ile doğrulama testi
    $defaultHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    $testPassword = 'admin123';
    
    echo "<h3>Şifre Doğrulama Testi:</h3>";
    echo "Varsayılan hash ile doğrulama: " . ($testPassword === 'admin123' && password_verify($testPassword, $defaultHash) ? 'Başarılı' : 'Başarısız') . "<br>";
    echo "Yeni oluşturulan hash ile doğrulama: " . (password_verify($testPassword, $hashedPassword) ? 'Başarılı' : 'Başarısız') . "<br>";
    
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?> 