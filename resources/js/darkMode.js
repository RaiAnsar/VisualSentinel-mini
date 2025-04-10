// Check if dark mode is enabled in localStorage or system preference
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
} else {
    document.documentElement.classList.remove('dark')
}

// Listen for system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!('theme' in localStorage)) {
        if (e.matches) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    }
})

// Handle dark mode toggle - works for both authenticated and guest users
window.toggleDarkMode = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.theme = isDark ? 'dark' : 'light';
    
    // Update icons in ALL toggle buttons
    const darkModeToggles = document.querySelectorAll('.dark-mode-toggle');
    darkModeToggles.forEach(toggle => {
        const darkIcon = toggle.querySelector('.dark-icon');
        const lightIcon = toggle.querySelector('.light-icon');
        
        if (darkIcon && lightIcon) {
            if (isDark) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }
    });
}

// When DOM is ready, add event listener to dark mode toggle buttons
document.addEventListener('DOMContentLoaded', function() {
    // Find all dark mode toggles and add click handler
    const darkModeToggles = document.querySelectorAll('.dark-mode-toggle');
    darkModeToggles.forEach(toggle => {
        // Remove any existing onclick attribute to avoid double toggling
        toggle.removeAttribute('onclick'); 
        
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleDarkMode();
        });
        
        // Set initial icon state for each toggle
        const darkIcon = toggle.querySelector('.dark-icon');
        const lightIcon = toggle.querySelector('.light-icon');
        
        if (darkIcon && lightIcon) {
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }
    });
}); 