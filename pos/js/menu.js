document.addEventListener('DOMContentLoaded', function() {
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');
    
    // Function to toggle sidebar
    function toggleSidebar() {
        hamburgerMenu.classList.toggle('active');
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        mainContent.classList.toggle('sidebar-active');
        
        // Toggle body scroll
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        
        // Prevent content shift when scrollbar disappears
        if (sidebar.classList.contains('active')) {
            document.body.style.paddingRight = window.innerWidth - document.documentElement.clientWidth + 'px';
        } else {
            document.body.style.paddingRight = '';
        }
    }
    
    // Event listeners
    hamburgerMenu.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);
    
    // Close sidebar when clicking a link
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });
    
    // Handle resize events
    function handleResize() {
        if (window.innerWidth > 768) {
            // Reset everything on desktop view
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            hamburgerMenu.classList.remove('active');
            mainContent.classList.remove('sidebar-active');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }
    
    window.addEventListener('resize', handleResize);
    
    // Initialize on load
    handleResize();
}); 