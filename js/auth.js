document.addEventListener("DOMContentLoaded", function () {
  const closeRegister = document.getElementById("closeRegister");
  if (closeRegister) {
    closeRegister.addEventListener("click", function () {
      window.location.href = "../pages/index.php"; // strona główna
    });
  }

  const closeLogin = document.getElementById("closeLogin");
  if (closeLogin) {
    closeLogin.addEventListener("click", function () {
      window.location.href = "../pages/index.php"; // strona główna
    });
  }

  // obsługa formularza rejestracji
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
      event.preventDefault();

      // Pobierz dane z formularza
      const formData = new FormData(registerForm);
      const data = {
        firstName: formData.get("firstName"),
        lastName: formData.get("lastName"),
        email: formData.get("email"),
        password: formData.get("password"),
        confirmPassword: formData.get("confirmPassword"),
      };

      // Wyślij żądanie AJAX
      fetch("../api/register.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then((response) => response.json())
        .then((result) => {
          const errorDiv = document.getElementById("errorMessage");
          const successDiv = document.getElementById("successMessage");

          if (result.success) {
            errorDiv.style.display = "none";
            successDiv.textContent = result.message;
            successDiv.style.display = "block";

            // Redirect to login page after 2 seconds
            setTimeout(() => {
              window.location.href = "../pages/login.php";
            }, 2000);
          } else {
            successDiv.style.display = "none";
            errorDiv.textContent = result.message;
            errorDiv.style.display = "block";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          const errorDiv = document.getElementById("errorMessage");
          const successDiv = document.getElementById("successMessage");

          successDiv.style.display = "none";
          errorDiv.textContent =
            "Wystąpił błąd podczas rejestracji. Spróbuj ponownie.";
          errorDiv.style.display = "block";
        });
    });
  }

  // obsługa formularza logowania
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      event.preventDefault();

      // Pobierz dane z formularza
      const formData = new FormData(loginForm);
      const data = {
        email: formData.get("email"),
        password: formData.get("password"),
      };

      // Wyślij żądanie AJAX
      fetch("../api/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then((response) => response.json())
        .then((result) => {
          const errorDiv = document.getElementById("errorMessage");
          const successDiv = document.getElementById("successMessage");

          if (result.success) {
            errorDiv.style.display = "none";
            successDiv.textContent = result.message;
            successDiv.style.display = "block";

            // Redirect to main page after 1 second
            setTimeout(() => {
              window.location.href = "../pages/index.php";
            }, 1000);
          } else {
            successDiv.style.display = "none";
            errorDiv.textContent = result.message;
            errorDiv.style.display = "block";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          const errorDiv = document.getElementById("errorMessage");
          const successDiv = document.getElementById("successMessage");

          successDiv.style.display = "none";
          errorDiv.textContent =
            "Wystąpił błąd podczas logowania. Spróbuj ponownie.";
          errorDiv.style.display = "block";
        });
    });
  }
});
