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
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark')
        localStorage.theme = 'light'
    } else {
        document.documentElement.classList.add('dark')
        localStorage.theme = 'dark'
    }
    
    // If there's a toggle button with an icon, update it
    const darkIcon = document.querySelector('.dark-mode-toggle .dark-icon');
    const lightIcon = document.querySelector('.dark-mode-toggle .light-icon');
    
    if (darkIcon && lightIcon) {
        if (document.documentElement.classList.contains('dark')) {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        }
    }
}

// When DOM is ready, add event listener to dark mode toggle buttons
document.addEventListener('DOMContentLoaded', function() {
    // Find all dark mode toggles and add click handler
    const darkModeToggles = document.querySelectorAll('.dark-mode-toggle, [onclick*="toggleDarkMode"]');
    darkModeToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleDarkMode();
        });
    });
    
    // Set initial icon state
    const darkIcon = document.querySelector('.dark-mode-toggle .dark-icon');
    const lightIcon = document.querySelector('.dark-mode-toggle .light-icon');
    
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