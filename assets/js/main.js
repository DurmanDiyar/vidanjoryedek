/**
 * Main JavaScript file for the corporate website
 */

document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll davranışı
    const navbar = document.querySelector('.navbar');
    const topBar = document.querySelector('.top-bar');
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
                if (topBar) topBar.classList.add('top-bar-hidden');
            } else {
                navbar.classList.remove('navbar-scrolled');
                if (topBar) topBar.classList.remove('top-bar-hidden');
            }
        });
    }
    
    // Bootstrap Tooltip aktivasyonu
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Mobil menü toggle - Bootstrap 5 uyumlu
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        // Mobil menüyü açan butonun click olayı
        navbarToggler.addEventListener('click', function() {
            document.body.classList.toggle('mobile-menu-open');
            
            // Kapatma butonu ekle (yoksa)
            if (!document.querySelector('.mobile-close')) {
                const closeBtn = document.createElement('div');
                closeBtn.className = 'mobile-close';
                closeBtn.innerHTML = '<i class="fas fa-times"></i>';
                navbarCollapse.prepend(closeBtn);
                
                // Kapatma butonuna tıklama olayı
                closeBtn.addEventListener('click', function() {
                    document.body.classList.remove('mobile-menu-open');
                    // Bootstrap 5 collapse API'sini kullan
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                });
            }
        });
        
        // Menü linklerine tıklama - otomatik kapat
        navbarCollapse.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.body.classList.remove('mobile-menu-open');
                // Bootstrap 5 collapse API'sini kullan
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            });
        });
        
        // Ekranın dışına tıklama - otomatik kapat
        document.addEventListener('click', function(event) {
            if (navbarCollapse.classList.contains('show') && 
                !navbarCollapse.contains(event.target) && 
                !navbarToggler.contains(event.target)) {
                document.body.classList.remove('mobile-menu-open');
                // Bootstrap 5 collapse API'sini kullan
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    }
    
    // Sayfa başına dön butonu
    const backToTopButton = document.querySelector('.back-to-top');
    if (backToTopButton) {
        // Sayfa yüklendiğinde butonun durumunu kontrol et
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('visible');
        }
        
        // Sayfa kaydırıldığında butonu göster/gizle
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        
        // Butona tıklandığında sayfa başına kaydır
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Floating butonlar için görünürlük kontrolü
    const floatingButtons = document.querySelector('.floating-buttons');
    if (floatingButtons) {
        // Sayfa yüklendiğinde floating butonları göster
        floatingButtons.classList.add('visible');
        
        // Ekstra kontrol için timeout ekle
        setTimeout(function() {
            floatingButtons.classList.add('visible');
        }, 500);
    }
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    if (forms.length > 0) {
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
}); 