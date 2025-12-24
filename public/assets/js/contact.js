// Contact form validation
// Note: This currently provides client-side validation only.
// To enable actual form submission, update the controller to handle POST requests.
(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                if (form.checkValidity()) {
                    // Show success message
                    const successMessage = document.getElementById('successMessage');
                    if (successMessage) {
                        successMessage.style.display = 'block';
                        
                        // Reset form
                        form.reset();
                        form.classList.remove('was-validated');
                        
                        // Scroll to success message
                        successMessage.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        
                        // Hide success message after 10 seconds
                        setTimeout(function() {
                            successMessage.style.display = 'none';
                        }, 10000);
                    }
                } else {
                    form.classList.add('was-validated');
                }
            }, false);
        }
    });
})();
