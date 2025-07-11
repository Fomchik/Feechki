document.addEventListener('DOMContentLoaded', function() {
    // Получаем элементы модальных окон
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const openLoginButton = document.getElementById('openLoginModal');
    const showRegisterLink = document.getElementById('showRegisterModal');
    const showLoginLink = document.getElementById('showLoginModal');
    const closeBtns = document.querySelectorAll('.close');
    
    console.log('Login Modal:', loginModal);
    console.log('Open Login Button:', openLoginButton);
    
    // Обработчик для открытия модального окна входа
    if (openLoginButton) {
        openLoginButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Login button clicked');
            loginModal.style.display = 'block';
        });
    } else {
        console.error('Open login button not found!');
    }
    
    // Переключение между модальными окнами
    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.style.display = 'none';
            registerModal.style.display = 'block';
        });
    }
    
    if (showLoginLink) {
        showLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            registerModal.style.display = 'none';
            loginModal.style.display = 'block';
        });
    }
    
    // Закрытие модальных окон при клике на крестик
    closeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            loginModal.style.display = 'none';
            registerModal.style.display = 'none';
        });
    });
    
    // Закрытие модальных окон при клике вне окна
    window.addEventListener('click', function(e) {
        if (e.target === loginModal) {
            loginModal.style.display = 'none';
        }
        if (e.target === registerModal) {
            registerModal.style.display = 'none';
        }
    });
    
    // AJAX обработчик для формы входа
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(loginForm);
            
            fetch('includes/auth_handler.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Успешная авторизация
                    window.location.reload();
                } else {
                    // Ошибка авторизации
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при авторизации');
            });
        });
    }
    
    // AJAX обработчик для формы регистрации
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('register_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                alert('Пароли не совпадают!');
                return;
            }
            
            const formData = new FormData(registerForm);
            
            fetch('includes/auth_handler.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Успешная регистрация
                    alert(data.message);
                    window.location.reload();
                } else {
                    // Ошибка регистрации
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при регистрации');
            });
        });
    }
});