<?php
/**
 * Admin Panel - Header Include
 * 
 * This file contains the header and navigation for the admin panel.
 */

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header('Location: index.php');
    exit;
}

// Get current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin Panel' : 'Admin Panel'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/assets/css/admin.css?v=<?php echo time(); ?>">
    
    <!-- Özel Renk Şeması Değişkenleri -->
    <?php 
    // Sayfayı her yenilemede taze renk şeması değişkenlerini almak için önbelleği atlama
    $cacheBuster = time(); 
    echo getColorSchemeVariables(); 
    ?>
    
    <!-- Admin Panel için Ek Stil Geçersiz Kılmalar -->
    <style>
        /* Admin panel özel stilleri */
        .sidebar { 
            background-color: var(--primary-color); 
            transition: background-color 0.3s ease-in-out;
        }
        .sidebar-heading { 
            color: var(--light-color);
        }
        .sidebar .nav-item .nav-link { 
            color: rgba(255, 255, 255, 0.9);
        }
        .sidebar .nav-item .nav-link:hover { 
            color: #fff;
            background-color: var(--secondary-color);
        }
        .sidebar .nav-item .nav-link.active { 
            color: #fff;
            background-color: var(--secondary-color);
        }
        .topbar { 
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .card-header { 
            background-color: var(--primary-color) !important; 
            color: #fff !important;
            transition: background-color 0.3s ease-in-out;
        }
        .btn-primary { 
            background-color: var(--primary-color) !important; 
            border-color: var(--primary-color) !important;
            transition: all 0.3s ease-in-out;
        }
        .btn-primary:hover, .btn-primary:focus { 
            background-color: var(--secondary-color) !important; 
            border-color: var(--secondary-color) !important;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Overlay -->
    <div class="overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="d-flex flex-column h-100">
            <!-- Sidebar Header -->
            <div class="p-3 d-flex align-items-center justify-content-between">
                <a href="<?php echo ADMIN_URL; ?>" class="text-white text-decoration-none">
                    <span class="admin-title">Admin Panel</span>
                </a>
                <button type="button" class="btn-close btn-close-white d-md-none" id="closeSidebarBtn" aria-label="Close"></button>
            </div>
            
            <!-- Main Navigation -->
            <div class="px-3 py-2 flex-grow-1">
                <ul class="nav nav-pills flex-column" id="adminMainMenu">
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/dashboard.php" class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/slider.php" class="nav-link <?php echo ($currentPage == 'slider.php') ? 'active' : ''; ?>">
                            <i class="fas fa-images me-2"></i>
                            <span>Slider Yönetimi</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/services.php" class="nav-link <?php echo ($currentPage == 'services.php') ? 'active' : ''; ?>">
                            <i class="fas fa-cogs me-2"></i>
                            <span>Hizmetler</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/gallery.php" class="nav-link <?php echo ($currentPage == 'gallery.php') ? 'active' : ''; ?>">
                            <i class="fas fa-photo-video me-2"></i>
                            <span>Galeri</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/references.php" class="nav-link <?php echo ($currentPage == 'references.php') ? 'active' : ''; ?>">
                            <i class="fas fa-handshake me-2"></i>
                            <span>Referanslar</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/messages.php" class="nav-link <?php echo ($currentPage == 'messages.php') ? 'active' : ''; ?>">
                            <i class="fas fa-envelope me-2"></i>
                            <span>Mesajlar</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/users.php" class="nav-link <?php echo ($currentPage == 'users.php') ? 'active' : ''; ?>">
                            <i class="fas fa-users me-2"></i>
                            <span>Kullanıcılar</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo ADMIN_URL; ?>/settings.php" class="nav-link <?php echo ($currentPage == 'settings.php') ? 'active' : ''; ?>">
                            <i class="fas fa-cog me-2"></i>
                            <span>Ayarlar</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Sidebar Footer - User -->
            <div class="mt-auto p-3 border-top border-secondary">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://via.placeholder.com/30" width="30" height="30" class="rounded-circle me-2">
                        <span><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>" target="_blank"><i class="fas fa-external-link-alt me-2"></i> Siteyi Görüntüle</a></li>
                        <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>/profile.php"><i class="fas fa-user-circle me-2"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo ADMIN_URL; ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content Wrapper -->
    <main class="main-content-wrapper" id="mainContent">
        <!-- Top Navigation Bar -->
        <header class="top-bar" id="topBar">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-link text-white p-0 menu-toggle me-3" id="sidebarToggleBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title mb-0"><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h1>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown d-inline-block me-3">
                        <a href="#" class="dropdown-toggle text-white text-decoration-none" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger rounded-pill">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationsDropdown" style="width: 300px;">
                            <li class="dropdown-header border-bottom">
                                <h6 class="mb-0">Bildirimler</h6>
                            </li>
                            <li><a class="dropdown-item py-2" href="#">
                                <div class="d-flex w-100">
                                    <div class="me-3">
                                        <i class="fas fa-envelope fa-lg text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Yeni bir mesaj alındı</p>
                                        <small class="text-muted">10 dakika önce</small>
                                    </div>
                                </div>
                            </a></li>
                            <li><a class="dropdown-item py-2" href="#">
                                <div class="d-flex w-100">
                                    <div class="me-3">
                                        <i class="fas fa-user-plus fa-lg text-success"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Yeni bir üye kaydoldu</p>
                                        <small class="text-muted">1 saat önce</small>
                                    </div>
                                </div>
                            </a></li>
                            <li><a class="dropdown-item py-2" href="#">
                                <div class="d-flex w-100">
                                    <div class="me-3">
                                        <i class="fas fa-exclamation-circle fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold">Sistem bildirimi</p>
                                        <small class="text-muted">2 saat önce</small>
                                    </div>
                                </div>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">Tüm bildirimleri gör</a></li>
                        </ul>
                    </div>
                    
                    <a href="<?php echo ADMIN_URL; ?>/logout.php" class="text-white text-decoration-none">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="d-none d-sm-inline-block ms-1">Çıkış</span>
                    </a>
                </div>
            </div>
        </header>
        
        <!-- Main Content Container -->
        <div class="container-fluid py-4"> 