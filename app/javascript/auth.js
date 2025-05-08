document.addEventListener('DOMContentLoaded', function () {
  const closeRegister = document.getElementById('closeRegister');
  if (closeRegister) {
    closeRegister.addEventListener('click', function () {
      window.location.href = '../pages/index.html'; // strona główna
    });
  }

  const closeLogin = document.getElementById('closeLogin');
  if (closeLogin) {
    closeLogin.addEventListener('click', function () {
      window.location.href = '../pages/index.html'; // strona główna
    });
  }

  // obsługa formularza rejestracji
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function (event) {
      event.preventDefault();
      alert('Zarejestrowano pomyślnie!');
      window.location.href = '../pages/index.html';
    });
  }

  // obsługa formularza logowania
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
      event.preventDefault();
      alert('Zalogowano pomyślnie!');
      window.location.href = '../pages/index.html';
    });
  }
});
