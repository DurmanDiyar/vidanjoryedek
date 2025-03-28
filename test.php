<?php
// Database bağlantısı için config.php dosyasını dahil et
require_once 'config.php';

// Hata raporlama
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Veritabanı bağlantısını al
    $pdo = getDbConnection();
    
    // Referansları çek
    $stmt = $pdo->query("SELECT * FROM referencess LIMIT 10");
    $references = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h1>Referanslar Tablosu İçeriği</h1>";
    
    if (count($references) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Firma Adı</th><th>Logo</th><th>Logo Path</th></tr>";
        
        foreach ($references as $reference) {
            echo "<tr>";
            echo "<td>" . $reference['id'] . "</td>";
            echo "<td>" . $reference['company_name'] . "</td>";
            echo "<td><img src='uploads/references/" . $reference['logo_path'] . "' width='100'></td>";
            echo "<td>" . $reference['logo_path'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Veritabanında referans kaydı bulunamadı.</p>";
    }
    
} catch (PDOException $e) {
    echo "Veritabanı Hatası: " . $e->getMessage();
}
?>
