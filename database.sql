-- Create database
CREATE DATABASE IF NOT EXISTS `corporate_site` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `corporate_site`;

-- Site settings table
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `site_title` varchar(255) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default site settings if not exists
INSERT INTO `site_settings` (`id`, `site_title`, `contact_phone`, `contact_email`, `contact_address`)
VALUES (1, 'Kurumsal Web Sitesi', '+90 555 123 4567', 'info@example.com', 'İstanbul, Türkiye');

-- Slider table
CREATE TABLE IF NOT EXISTS `slider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `display_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample slider items
INSERT INTO `slider` (`image_path`, `title`, `description`, `display_order`)
VALUES 
('assets/img/slider1.jpg', 'Profesyonel Hizmet', 'Uzman kadromuz ile kaliteli hizmet sunuyoruz', 1),
('assets/img/slider2.jpg', 'Modern Çözümler', 'İhtiyaçlarınıza uygun modern çözümler', 2);

-- Services table
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `icon` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample services
INSERT INTO `services` (`name`, `description`, `icon`, `price`)
VALUES 
('Hizmet 1', 'Hizmet 1 açıklaması burada yer alacak.', 'fas fa-cogs', 100.00),
('Hizmet 2', 'Hizmet 2 açıklaması burada yer alacak.', 'fas fa-tools', 150.00),
('Hizmet 3', 'Hizmet 3 açıklaması burada yer alacak.', 'fas fa-chart-line', 200.00);

-- Gallery table
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` enum('image','video') NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample gallery items
INSERT INTO `gallery` (`title`, `file_path`, `type`, `category`, `description`)
VALUES 
('Proje 1', 'assets/img/gallery1.jpg', 'image', 'Projeler', 'Proje 1 açıklaması'),
('Proje 2', 'assets/img/gallery2.jpg', 'image', 'Projeler', 'Proje 2 açıklaması'),
('Etkinlik 1', 'assets/img/gallery3.jpg', 'image', 'Etkinlikler', 'Etkinlik 1 açıklaması'),
('Tanıtım Videosu', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'video', 'Videolar', 'Tanıtım videosu açıklaması');

-- References table
CREATE TABLE IF NOT EXISTS `referencess` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `description` text,
  `website_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample references
INSERT INTO `referencess` (`company_name`, `logo_path`, `description`, `website_url`)
VALUES 
('ABC Şirketi', 'assets/img/ref1.png', 'ABC Şirketi ile uzun yıllardır çalışmaktayız.', 'https://www.example.com'),
('XYZ Holding', 'assets/img/ref2.png', 'XYZ Holding ile başarılı projelere imza attık.', 'https://www.example.com'),
('DEF Teknoloji', 'assets/img/ref3.png', 'DEF Teknoloji ile teknolojik çözümler ürettik.', 'https://www.example.com'),
('GHI İnşaat', 'assets/img/ref4.png', 'GHI İnşaat ile büyük projelerde yer aldık.', 'https://www.example.com'),
('JKL Otomotiv', 'assets/img/ref5.png', 'JKL Otomotiv ile sektöre yön verdik.', 'https://www.example.com'),
('MNO Tekstil', 'assets/img/ref6.png', 'MNO Tekstil ile uzun soluklu işbirliği yaptık.', 'https://www.example.com');

-- Contact messages table
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
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

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `role`)
VALUES ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'); 