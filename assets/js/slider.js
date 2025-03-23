/**
 * Slider JavaScript Functions
 * Handles slider behavior and animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap Carousel
    const carouselElement = document.querySelector('#mainCarousel');
    
    if (carouselElement) {
        // Get carousel instance
        const carousel = new bootstrap.Carousel(carouselElement, {
            interval: 5000,
            pause: 'hover',
            wrap: true,
            keyboard: true
        });
        
        // Add swipe support for touch devices
        let touchStartX = 0;
        let touchEndX = 0;
        
        carouselElement.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        carouselElement.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                // Swipe left, go to next slide
                carousel.next();
            } else if (touchEndX > touchStartX + 50) {
                // Swipe right, go to previous slide
                carousel.prev();
            }
        }
        
        // Add animation classes to carousel items when they become active
        carouselElement.addEventListener('slide.bs.carousel', function(event) {
            // Remove animation classes from previous slide
            const previousSlide = event.relatedTarget;
            const elements = previousSlide.querySelectorAll('[class*="animate__"]');
            
            elements.forEach(element => {
                const animationClasses = Array.from(element.classList)
                    .filter(className => className.startsWith('animate__'));
                
                element.classList.remove(...animationClasses);
            });
        });
        
        carouselElement.addEventListener('slid.bs.carousel', function(event) {
            // Add animation classes to current slide
            const currentSlide = event.relatedTarget;
            const heading = currentSlide.querySelector('h2');
            const paragraph = currentSlide.querySelector('p');
            const button = currentSlide.querySelector('.btn');
            
            if (heading) {
                heading.classList.add('animate__animated', 'animate__fadeInDown');
            }
            
            if (paragraph) {
                paragraph.classList.add('animate__animated', 'animate__fadeInUp');
                paragraph.style.animationDelay = '0.3s';
            }
            
            if (button) {
                button.classList.add('animate__animated', 'animate__fadeInUp');
                button.style.animationDelay = '0.6s';
            }
        });
        
        // Trigger animation for first slide on page load
        const firstSlide = carouselElement.querySelector('.carousel-item.active');
        const heading = firstSlide.querySelector('h2');
        const paragraph = firstSlide.querySelector('p');
        const button = firstSlide.querySelector('.btn');
        
        if (heading) {
            heading.classList.add('animate__animated', 'animate__fadeInDown');
        }
        
        if (paragraph) {
            paragraph.classList.add('animate__animated', 'animate__fadeInUp');
            paragraph.style.animationDelay = '0.3s';
        }
        
        if (button) {
            button.classList.add('animate__animated', 'animate__fadeInUp');
            button.style.animationDelay = '0.6s';
        }
    }
}); 