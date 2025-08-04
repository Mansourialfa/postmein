document.addEventListener('DOMContentLoaded', () => {

    /**
     * Une fonction centralisée pour faire des appels à notre API PHP.
     * Elle gère les erreurs réseau et les sessions expirées automatiquement.
     * @param {string} endpoint - Le nom du script à appeler (ex: 'signup', 'get_posts').
     * @param {object} options - Les options pour la requête fetch (method, headers, body).
     * @returns {Promise<Response|null>} La réponse du serveur ou null en cas d'erreur.
     */
    const apiCall = async (endpoint, options = {}) => {
        try {
            // On construit l'URL de l'API, par exemple /api/signup
            const response = await fetch(`/api/${endpoint}`, options);

            // Si l'utilisateur n'est pas connecté, le serveur renvoie 401. On le redirige.
            if (response.status === 401) {
                alert('Session expirée ou non autorisé. Veuillez vous reconnecter.');
                window.location.href = 'index.html';
                return null;
            }
            return response;
        } catch (error) {
            console.error('Erreur Réseau:', error);
            alert('Une erreur réseau est survenue. Vérifiez votre connexion et la console.');
            return null;
        }
    };

    // --- LOGIQUE D'INSCRIPTION (signup.html) ---
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', async (e) => {
            // Annule le comportement par défaut du formulaire (qui est de recharger la page)
            e.preventDefault();

            const res = await apiCall('signup', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    username: document.getElementById('username').value,
                    password: document.getElementById('password').value,
                }),
            });

            // Si la requête a pu être effectuée
            if (res) {
                const result = await res.json();
                alert(result.message);
                if (res.ok) { // Si le statut est 2xx (succès)
                    window.location.href = 'index.html';
                }
            }
        });
    }

    // --- LOGIQUE DE CONNEXION (index.html) ---
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await apiCall('auth', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value
                }),
            });
            
            if (res && res.ok) {
                window.location.href = 'home.html';
            } else if (res) {
                const result = await res.json();
                alert(result.message);
            }
        });
    }

    // --- LOGIQUE DE DÉCONNEXION (home.html) ---
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            const res = await apiCall('logout', { method: 'POST' });
            if (res && res.ok) {
                window.location.href = 'index.html';
            }
        });
    }

    // --- PAGE D'ACCUEIL : AFFICHER LES POSTS ET GÉRER LES LIKES (home.html) ---
    const postsContainer = document.getElementById('posts-container');
    if (postsContainer) {
        const fetchPosts = async () => {
            const res = await apiCall('get_posts');
            if (res && res.ok) {
                const result = await res.json();
                if (result.success && result.data) {
                    renderPosts(result.data);
                } else {
                    postsContainer.innerHTML = `<p>${result.message || "Impossible de charger les posts."}</p>`;
                }
            }
        };

        const renderPosts = (posts) => {
            if (posts.length === 0) {
                postsContainer.innerHTML = "<p>Aucune publication pour le moment. Soyez le premier !</p>";
                return;
            }
            postsContainer.innerHTML = posts.map(post => `
                <div class="post" data-post-id="${post.id}">
                    <div class="post-header">
                        <span class="post-author">${post.username || 'Anonyme'}</span>
                        <span class="post-date">${new Date(post.created_at).toLocaleString('fr-FR')}</span>
                    </div>
                    <div class="post-content"><p>${post.description.replace(/\n/g, '<br>')}</p></div>
                    <div class="post-actions">
                        <button class="like-btn ${post.user_liked ? 'liked' : ''}">
                            <span class="like-icon">❤️</span>
                            <span class="like-count">${post.likes_count}</span>
                        </button>
                        <span class="comment-btn">💬 ${post.comments_count}</span>
                    </div>
                </div>
            `).join('');
        };

        // On écoute les clics sur tout le conteneur des posts (plus efficace)
        postsContainer.addEventListener('click', async (e) => {
            const likeButton = e.target.closest('.like-btn');
            if (likeButton) {
                const postElement = likeButton.closest('.post');
                const postId = postElement.dataset.postId;
                
                // Mise à jour visuelle immédiate pour une meilleure expérience utilisateur
                const isLiked = likeButton.classList.toggle('liked');
                const countElement = likeButton.querySelector('.like-count');
                const currentCount = parseInt(countElement.textContent);
                countElement.textContent = isLiked ? currentCount + 1 : currentCount - 1;

                // On envoie la requête au serveur en arrière-plan
                const res = await apiCall('like_post', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ post_id: postId }),
                });

                // Si l'appel échoue, on annule la mise à jour visuelle
                if (!res || !res.ok) {
                    likeButton.classList.toggle('liked'); // On annule le toggle
                    countElement.textContent = currentCount; // On remet le bon compte
                    alert("Erreur : L'action 'like' n'a pas pu être enregistrée.");
                }
            }
        });

        // On charge les posts au chargement de la page
        fetchPosts();
    }

    // --- LOGIQUE DE CRÉATION DE POST (create-post.html) ---
    const createPostForm = document.getElementById('create-post-form');
    if (createPostForm) {
        createPostForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await apiCall('create_post', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ content: document.getElementById('post-content').value }),
            });
            if (res && res.ok) {
                window.location.href = 'home.html';
            } else if(res) {
                const result = await res.json();
                alert(result.message);
            }
        });
    }
});