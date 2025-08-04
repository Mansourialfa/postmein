// FILE: /public/js/app.js (FINAL CORRECTED VERSION)

document.addEventListener('DOMContentLoaded', () => {

    const apiCall = async (endpoint, options = {}) => {
        try {
            const response = await fetch(`/api/${endpoint}`, options);
            if (response.status === 401 && !['/index.html', '/signup.html', '/'].includes(window.location.pathname)) {
                alert('Session expired. Please log in again.');
                window.location.href = '/index.html';
                return null;
            }
            return response;
        } catch (error) {
            console.error('Network Error:', error);
            alert('A network error occurred. Please check your connection.');
            return null;
        }
    };

    // --- SIGN UP LOGIC (runs only on signup.html) ---
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', async (e) => {
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
                alert(result.message);
                if (response.ok) {
                    window.location.href = '/index.html';
                }
            }
            
            submitButton.disabled = false;
            submitButton.textContent = 'Sign Up & Claim Color';
        });
    }

    // --- LOGIN LOGIC (runs only on index.html) ---
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitButton = loginForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Logging In...';

            const response = await apiCall('login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    email: document.getElementById('email').value, 
                    password: document.getElementById('password').value 
                }),
            });
            
            if (response && response.ok) {
                window.location.href = '/profile.html';
            } else if (response) {
                const result = await response.json();
                alert(result.message);
            }

            submitButton.disabled = false;
            submitButton.textContent = 'Log In';
        });
    }
    
    // --- PROFILE PAGE & LOGOUT LOGIC (runs only on profile.html) ---
    const profileContent = document.getElementById('profile-content');
    if (profileContent) {
        const logoutBtn = document.getElementById('logout-btn');
        if(logoutBtn) {
            logoutBtn.addEventListener('click', async () => {
                await apiCall('logout.php', { method: 'POST' });
                window.location.href = '/index.html';
            });
        }

        const loadProfile = async () => {
            const response = await apiCall('get_profile.php');
            if (response && response.ok) {
                const result = await response.json();
                if (result.success) {
                    displayProfile(result.data);
                } else {
                    profileContent.innerHTML = `<p class="error">${result.message}</p>`;
                }
            } else {
                profileContent.innerHTML = `<p class="error">Could not load profile data. You may need to log in again.</p>`;
            }
        };

        const displayProfile = (data) => {
            const { user, color } = data;
            
            if (!color) {
                profileContent.innerHTML = `<h1>Welcome, ${user.username}</h1><p class="error">It seems you don't own a color. Please contact support.</p>`;
                return;
            }

            // Calculate text color based on background luminance for better contrast
            const hex = color.hex_code.replace('#', '');
            const r = parseInt(hex.substring(0, 2), 16);
            const g = parseInt(hex.substring(2, 4), 16);
            const b = parseInt(hex.substring(4, 6), 16);
            const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
            const textColor = luminance > 0.5 ? '#000000' : '#FFFFFF';

            profileContent.innerHTML = `
                <div class="color-display" style="background-color: ${color.hex_code};">
                    <div class="color-overlay" style="color: ${textColor};">
                        <h2 class="color-name">${color.name}</h2>
                        <p class="color-hex">${color.hex_code}</p>
                    </div>
                </div>
                <div class="profile-details">
                    <h3>Owned by: ${user.username}</h3>
                    <p class="color-description">"${color.description}"</p>
                    <div class="user-info">
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Member since:</strong> ${new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    </div>
                    <div class="actions">
                         <button class="btn-primary" disabled>Download Certificate (Soon)</button>
                         <button class="btn-secondary" disabled>List on Marketplace (Soon)</button>
                    </div>
                </div>
            `;
        };

        loadProfile();
    }
});