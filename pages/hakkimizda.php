<?php
/**
 * About Us page for the corporate website
 */
require_once '../config.php';

// Set the page title for the header
$pageTitle = 'Hakkımızda';

// Include header
include_once '../includes/header.php';
?>

<!-- Page Header with enhanced styling -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold text-white mb-3">Hakkımızda</h1>
                <p class="lead text-white opacity-90 mb-0">Firmamızı daha yakından tanıyın.</p>
            </div>
        </div>
    </div>
</div>

<!-- About Us Section with enhanced styling -->
<section class="about-page-section pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="about-image position-relative">
                    <img src="<?php echo SITE_URL; ?>/assets/img/about-large.jpg" alt="Hakkımızda" class="img-fluid rounded shadow-lg">
                    <div class="experience-badge position-absolute text-white py-3 px-4 rounded shadow" style="background-color: var(--primary-color); bottom: -20px; right: 30px;">
                        <span class="h2 fw-bold d-block">15+</span>
                        <span>Yıllık Deneyim</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content ps-lg-4">
                    <h6 class="fw-bold mb-2 text-uppercase" style="color: var(--accent-color);">Biz Kimiz?</h6>
                    <h2 class="mb-4 fw-bold" style="color: var(--primary-color);">Profesyonel Çözüm Ortağınız</h2>
                    <p class="lead">
                        Firmamız, 2005 yılında kurulmuş olup, sektörde 15+ yıllık deneyime sahiptir.
                    </p>
                    <p>
                        Profesyonel ekibimiz, alanında uzman mühendisler, teknisyenler ve idari personelden oluşmaktadır. Her projede müşteri memnuniyetini ön planda tutarak, kaliteli ve güvenilir hizmet sunmayı ilke edindik.
                    </p>
                    <p>
                        Teknolojik gelişmeleri yakından takip ederek, modern çözümler sunuyoruz. Sürekli gelişen ve büyüyen yapımız ile sektörde öncü firmalardan biri olmayı başardık.
                    </p>
                    
                    <div class="row g-3 mt-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>500+ Tamamlanan Proje</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>50+ Uzman Personel</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>300+ Mutlu Müşteri</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>7/24 Teknik Destek</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mission & Vision with enhanced styling -->
        <div class="row mt-5">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box text-white rounded-circle me-3" style="background-color: var(--primary-color); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="card-title mb-0" style="color: var(--primary-color);">Misyonumuz</h3>
                        </div>
                        <p class="card-text">
                            Müşterilerimize en kaliteli hizmeti sunarak, ihtiyaçlarına uygun çözümler üretmek ve sektörde güvenilir bir marka olmak. Her projede müşteri memnuniyetini ön planda tutarak, kaliteli ve güvenilir hizmet sunmayı ilke edindik.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box text-white rounded-circle me-3" style="background-color: var(--primary-color); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="card-title mb-0" style="color: var(--primary-color);">Vizyonumuz</h3>
                        </div>
                        <p class="card-text">
                            Sektörde öncü ve yenilikçi çözümler üreten, müşteri memnuniyetini en üst düzeyde tutan, ulusal ve uluslararası alanda tanınan bir marka olmak. Sürekli gelişen ve büyüyen yapımız ile sektörde lider konuma ulaşmayı hedefliyoruz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Company Values with enhanced styling -->
        <div class="row mt-5">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title" style="color: var(--primary-color);">Değerlerimiz</h2>
                <p class="section-description">Kurumsal değerlerimiz ile fark yaratıyoruz.</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-handshake fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Güvenilirlik</h4>
                    <p class="text-muted">Müşterilerimize her zaman dürüst ve şeffaf davranırız.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-award fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Kalite</h4>
                    <p class="text-muted">Her işimizde en yüksek kalite standartlarını uygularız.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-users fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Müşteri Odaklılık</h4>
                    <p class="text-muted">Müşteri memnuniyeti bizim için her şeyden önemlidir.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-lightbulb fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Yenilikçilik</h4>
                    <p class="text-muted">Sürekli gelişir ve yeni çözümler üretiriz.</p>
                </div>
            </div>
        </div>
        
        <!-- Statistics with enhanced styling -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="stats-section bg-light p-5 rounded shadow-sm">
                    <h2 class="text-center mb-5 section-title" style="color: var(--primary-color);">Rakamlarla Biz</h2>
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                            <div class="counter-box">
                                <div class="counter-number" style="color: var(--primary-color);">15+</div>
                                <div class="counter-text">Yıllık Deneyim</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                            <div class="counter-box">
                                <div class="counter-number" style="color: var(--primary-color);">500+</div>
                                <div class="counter-text">Tamamlanan Proje</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                            <div class="counter-box">
                                <div class="counter-number" style="color: var(--primary-color);">50+</div>
                                <div class="counter-text">Uzman Personel</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="counter-box">
                                <div class="counter-number" style="color: var(--primary-color);">300+</div>
                                <div class="counter-text">Mutlu Müşteri</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Team Section with enhanced styling -->
        <div class="row mt-5">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title" style="color: var(--primary-color);">Ekibimiz</h2>
                <p class="section-description">Profesyonel ve deneyimli ekibimizle hizmetinizdeyiz.</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="team-member bg-white rounded shadow-sm hover-card">
                    <div class="team-img">
                        <img src="<?php echo SITE_URL; ?>/assets/img/team1.jpg" alt="Takım Üyesi" class="img-fluid rounded-top">
                    </div>
                    <div class="team-info text-center p-3">
                        <h4 class="mb-1" style="color: var(--primary-color);">Ahmet Yılmaz</h4>
                        <p class="text-muted mb-2">Genel Müdür</p>
                        <ul class="list-inline social-icons mb-0">
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-linkedin-in"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fas fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="team-member bg-white rounded shadow-sm hover-card">
                    <div class="team-img">
                        <img src="<?php echo SITE_URL; ?>/assets/img/team2.jpg" alt="Takım Üyesi" class="img-fluid rounded-top">
                    </div>
                    <div class="team-info text-center p-3">
                        <h4 class="mb-1" style="color: var(--primary-color);">Ayşe Demir</h4>
                        <p class="text-muted mb-2">Operasyon Müdürü</p>
                        <ul class="list-inline social-icons mb-0">
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-linkedin-in"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fas fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="team-member bg-white rounded shadow-sm hover-card">
                    <div class="team-img">
                        <img src="<?php echo SITE_URL; ?>/assets/img/team3.jpg" alt="Takım Üyesi" class="img-fluid rounded-top">
                    </div>
                    <div class="team-info text-center p-3">
                        <h4 class="mb-1" style="color: var(--primary-color);">Mehmet Kaya</h4>
                        <p class="text-muted mb-2">Teknik Müdür</p>
                        <ul class="list-inline social-icons mb-0">
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-linkedin-in"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fas fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="team-member bg-white rounded shadow-sm hover-card">
                    <div class="team-img">
                        <img src="<?php echo SITE_URL; ?>/assets/img/team4.jpg" alt="Takım Üyesi" class="img-fluid rounded-top">
                    </div>
                    <div class="team-info text-center p-3">
                        <h4 class="mb-1" style="color: var(--primary-color);">Zeynep Şahin</h4>
                        <p class="text-muted mb-2">Pazarlama Müdürü</p>
                        <ul class="list-inline social-icons mb-0">
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-linkedin-in"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fab fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-white" style="background-color: var(--primary-color);"><i class="fas fa-envelope"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action with enhanced styling -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="cta-section text-center p-5 rounded" style="background-color: var(--primary-color);">
                    <h2 class="text-white mb-3">Profesyonel Hizmetlerimiz İçin Bizimle İletişime Geçin</h2>
                    <p class="text-white mb-4 opacity-75">Uzman ekibimiz, ihtiyaçlarınıza uygun çözümler sunmak için hazır.</p>
                    <a href="<?php echo SITE_URL; ?>/pages/iletisim.php" class="btn btn-light btn-lg px-4 py-2">Bize Ulaşın</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?> 