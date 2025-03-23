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
                    <img src="<?php echo SITE_URL; ?>/assets/img/a.png" alt="Hakkımızda" class="img-fluid rounded shadow-lg">
                    <div class="experience-badge position-absolute text-white py-3 px-4 rounded shadow" style="background-color: var(--primary-color); bottom: -20px; right: 30px;">
                        <span class="h2 fw-bold d-block">15+</span>
                        <span>Yıllık Deneyim</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content ps-lg-4">
                    <h6 class="fw-bold mb-2 text-uppercase" style="color: var(--accent-color);">Biz Kimiz?</h6>
                    <h2 class="mb-4 fw-bold" style="color: var(--primary-color);">Profesyonel Vidanjör Hizmetleri</h2>
                    <p class="lead">
                        Durman Vidanjör olarak, İzmir, İstanbul ve Tekirdağ bölgelerinde 15+ yıldır profesyonel vidanjör hizmetleri sunmaktayız.
                    </p>
                    <p>
                        Deneyimli ekibimiz ve modern ekipmanlarımız ile kanalizasyon açma, logar temizleme, fosseptik çukuru temizliği, rögar temizliği ve tüm vidanjör hizmetlerinde kaliteli ve güvenilir çözümler sağlıyoruz.
                    </p>
                    <p>
                        7/24 hizmet anlayışımız, hızlı müdahale ekiplerimiz ve uzun yıllara dayanan tecrübemiz ile sektörde öncü bir firma olarak, müşteri memnuniyetini en üst düzeyde tutmayı ilke edindik.
                    </p>
                    
                    <div class="row g-3 mt-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>5000+ Başarılı İşlem</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>15+ Uzman Personel</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>1500+ Mutlu Müşteri</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 text-white rounded p-2" style="background-color: var(--secondary-color);">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>7/24 Acil Servis</span>
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
                            Vidanjör hizmetlerinde kalite standartlarını yükselterek, müşterilerimize hızlı, güvenilir ve ekonomik çözümler sunmak. Çevre sağlığına verdiğimiz önem doğrultusunda, modern ekipmanlarımız ve uzman ekibimiz ile tüm kanalizasyon ve fosseptik problemlerine profesyonel çözümler üretmek.
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
                            Vidanjör sektöründe İzmir, İstanbul ve Tekirdağ bölgelerinde lider konuma gelerek, hizmet kalitesi denildiğinde akla ilk gelen marka olmak. Sürekli yenilenen araç filomuz, teknolojik ekipmanlarımız ve deneyimli kadromuz ile hizmet ağımızı tüm Türkiye'ye yaymak.
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
                    <p class="text-muted">Müşterilerimize dürüst, şeffaf ve güvenilir hizmet sunmayı taahhüt ediyoruz.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-award fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Kalite</h4>
                    <p class="text-muted">Her işimizde en yüksek kalite standartlarını uygulayarak müşteri memnuniyeti sağlıyoruz.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-users fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Müşteri Odaklılık</h4>
                    <p class="text-muted">Her zaman müşteri ihtiyaçlarını ön planda tutarak çözüm odaklı yaklaşım sergiliyoruz.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="value-item text-center p-4 rounded bg-light shadow-sm hover-card">
                    <div class="value-icon mb-3">
                        <i class="fas fa-lightbulb fa-3x" style="color: var(--accent-color);"></i>
                    </div>
                    <h4 style="color: var(--primary-color);">Yenilikçilik</h4>
                    <p class="text-muted">Modern vidanjör ekipmanları ve yenilikçi çözümlerle sektörde fark yaratıyoruz.</p>
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
                                <div class="counter-number" style="color: var(--primary-color);">5000+</div>
                                <div class="counter-text">Tamamlanan İşlem</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                            <div class="counter-box">
                                <div class="counter-number" style="color: var(--primary-color);">15+</div>
                                <div class="counter-text">Uzman Personel</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="counter-box">
                                <div class="counter-number" style="color: var(--primary-color);">1500+</div>
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
                        <h4 class="mb-1" style="color: var(--primary-color);">Cevdet Durman</h4>
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
                        <h4 class="mb-1" style="color: var(--primary-color);">Ali Durman</h4>
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
                        <h4 class="mb-1" style="color: var(--primary-color);">İshak Durman</h4>
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
                        <h4 class="mb-1" style="color: var(--primary-color);">Durman Hizmet Ekibi</h4>
                        <p class="text-muted mb-2">Saha Operasyon Ekibi</p>
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
                    <h2 class="text-white mb-3">Profesyonel Vidanjör Hizmetlerimiz İçin Bizimle İletişime Geçin</h2>
                    <p class="text-white mb-4 opacity-75">İzmir, İstanbul ve Tekirdağ bölgelerinde 7/24 hizmet veren uzman ekibimiz her an yanınızda!</p>
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