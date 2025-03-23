-- Önce mevcut users tablosunu yedekleyelim
CREATE TABLE IF NOT EXISTS `users_backup` LIKE `users`;
INSERT INTO `users_backup` SELECT * FROM `users`;

-- Tabloyu silip yeniden oluşturalım
DROP TABLE IF EXISTS `users`;

-- Users tablosunu yeniden oluştur
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','viewer') DEFAULT 'viewer',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Varsayılan admin kullanıcısını ekle (şifre: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `role`)
VALUES ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); 