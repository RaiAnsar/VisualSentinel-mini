// Handle password toggles on login and registration forms
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility on login and registration page (password field)
    const togglePasswordBtn = document.getElementById('togglePassword');
    if (togglePasswordBtn) {
        const passwordInput = document.getElementById('password');
        
        togglePasswordBtn.addEventListener('click', function() {
            // Toggle the password input type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye icons
            togglePasswordBtn.querySelectorAll('svg').forEach(icon => {
                icon.classList.toggle('hidden');
            });
        });
    }
    
    // Toggle password visibility on registration page (confirm password field)
    const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
    if (toggleConfirmPasswordBtn) {
        const confirmPasswordInput = document.getElementById('password_confirmation');
        
        toggleConfirmPasswordBtn.addEventListener('click', function() {
            // Toggle the confirm password input type
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            // Toggle the eye icons
            toggleConfirmPasswordBtn.querySelectorAll('svg').forEach(icon => {
                icon.classList.toggle('hidden');
            });
        });
    }
}); 