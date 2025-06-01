const modal = document.getElementById("membership-modal");
const btn = document.getElementById("join-btn");
const closeBtn = document.querySelector("#membership-modal .close");
const form = document.getElementById("membership-form");

btn.onclick = function () {
  modal.style.display = "block";
  document.body.classList.add("modal-open");
  document.body.style.top = `-${window.scrollY}px`;
};

closeBtn.onclick = function () {
  closeModal();
};

window.onclick = function (event) {
  if (event.target == modal) {
    closeModal();
  }
};

function closeModal() {
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  const scrollY = document.body.style.top;
  document.body.style.top = "";
  window.scrollTo(0, parseInt(scrollY || "0") * -1);
}

// obsługa formularza
form.addEventListener("submit", function (e) {
  e.preventDefault();

  // Get form data
  const formData = new FormData(form);
  const membershipData = {
    firstname: formData.get("firstname"),
    lastname: formData.get("lastname"),
    email: formData.get("email"),
    idNumber: formData.get("idNumber"),
    membershipType: formData.get("membershipType"),
    terms: formData.get("terms"),
  };

  // Validate form
  if (!membershipData.membershipType || !membershipData.terms) {
    alert("Wszystkie pola są wymagane i musisz zaakceptować regulamin.");
    return;
  }

  // Show loading state
  const submitButton = form.querySelector(".submit-btn");
  const originalText = submitButton.textContent;
  submitButton.textContent = "Przetwarzanie...";
  submitButton.disabled = true;

  // Send membership purchase request
  fetch("../api/purchase_membership.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      membershipType: membershipData.membershipType,
    }),
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        alert(
          `Dziękujemy za zakup członkostwa ${result.membership.type}!\nKoszt: ${result.membership.price} zł\nWażne do: ${result.membership.expiration_date}`,
        );
        closeModal();

        // Refresh page to update user info
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        alert("Błąd: " + result.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Wystąpił błąd podczas zakupu członkostwa. Spróbuj ponownie.");
    })
    .finally(() => {
      // Restore button state
      submitButton.textContent = originalText;
      submitButton.disabled = false;
    });
});

document.getElementById("idNumber").addEventListener("input", function () {
  this.value = this.value.toUpperCase();
});
