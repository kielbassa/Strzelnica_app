document.addEventListener("DOMContentLoaded", function () {
  let ammunitionData = {};
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Load ammunition data from the database
  loadAmmunition();

  function loadAmmunition() {
    fetch("../api/get_ammunition.php")
      .then((response) => response.json())
      .then((result) => {
        if (result.success) {
          ammunitionData = result.ammunition;
          displayAmmunition();
        } else {
          showError("Błąd podczas ładowania amunicji: " + result.message);
        }
      })
      .catch((error) => {
        console.error("Error loading ammunition:", error);
        showError("Wystąpił błąd podczas ładowania amunicji");
      });
  }

  function displayAmmunition() {
    const loadingDiv = document.getElementById("loading-ammunition");
    const table = document.getElementById("ammunition-table");
    const tbody = document.getElementById("ammunition-tbody");

    if (!tbody) {
      showError("Nie można znaleźć tabeli amunicji");
      return;
    }

    loadingDiv.style.display = "none";
    table.style.display = "table";

    tbody.innerHTML = "";

    // Display ammunition by category
    Object.keys(ammunitionData).forEach((category) => {
      if (ammunitionData[category].length > 0) {
        // category header row
        const categoryRow = document.createElement("tr");
        categoryRow.className = "category-row";
        categoryRow.innerHTML = `<td colspan="6">${category}</td>`;
        tbody.appendChild(categoryRow);

        // Add ammunition items for this category
        ammunitionData[category].forEach((ammo) => {
          const row = document.createElement("tr");
          row.innerHTML = `
                        <td>${ammo.gun_name}</td>
                        <td>${ammo.ammo_name}</td>
                        <td>${ammo.price.toFixed(2)} zł</td>
                        <td class="stock-amount" data-ammo-id="${ammo.id}">${ammo.amount}</td>
                        <td>
                            <input type="number" min="1" max="${ammo.amount}" value="1"
                                   class="quantity-input" data-ammo-id="${ammo.id}"
                                   style="width: 60px; padding: 5px; text-align: center;">
                        </td>
                        <td>
                            <button class="add-to-cart-btn"
                                    data-ammo-id="${ammo.id}"
                                    data-ammo-name="${ammo.ammo_name}"
                                    data-gun-name="${ammo.gun_name}"
                                    data-price="${ammo.price}"
                                    data-max-amount="${ammo.amount}"
                                    ${ammo.amount === 0 || !ammo.gun_available ? "disabled" : ""}>
                                ${ammo.amount === 0 ? "Brak w magazynie" : "Dodaj do koszyka"}
                            </button>
                        </td>
                    `;
          tbody.appendChild(row);
        });
      }
    });

    // Add event listeners to add to cart buttons
    setupCartButtons();
  }

  function setupCartButtons() {
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

    addToCartButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const ammoId = parseInt(this.getAttribute("data-ammo-id"));
        const ammoName = this.getAttribute("data-ammo-name");
        const gunName = this.getAttribute("data-gun-name");
        const price = parseFloat(this.getAttribute("data-price"));
        const maxAmount = parseInt(this.getAttribute("data-max-amount"));

        const quantityInput = document.querySelector(
          `.quantity-input[data-ammo-id="${ammoId}"]`,
        );
        const quantity = parseInt(quantityInput.value) || 1;

        if (quantity > maxAmount) {
          alert(`Maksymalna dostępna ilość: ${maxAmount}`);
          quantityInput.value = maxAmount;
          return;
        }

        addToCart(
          ammoId,
          `${gunName} - ${ammoName}`,
          price,
          quantity,
          maxAmount,
        );
      });
    });

    // Add event listeners to quantity inputs
    const quantityInputs = document.querySelectorAll(".quantity-input");
    quantityInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const max = parseInt(this.getAttribute("max"));
        const value = parseInt(this.value);

        if (value > max) {
          this.value = max;
          alert(`Maksymalna dostępna ilość: ${max}`);
        } else if (value < 1) {
          this.value = 1;
        }
      });
    });
  }

  function addToCart(id, name, price, quantity, maxAmount) {
    // Check if item already exists in cart
    const existingItemIndex = cart.findIndex((item) => item.id === id);

    if (existingItemIndex > -1) {
      const newQuantity = cart[existingItemIndex].quantity + quantity;

      if (newQuantity > maxAmount) {
        alert(
          `Nie można dodać więcej. Maksymalna dostępna ilość: ${maxAmount}`,
        );
        return;
      }

      cart[existingItemIndex].quantity = newQuantity;
    } else {
      cart.push({
        id: id,
        name: name,
        price: price,
        quantity: quantity,
        maxAmount: maxAmount,
      });
    }

    // Save cart to localStorage
    localStorage.setItem("cart", JSON.stringify(cart));

    // Show success message
    showSuccess(`Dodano ${quantity} szt. "${name}" do koszyka`);

    // Update cart display if on cart page
    if (typeof updateCartDisplay === "function") {
      updateCartDisplay();
    }
  }

  function showError(message) {
    const loadingDiv = document.getElementById("loading-ammunition");
    loadingDiv.innerHTML = `<div style="color: red; font-weight: bold;">${message}</div>`;
  }

  function showSuccess(message) {
    // Create or update success message element
    let successElement = document.getElementById("success-message");

    if (!successElement) {
      successElement = document.createElement("div");
      successElement.id = "success-message";
      successElement.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                padding: 15px;
                z-index: 10000;
                max-width: 300px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            `;
      document.body.appendChild(successElement);
    }

    successElement.textContent = message;
    successElement.style.display = "block";

    // Auto-hide after 3 seconds
    setTimeout(() => {
      successElement.style.display = "none";
    }, 3000);
  }

  window.refreshAmmunitionData = function () {
    loadAmmunition();
  };

  // Function to get current cart
  window.getCurrentCart = function () {
    return cart;
  };

  // Function to update stock display after purchase
  window.updateStockDisplay = function (ammoId, newAmount) {
    const stockElement = document.querySelector(
      `.stock-amount[data-ammo-id="${ammoId}"]`,
    );
    const quantityInput = document.querySelector(
      `.quantity-input[data-ammo-id="${ammoId}"]`,
    );
    const addButton = document.querySelector(
      `.add-to-cart-btn[data-ammo-id="${ammoId}"]`,
    );

    if (stockElement) {
      stockElement.textContent = newAmount;
    }

    if (quantityInput) {
      quantityInput.setAttribute("max", newAmount);
      if (parseInt(quantityInput.value) > newAmount) {
        quantityInput.value = Math.max(1, newAmount);
      }
      quantityInput.disabled = newAmount === 0;
    }

    if (addButton) {
      addButton.setAttribute("data-max-amount", newAmount);
      if (newAmount === 0) {
        addButton.disabled = true;
        addButton.textContent = "Brak w magazynie";
      } else {
        addButton.disabled = false;
        addButton.textContent = "Dodaj do koszyka";
      }
    }
  };
});
