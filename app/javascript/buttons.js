document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('.img-container');
  
    containers.forEach(container => {
      const btn = container.querySelector('.hover-btn');
      const overlay = container.querySelector('.overlay');
  
      container.addEventListener('mouseenter', () => {
        btn.style.color = 'red';
        btn.style.borderColor = 'red';
        btn.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        overlay.style.opacity = '0.3';
      });
  
      container.addEventListener('mouseleave', () => {
        btn.style.color = 'white';
        btn.style.borderColor = 'white';
        btn.style.backgroundColor = 'transparent';
        overlay.style.opacity = '0';
      });
    });
  });
  