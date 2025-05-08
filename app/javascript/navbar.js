document.addEventListener("DOMContentLoaded", function() {
    const currentUrl = window.location.pathname;
    
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
      if (link.href === window.location.href) {
        link.classList.add('active');
      }
    });
  });