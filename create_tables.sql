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
SELECT 1, 'Kurumsal Web Sitesi', '+90 555 123 4567', 'info@example.com', 'İstanbul, Türkiye'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE id = 1);

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

-- Insert sample gallery items if table is empty
INSERT INTO `gallery` (`title`, `file_path`, `type`, `category`, `description`)
SELECT 'Proje 1', 'assets/img/gallery1.jpg', 'image', 'Projeler', 'Proje 1 açıklaması'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `gallery` LIMIT 1);

INSERT INTO `gallery` (`title`, `file_path`, `type`, `category`, `description`)
SELECT 'Proje 2', 'assets/img/gallery2.jpg', 'image', 'Projeler', 'Proje 2 açıklaması'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `gallery` LIMIT 1);

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

-- Insert sample references if table is empty
INSERT INTO `referencess` (`company_name`, `logo_path`, `description`, `website_url`)
SELECT 'ABC Şirketi', 'assets/img/ref1.png', 'ABC Şirketi ile uzun yıllardır çalışmaktayız.', 'https://www.example.com'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `referencess` LIMIT 1);

INSERT INTO `referencess` (`company_name`, `logo_path`, `description`, `website_url`)
SELECT 'XYZ Holding', 'assets/img/ref2.png', 'XYZ Holding ile başarılı projelere imza attık.', 'https://www.example.com'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `referencess` LIMIT 1);

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