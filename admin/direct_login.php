<?php
/**
 * Admin Panel - Direct Login
 * 
 * Bu sayfa, doğrudan admin girişi sağlar.
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
    
    // Admin kullanıcısını bul
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->execute(['admin']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Kullanıcı bulundu, oturum bilgilerini ayarla
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        
        // Update last login time
        $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->execute([$user['id']]);
        
        // Başarılı mesajı göster
        echo "Admin girişi başarılı. Dashboard'a yönlendiriliyorsunuz...";
        echo "<script>setTimeout(function() { window.location.href = 'dashboard.php'; }, 2000);</script>";
    } else {
        // Kullanıcı bulunamadı, yeni admin kullanıcısı oluştur
        $username = 'admin';
        $email = 'admin@example.com';
        $password = 'admin123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        $result = $stmt->execute([$username, $email, $hashedPassword]);
        
        if ($result) {
            // Kullanıcı oluşturuldu, oturum bilgilerini ayarla
            $userId = $db->lastInsertId();
            
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $userId;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_role'] = 'admin';
            
            // Başarılı mesajı göster
            echo "Admin kullanıcısı oluşturuldu ve giriş yapıldı. Dashboard'a yönlendiriliyorsunuz...";
            echo "<script>setTimeout(function() { window.location.href = 'dashboard.php'; }, 3000);</script>";
        } else {
            // Kullanıcı oluşturulamadı
            echo "Admin kullanıcısı oluşturulamadı. Lütfen manuel olarak oluşturun.";
        }
    }
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?> 