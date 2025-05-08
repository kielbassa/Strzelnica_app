const modal = document.getElementById("membership-modal");
const btn = document.getElementById("join-btn");
const closeBtn = document.querySelector(".close");
const form = document.getElementById("membership-form");

btn.onclick = function() {
  modal.style.display = "block";
  document.body.classList.add("modal-open");
  document.body.style.top = `-${window.scrollY}px`;
}

closeBtn.onclick = function() {
  closeModal();
}

window.onclick = function(event) {
  if (event.target == modal) {
    closeModal();
  }
}

function closeModal() {
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
  const scrollY = document.body.style.top;
  document.body.style.top = '';
  window.scrollTo(0, parseInt(scrollY || '0') * -1);
}

// obsługa formularza
form.addEventListener("submit", function(e) {
  e.preventDefault();
  // kod do przetwarzania formularza
  


  // wyświetlenie komunikatu o sukcesie
  alert("Dziękujemy za zapisanie się do Klubu Strzeleckiego!");
  closeModal();
});

document.getElementById("idNumber").addEventListener("input", function() {
  this.value = this.value.toUpperCase();
});