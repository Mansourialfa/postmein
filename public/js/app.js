// FILE: /public/js/app.js

document.addEventListener('DOMContentLoaded', () => {

    /**
     * A central function to make API calls to our PHP backend.
     * @param {string} endpoint - The PHP file to call (e.g., 'signup', 'login').
     * @param {object} options - Options for the fetch request (method, headers, body).
     * @returns {Promise<Response|null>} The server's response or null on network error.
     */
    const apiCall = async (endpoint, options = {}) => {
        try {
            const response = await fetch(`/api/${endpoint}`, options);
            return response;
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred. Please check your connection and the console.');
            return null;
        }
    };

    // --- SIGN UP LOGIC (for signup.html) ---
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', async (e) => {
            // Prevent the form from reloading the page
            e.preventDefault(); 
            const submitButton = signupForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Claiming your color...';

            const response = await apiCall('signup.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    username: document.getElementById('username').value,
                    password: document.getElementById('password').value,
                }),
            });

            if (response) {
                const result = await response.json();
                alert(result.message); // Show success or error message from the server
                if (response.ok) {
                    window.location.href = '/index.html'; // Redirect to login page on success
                }
            }
            
            submitButton.disabled = false;
            submitButton.textContent = 'Sign Up & Claim Color';
        });
    }

    // --- LOGIN LOGIC (for index.html) will be added here in the next step ---
});