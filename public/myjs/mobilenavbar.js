
// Toggle mobile profile menu
function toggleMobileProfileMenu(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const dropdown = document.getElementById('mobileProfileDropdown');
  const overlay = document.getElementById('mobileDropdownOverlay');
  
  
  const isShowing = dropdown.classList.contains('show');
  
  if (isShowing) {
    dropdown.classList.remove('show');
    if (overlay) overlay.classList.remove('show');
  } else {
    dropdown.classList.add('show');
    if (overlay) overlay.classList.add('show');
  }
}

// Close mobile profile menu
function closeMobileProfileMenu() {
  const dropdown = document.getElementById('mobileProfileDropdown');
  const overlay = document.getElementById('mobileDropdownOverlay');
  
  // ✅ FIX: Check if elements exist before accessing
    if (dropdown) dropdown.classList.remove('show');
    if (overlay) overlay.classList.remove('show');
}

// Toggle Dark Mode
function toggleDarkMode(event) {
  event.preventDefault();
  event.stopPropagation();
  
  const toggle = document.getElementById('darkModeToggle');
  const body = document.body;
  
  body.classList.toggle('dark-mode');
  if (toggle) toggle.classList.toggle('active'); // ✅ FIX: Safety check
  
  // Save preference to localStorage
  if (body.classList.contains('dark-mode')) {
    localStorage.setItem('darkMode', 'enabled');
  } else {
    localStorage.setItem('darkMode', 'disabled');
  }
}

// Load dark mode preference on page load
document.addEventListener('DOMContentLoaded', function() {
  const darkModePreference = localStorage.getItem('darkMode');
  const toggle = document.getElementById('darkModeToggle');
  
  if (darkModePreference === 'enabled') {
    document.body.classList.add('dark-mode');
    if (toggle) toggle.classList.add('active');
  }
  
  // Active nav state is handled server-side by Blade (no JS override needed)
  
  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('mobileProfileDropdown');
    const profileWrapper = document.querySelector('.profile-menu-wrapper');
    
    if (dropdown && profileWrapper && !profileWrapper.contains(event.target)) {
      closeMobileProfileMenu();
    }
  });
  
  // ✅ FIX: Allow links in dropdown to work
  document.querySelectorAll('.mobile-dropdown-menu a').forEach(link => {
    link.addEventListener('click', function(e) {
      // Don't stop propagation - let the link work
      // Just close the menu
      closeMobileProfileMenu();
    });
  });
});





// Toggle Plus Dropdown (Desktop)
function togglePlusDropdown() {
    const dropdown = document.getElementById('plusDropdown');
    const overlay = document.getElementById('dropdownOverlay');
    
    
    dropdown.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Toggle Mobile Plus Dropdown
function toggleMobilePlusDropdown() {
    const dropdown = document.getElementById('mobilePlusDropdown');
    const overlay = document.getElementById('dropdownOverlay');
    
    dropdown.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Close All Dropdowns
function closeAllDropdowns() {
    document.querySelectorAll('.plus-dropdown, .mobile-plus-dropdown').forEach(el => {
        el.classList.remove('show');
    });
    document.getElementById('dropdownOverlay').classList.remove('show');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.plus-menu-trigger') && !event.target.closest('.nav-item')) {
        closeAllDropdowns();
    }
});




    
    function toggleAgeAiIcon() {
        const ageAiIcon = document.getElementById('ageAiIcon');
        const plusButton = document.getElementById('plusMenuTrigger');
        
        if (!ageAiIcon || !plusButton) {
            console.error("Missing required elements: ageAiIcon or plusMenuTrigger.");
            return;
        }

        // Toggle the visibility of the AGE AI Icon
        if (ageAiIcon.style.display === 'none') {
            // --- WHEN SHOWING AGE AI ICON (Search is done) ---
            ageAiIcon.style.display = ''; // Revert to default display (e.g., 'block' or 'flex')
            
            // 1. Enable the Plus Button
            plusButton.style.pointerEvents = 'auto'; // Allow clicking
            plusButton.style.opacity = '1';         // Full visibility
            plusButton.classList.remove('disabled'); // Remove disabled class for visual cue
            
        } else {
            // --- WHEN HIDING AGE AI ICON (Search is active) ---
            ageAiIcon.style.display = 'none';
            
            // 1. Disable the Plus Button
            plusButton.style.pointerEvents = 'none'; // Prevent clicking/events
            plusButton.style.opacity = '0.5';        // Visually grey out/fade it
            plusButton.classList.add('disabled');    // Add disabled class
        }
    }

    
    // NOTE: If you are using jQuery, the function would be simpler:
    /*
    function toggleAgeAiIcon() {
        $('#ageAiIcon').toggle();
    }
    */