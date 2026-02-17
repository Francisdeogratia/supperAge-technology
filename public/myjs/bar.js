$(document).ready(function () {
  $('.search').click(function () {
    $('.fb-logo').toggle();
    $('.search-bar').toggle();
  });
  
  $('.notifications').click(function () {
    $('.notify-dropdown').fadeToggle();
    $('.profile-dropdown').hide();
  });
  
  $('.profile').click(function () {
    $('.profile-dropdown').fadeToggle();
    $('.notify-dropdown').hide();
  });
  
  // Hide dropdowns when clicking outside
  $(document).click(function (e) {
    if (!$(e.target).closest('.icon').length) {
      $('.dropdown').hide();
    }
  });
  
  // Load saved mode on page load
  const savedMode = localStorage.getItem("darkMode");
  if (savedMode === "enabled") {
    $('body').addClass("dark-mode");
  }

  $('.dark-toggle').click(function () {
    $('body').toggleClass("dark-mode");
    const isDark = $('body').hasClass("dark-mode");
    localStorage.setItem("darkMode", isDark ? "enabled" : "disabled");
    // Sync mobile toggle switch
    const mobileToggle = document.getElementById('darkModeToggle');
    if (mobileToggle) {
      if (isDark) {
        mobileToggle.classList.add('active');
      } else {
        mobileToggle.classList.remove('active');
      }
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Active icon highlighting
  const icons = document.querySelectorAll('.fb-icons .icon');
  let foundActive = false;
  
  icons.forEach(icon => {
    const link = icon.querySelector('a');
    if (link && link.href.includes(window.location.pathname) && window.location.pathname !== '/') {
      icon.classList.add('active');
      foundActive = true;
    }
  });
  
  // If no matching icon was found, set "Home" as default active
  if (!foundActive) {
    const homeIcon = document.querySelector('.fb-icons .home');
    if (homeIcon) {
      homeIcon.classList.add('active');
    }
  }

  // Scrolling functionality - ONLY DECLARED ONCE
  const scrollContainer = document.querySelector('.scroll-container');
  const leftBtn = document.querySelector('.scroll-btn.left');
  const rightBtn = document.querySelector('.scroll-btn.right');
  
  if (scrollContainer && leftBtn && rightBtn) {
    leftBtn.addEventListener('click', () => {
      scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
    });
    
    rightBtn.addEventListener('click', () => {
      scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
    });
    
    // Swipe gestures for mobile
    let startX = 0;
    
    scrollContainer.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
    });
    
    scrollContainer.addEventListener('touchmove', (e) => {
      if (!startX) return;
      const diffX = startX - e.touches[0].clientX;
      scrollContainer.scrollLeft += diffX;
      startX = e.touches[0].clientX;
    });
    
    scrollContainer.addEventListener('touchend', () => {
      startX = 0;
    });
  }
});