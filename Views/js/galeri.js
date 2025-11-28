// GALERI.JS - Halaman Galeri Video Global Time

// Profile Dropdown Menu
function toggleProfileMenu(event) {
  event.preventDefault();
  const dropdown = document.querySelector('.profile-dropdown');
  dropdown.classList.toggle('active');
}

// Close profile menu when clicking outside
document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  const profileButton = document.querySelector('.user-profile');
  
  if (dropdown && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});

// Logout function
function logout() {
  if (confirm("Yakin ingin logout?")) {
    window.location.href = "index.php?page=logout";
  }
}

// Global Time Gallery JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Set active navigation
    const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href) {
            const linkPage = href.replace('.php', '');
            
            if (linkPage === currentPage || 
                (currentPage === '' && linkPage === 'home') ||
                (currentPage === 'index' && linkPage === 'home')) {
                link.classList.add('active');
            }
        }
        
        link.addEventListener('click', function() {
            navLinks.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Animasi loading untuk video cards
    const videoCards = document.querySelectorAll('.video-card');
    
    // Intersection Observer untuk animasi saat scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);
    
    // Apply initial styles dan observe cards
    videoCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        cardObserver.observe(card);
    });
    
    // Hover effects untuk video cards
    videoCards.forEach(card => {
        const thumbnail = card.querySelector('.thumbnail');
        const duration = card.querySelector('.duration');
        const img = card.querySelector('.thumbnail img');
        
        card.addEventListener('mouseenter', function() {
            if (duration) {
                duration.style.background = 'rgba(220, 53, 69, 0.9)';
                duration.style.transform = 'scale(1.1)';
            }
            
            if (img) {
                img.style.filter = 'brightness(1.1)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (duration) {
                duration.style.background = 'rgba(0,0,0,0.8)';
                duration.style.transform = 'scale(1)';
            }
            
            if (img) {
                img.style.filter = 'brightness(1)';
            }
        });
    });
    
    // Search functionality
    function setupSearch() {
        const searchInput = document.querySelector('#search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filterVideos(searchTerm);
            });
        }
    }
    
    function filterVideos(searchTerm) {
        videoCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const category = card.querySelector('.category').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || category.includes(searchTerm) || searchTerm === '') {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    }
    
    // Lazy loading untuk images
    const images = document.querySelectorAll('.thumbnail img');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const src = img.getAttribute('src');
                
                if (src) {
                    img.style.opacity = '0';
                    img.onload = () => {
                        img.style.transition = 'opacity 0.3s ease';
                        img.style.opacity = '1';
                    };
                }
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => {
        imageObserver.observe(img);
    });
    
    // Video card click tracking
    videoCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('a')) return;
            
            const title = this.querySelector('h3').textContent;
            const category = this.querySelector('.category').textContent;
            const url = this.querySelector('a').href;
            
            console.log('Video clicked:', {
                title: title,
                category: category,
                url: url,
                timestamp: new Date().toISOString()
            });
            
            window.open(url, '_blank');
        });
    });
    
    // Mobile menu toggle
    function setupMobileMenu() {
        const navToggle = document.querySelector('.nav-toggle');
        const navMenu = document.querySelector('.nav-menu');
        
        if (navToggle && navMenu) {
            navToggle.addEventListener('click', function() {
                navMenu.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (navMenu.classList.contains('show')) {
                    if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                        navMenu.classList.remove('show');
                    }
                }
            });
        }
    }
    
    // Scroll to top button
    function setupScrollToTop() {
        const scrollBtn = document.createElement('button');
        scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollBtn.className = 'scroll-to-top';
        scrollBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        `;
        
        document.body.appendChild(scrollBtn);
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        scrollBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        scrollBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    }
    
    // Format tanggal ke format Indonesia
    function formatDates() {
        const dates = document.querySelectorAll('.date');
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        dates.forEach(dateElement => {
            const dateText = dateElement.textContent;
            try {
                const [day, month, year] = dateText.split('/');
                const monthName = monthNames[parseInt(month) - 1];
                const formattedDate = `${parseInt(day)} ${monthName} ${year}`;
                dateElement.textContent = formattedDate;
            } catch (error) {
                console.log('Date format error:', error);
            }
        });
    }
    
    // Progress bar saat scroll
    function setupScrollProgress() {
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: #dc3545;
            z-index: 1000;
            transition: width 0.3s ease;
        `;
        
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    }
    
    // Video duration formatter
    function formatDuration(duration) {
        const durations = document.querySelectorAll('.duration');
        durations.forEach(dur => {
            dur.style.transition = 'all 0.3s ease';
        });
    }
    
    // Initialize all functions
    setupSearch();
    setupMobileMenu();
    setupScrollToTop();
    formatDates();
    setupScrollProgress();
    formatDuration();
    
    // Loading animation
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 500);
    
    console.log('🎬 Global Time Gallery loaded successfully!');
    console.log(`📹 Found ${videoCards.length} video cards`);
});

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Error handling untuk images
window.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0xNTAgMTAwTDEyNSA3NUwxNzUgNzVMMTUwIDEwMFoiIGZpbGw9IiNEQ0RDREMiLz4KPC9zdmc+';
        e.target.style.filter = 'grayscale(100%)';
    }
}, true);