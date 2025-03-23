/**
 * Menu JavaScript Functions
 * Handles menu behavior and animations
 */

document.addEventListener('DOMContentLoaded', function() {
    /**
     * Mobil menü toggle işlevi
     */
    function setupMobileMenu() {
        var toggler = document.querySelector('.navbar-toggler');
        var collapse = document.querySelector('.navbar-collapse');
        
        if (!toggler || !collapse) return;
        
        // Toggle işlevi
        toggler.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle classes
            if (collapse.classList.contains('show')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        // Menü açma fonksiyonu
        function openMenu() {
            collapse.classList.add('show');
            toggler.classList.remove('collapsed');
            toggler.setAttribute('aria-expanded', 'true');
        }
        
        // Menü kapatma fonksiyonu
        function closeMenu() {
            collapse.classList.remove('show');
            toggler.classList.add('collapsed');
            toggler.setAttribute('aria-expanded', 'false');
        }
        
        // Menü linklerine tıklandığında menüyü kapat
        var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992 && collapse.classList.contains('show')) {
                    closeMenu();
                }
            });
        });
        
        // Menü dışına tıklandığında menüyü kapat
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && 
                collapse.classList.contains('show') && 
                !toggler.contains(e.target) && 
                !collapse.contains(e.target)) {
                
                closeMenu();
            }
        });
        
        // ESC tuşuna basıldığında menüyü kapat
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && collapse.classList.contains('show')) {
                closeMenu();
            }
        });
    }
    
    /**
     * Aktif menü öğesini ayarla
     */
    function setupActiveMenuItem() {
        var currentPage = window.location.pathname;
        var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        
        navLinks.forEach(function(link) {
            var href = link.getAttribute('href');
            if (href === currentPage || 
                (currentPage.includes(href) && href !== '/' && href !== '#')) {
                link.classList.add('active');
                
                // Dropdown öğesiyse, üst dropdown'ı da aktif yap
                var dropdownParent = link.closest('.dropdown');
                if (dropdownParent) {
                    var dropdownToggle = dropdownParent.querySelector('.dropdown-toggle');
                    if (dropdownToggle) {
                        dropdownToggle.classList.add('active');
                    }
                }
            }
        });
    }
    
    /**
     * Smooth scroll işlevi
     */
    function setupSmoothScroll() {
        var anchors = document.querySelectorAll('a[href^="#"]:not([href="#"])');
        anchors.forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                var targetId = this.getAttribute('href');
                var targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
    
    // Initialize functions
    setupMobileMenu();
    setupActiveMenuItem();
    setupSmoothScroll();
}); 