                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white py-3 mt-auto border-top">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-muted">
                        <small>© <?php echo date('Y'); ?> Kurumsal Web Sitesi - Tüm Hakları Saklıdır</small>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Admin Panel v1.0</small>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Admin Panel JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('adminSidebar');
                const mainContent = document.getElementById('mainContent');
                const topBar = document.getElementById('topBar');
                const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
                const closeSidebarBtn = document.getElementById('closeSidebarBtn');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                
                // Function to check window size and set appropriate classes
                function checkWindowSize() {
                    if (window.innerWidth < 768) {
                        // Mobile view
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                        topBar.classList.remove('expanded');
                    }
                }
                
                // Toggle sidebar on button click
                sidebarToggleBtn.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        // On mobile, show sidebar with overlay
                        sidebar.classList.add('mobile-visible');
                        sidebarOverlay.classList.add('active');
                        document.body.style.overflow = 'hidden'; // Prevent scrolling
                    } else {
                        // On desktop, collapse/expand sidebar
                        sidebar.classList.toggle('collapsed');
                        mainContent.classList.toggle('expanded');
                        topBar.classList.toggle('expanded');
                    }
                });
                
                // Close sidebar on mobile
                closeSidebarBtn.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-visible');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
                
                // Close sidebar when clicking overlay
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-visible');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
                
                // Initialize on page load
                checkWindowSize();
                
                // Check window size on resize
                window.addEventListener('resize', checkWindowSize);
                
                // Add active class to current page in sidebar
                const currentPageUrl = window.location.pathname;
                const navLinks = document.querySelectorAll('#adminMainMenu .nav-link');
                
                navLinks.forEach(link => {
                    const linkPath = link.getAttribute('href');
                    if (currentPageUrl.endsWith(linkPath)) {
                        link.classList.add('active');
                    }
                });
                
                // Initialize tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                
                // Initialize popovers
                const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });
            });
        </script>
        
        <!-- Önbellek temizleme ve renk şeması güncellemesi için yardımcı script -->
        <script>
            // Sayfa yüklendiğinde
            document.addEventListener('DOMContentLoaded', function() {
                // URL'de güncellenmiş parametre var mı kontrol et
                if (new URLSearchParams(window.location.search).has('updated')) {
                    console.log('Güncellenmiş içerik algılandı, tarayıcı önbelleğini temizleme...');
                    
                    // localStorage'a zaman damgası ekle
                    localStorage.setItem('lastUpdate', Date.now());
                    
                    // Service Worker varsa güncelle
                    if ('serviceWorker' in navigator) {
                        navigator.serviceWorker.getRegistrations().then(function(registrations) {
                            for (let registration of registrations) {
                                registration.update();
                            }
                        });
                    }
                    
                    // Önbellek silme tarayıcı API'si varsa kullan
                    if ('caches' in window) {
                        caches.keys().then(function(names) {
                            for (let name of names) {
                                caches.delete(name);
                            }
                        });
                    }
                }
            });
        </script>
    </body>
</html>