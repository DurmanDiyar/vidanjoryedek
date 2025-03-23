    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Şirket Bilgileri -->
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5><?php echo htmlspecialchars($settings['site_title']); ?></h5>
                    <p>Kaliteli hizmet ve müşteri memnuniyeti odaklı çalışan kurumsal firmamız ile tanışın.</p>
                    <div class="social-icons">
                        <?php if (!empty($settings['facebook_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['twitter_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['instagram_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['linkedin_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['youtube_url'])): ?>
                            <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Hızlı Linkler -->
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5>Hızlı Linkler</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Ana Sayfa</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/hakkimizda.php">Hakkımızda</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/hizmetler.php">Hizmetlerimiz</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/galeri.php">Galeri</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/referanslar.php">Referanslar</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/iletisim.php">İletişim</a></li>
                    </ul>
                </div>
                
                <!-- Hizmetlerimiz -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Hizmetlerimiz</h5>
                    <ul class="list-unstyled">
                        <?php
                        try {
                            // Veritabanı bağlantısı yoksa yeniden oluştur
                            if (!isset($pdo)) {
                                $pdo = getDbConnection();
                            }
                            
                            $stmt = $pdo->prepare("SELECT id, name FROM services LIMIT 5");
                            $stmt->execute();
                            while ($service = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li><a href="' . SITE_URL . '/pages/hizmet-detay.php?id=' . $service['id'] . '">' . htmlspecialchars($service['name']) . '</a></li>';
                            }
                        } catch (PDOException $e) {
                            // Hata durumunda sessizce geç
                            echo '<!-- Veritabanı hatası: ' . $e->getMessage() . ' -->';
                        }
                        ?>
                    </ul>
                </div>
                
                <!-- İletişim Bilgileri -->
                <div class="col-lg-3 col-md-6">
                    <h5>İletişim</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($settings['contact_address'] ?? 'Adres bilgisi bulunamadı'); ?></li>
                        <li><i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($settings['contact_phone'] ?? 'Telefon bilgisi bulunamadı'); ?></li>
                        <li><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($settings['contact_email'] ?? 'E-posta bilgisi bulunamadı'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Footer Alt Kısım -->
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date("Y"); ?> <?php echo htmlspecialchars($settings['site_title']); ?>. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Floating İletişim Butonları -->
    <div class="floating-buttons visible">
        <?php if (!empty($settings['contact_phone'])): ?>
        <a href="tel:<?php echo htmlspecialchars($settings['contact_phone']); ?>" class="floating-button phone-button" title="Bizi Arayın">
            <i class="fas fa-phone"></i>
        </a>
        <?php endif; ?>

        <?php if (!empty($settings['whatsapp_phone'])): ?>
        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $settings['whatsapp_phone']); ?>" class="floating-button whatsapp-button" target="_blank" title="WhatsApp ile Yazın">
            <i class="fab fa-whatsapp"></i>
        </a>
        <?php else: ?>
        <a href="https://wa.me/905555555555" class="floating-button whatsapp-button" target="_blank" title="WhatsApp ile Yazın">
            <i class="fab fa-whatsapp"></i>
        </a>
        <?php endif; ?>

        <a href="<?php echo SITE_URL; ?>/pages/iletisim.php" class="floating-button contact-button" title="İletişim Sayfası">
            <i class="fas fa-envelope"></i>
        </a>

        <a href="#" class="floating-button back-to-top" title="Sayfa Başına Dön">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>

    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html> 