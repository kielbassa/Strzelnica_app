document.addEventListener("DOMContentLoaded", function () {
  // Tab switching functionality
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabContents = document.querySelectorAll(".tab-content");

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Get the tab to activate
      const tabToActivate = this.getAttribute("data-tab");

      // Deactivate all tabs
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      // Activate the selected tab
      this.classList.add("active");
      document.getElementById(tabToActivate + "-tab").classList.add("active");

      // Update URL hash for bookmarking
      window.location.hash = tabToActivate;
    });
  });

  // Check if there's a tab specified in the URL hash
  const hash = window.location.hash.substring(1);
  if (hash) {
    const tabButton = document.querySelector(`.tab-button[data-tab="${hash}"]`);
    if (tabButton) {
      tabButton.click();
    }
  }

  // Handle membership renewal
  const renewButtons = document.querySelectorAll(".renew-membership-btn");
  renewButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      // Redirect to store page
      window.location.href = "../pages/store.php#membership";
    });
  });

  // Format dates for better display
  const formatDates = () => {
    const dateElements = document.querySelectorAll(".format-date");
    dateElements.forEach((element) => {
      const dateString = element.textContent.trim();
      if (dateString) {
        try {
          const date = new Date(dateString);
          if (!isNaN(date)) {
            element.textContent = date.toLocaleDateString("pl-PL");
          }
        } catch (e) {
          console.error("Error formatting date:", e);
        }
      }
    });
  };

  formatDates();

  // Handle reservation cancellation
  const cancelButtons = document.querySelectorAll(".cancel-reservation-btn");
  cancelButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      const reservationId = this.getAttribute("data-id");
      const confirmCancel = confirm(
        "Czy na pewno chcesz anulować tę rezerwację?",
      );

      if (confirmCancel && reservationId) {
        // Show loading state
        button.textContent = "Anulowanie...";
        button.disabled = true;

        // Send cancel request to API
        fetch("../api/cancel_reservation.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            reservation_id: reservationId,
          }),
        })
          .then((response) => response.json())
          .then((result) => {
            if (result.success) {
              alert("Rezerwacja została anulowana pomyślnie");
              // Reload page to update reservations list
              window.location.reload();
            } else {
              alert(
                "Błąd: " +
                  (result.message || "Nie udało się anulować rezerwacji"),
              );
              // Restore button state
              button.textContent = "Anuluj";
              button.disabled = false;
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("Wystąpił błąd podczas anulowania rezerwacji");
            // Restore button state
            button.textContent = "Anuluj";
            button.disabled = false;
          });
      }
    });
  });

  // Initialize auth
  if (window.userAuth) {
    window.userAuth.checkSession();
  }
});
