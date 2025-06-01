// User Authentication JavaScript for Strzelnica App
class UserAuth {
    constructor() {
        this.user = null;
        this.isAuthenticated = false;
        this.checkInterval = null;
        this.init();
    }

    init() {
        this.checkSession();
        this.startSessionCheck();
        this.setupLogoutHandlers();
    }

    async checkSession() {
        try {
            const response = await fetch('../api/check_session.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success && result.authenticated) {
                this.user = result.user;
                this.isAuthenticated = true;
                this.updateUI();
            } else {
                this.user = null;
                this.isAuthenticated = false;
                this.updateUI();
                
                if (result.message && result.message.includes('wygasła')) {
                    this.showMessage('error', result.message);
                }
            }
        } catch (error) {
            console.error('Error checking session:', error);
            this.isAuthenticated = false;
            this.updateUI();
        }
    }

    startSessionCheck() {
        // Check session every 5 minutes
        this.checkInterval = setInterval(() => {
            this.checkSession();
        }, 300000);
    }

    stopSessionCheck() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
            this.checkInterval = null;
        }
    }

    async logout() {
        try {
            const response = await fetch('../api/logout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.user = null;
                this.isAuthenticated = false;
                this.stopSessionCheck();
                this.showMessage('success', result.message);
                
                // Redirect to home page after short delay
                setTimeout(() => {
                    window.location.href = '../pages/index.php';
                }, 1500);
            } else {
                this.showMessage('error', result.message);
            }
        } catch (error) {
            console.error('Error during logout:', error);
            this.showMessage('error', 'Wystąpił błąd podczas wylogowywania');
        }
    }

    setupLogoutHandlers() {
        // Global logout function
        window.logout = () => {
            this.logout();
        };

        // Handle logout buttons dynamically
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('logout-btn') || e.target.id === 'logoutBtn') {
                e.preventDefault();
                this.logout();
            }
        });
    }

    updateUI() {
        this.updateAuthButtons();
        this.updateUserInfo();
        this.updateNavigationMenu();
    }

    updateAuthButtons() {
        const authContainer = document.getElementById('authContainer');
        if (!authContainer) return;

        if (this.isAuthenticated && this.user) {
            authContainer.innerHTML = `
                <div class="user-info">
                    <span class="welcome-text">Witaj, ${this.user.first_name}!</span>
                    <button class="logout-btn" onclick="logout()">Wyloguj</button>
                </div>
            `;
        } else {
            authContainer.innerHTML = `
                <div class="auth-buttons">
                    <a href="../pages/login.php" class="login-btn">Zaloguj się</a>
                    <a href="../pages/register.php" class="register-btn">Zarejestruj się</a>
                </div>
            `;
        }
    }

    updateUserInfo() {
        const userInfoElements = document.querySelectorAll('.user-display');
        userInfoElements.forEach(element => {
            if (this.isAuthenticated && this.user) {
                element.textContent = this.user.full_name;
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        });
    }

    updateNavigationMenu() {
        // Show/hide menu items based on authentication status
        const authRequiredItems = document.querySelectorAll('.auth-required');
        const guestOnlyItems = document.querySelectorAll('.guest-only');

        authRequiredItems.forEach(item => {
            item.style.display = this.isAuthenticated ? 'block' : 'none';
        });

        guestOnlyItems.forEach(item => {
            item.style.display = this.isAuthenticated ? 'none' : 'block';
        });
    }

    requireAuth() {
        if (!this.isAuthenticated) {
            this.showMessage('error', 'Musisz być zalogowany, aby uzyskać dostęp do tej strony');
            setTimeout(() => {
                window.location.href = '../pages/login.php';
            }, 2000);
            return false;
        }
        return true;
    }

    preventAuthAccess() {
        if (this.isAuthenticated) {
            window.location.href = '../pages/index.php';
            return false;
        }
        return true;
    }

    showMessage(type, message) {
        // Try to find existing message containers
        let messageContainer = document.getElementById('flashMessage');
        
        if (!messageContainer) {
            // Create message container if it doesn't exist
            messageContainer = document.createElement('div');
            messageContainer.id = 'flashMessage';
            messageContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                max-width: 300px;
                padding: 15px;
                border-radius: 5px;
                font-weight: bold;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            `;
            document.body.appendChild(messageContainer);
        }

        // Set message style based on type
        const isError = type === 'error';
        messageContainer.style.backgroundColor = isError ? '#ffe6e6' : '#e6ffe6';
        messageContainer.style.color = isError ? '#d8000c' : '#4f8a10';
        messageContainer.style.border = `1px solid ${isError ? '#d8000c' : '#4f8a10'}`;
        
        messageContainer.textContent = message;
        messageContainer.style.display = 'block';

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (messageContainer) {
                messageContainer.style.display = 'none';
            }
        }, 5000);
    }

    getUser() {
        return this.user;
    }

    getUserId() {
        return this.user ? this.user.id : null;
    }

    getUserEmail() {
        return this.user ? this.user.email : null;
    }

    getUserFullName() {
        return this.user ? this.user.full_name : null;
    }

    isUserLoggedIn() {
        return this.isAuthenticated;
    }
}

// Initialize UserAuth when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.userAuth = new UserAuth();
});

// Global convenience functions
window.requireAuth = function() {
    return window.userAuth ? window.userAuth.requireAuth() : false;
};

window.preventAuthAccess = function() {
    return window.userAuth ? window.userAuth.preventAuthAccess() : true;
};

window.isLoggedIn = function() {
    return window.userAuth ? window.userAuth.isUserLoggedIn() : false;
};

window.getCurrentUser = function() {
    return window.userAuth ? window.userAuth.getUser() : null;
};