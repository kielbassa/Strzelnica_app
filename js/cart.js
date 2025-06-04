document.addEventListener('DOMContentLoaded', function () {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const checkoutBtn = document.querySelector('.checkout-btn');
    const emptyMessage = document.getElementById('empty-cart-message');

    function updateCartDisplay() {
        cartItems.innerHTML = '';

        if (cart.length === 0) {
            const emptyMsg = document.getElementById('empty-cart-message') || emptyMessage;
            if (emptyMsg) emptyMsg.style.display = 'block';
            cartTotal.textContent = '0.00 zł';
            return;
        }

        const emptyMsg = document.getElementById('empty-cart-message') || emptyMessage;
        if (emptyMsg) emptyMsg.style.display = 'none';

        cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';

            cartItem.innerHTML = `
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-details">${item.price.toFixed(2)} zł/szt. ${item.maxAmount ? `(max: ${item.maxAmount})` : ''}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn decrease-quantity">-</button>
                    <span class="quantity-value">${item.quantity}</span>
                    <button class="quantity-btn increase-quantity" ${item.maxAmount && item.quantity >= item.maxAmount ? 'disabled' : ''}>+</button>
                </div>
                <div class="cart-item-total">${(item.price * item.quantity).toFixed(2)} zł</div>
                <button class="remove-item">×</button>
            `;

            cartItem.querySelector('.decrease-quantity').addEventListener('click', function () {
                decreaseQuantity(item.id);
            });

            cartItem.querySelector('.increase-quantity').addEventListener('click', function () {
                increaseQuantity(item.id);
            });

            cartItem.querySelector('.remove-item').addEventListener('click', function () {
                removeFromCart(item.id);
            });

            cartItems.appendChild(cartItem);
        });

        cartTotal.textContent = `${calculateTotal()} zł`;
    }

    function calculateTotal() {
        return cart.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
    }

    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function increaseQuantity(id) {
        const item = cart.find(i => i.id === id);
        if (item) {
            if (item.maxAmount && item.quantity >= item.maxAmount) {
                alert(`Maksymalna dostępna ilość: ${item.maxAmount}`);
                return;
            }
            item.quantity++;
            saveCart();
            updateCartDisplay();
        }
    }

    function decreaseQuantity(id) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.quantity--;
            if (item.quantity <= 0) {
                removeFromCart(id);
            } else {
                saveCart();
                updateCartDisplay();
            }
        }
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        saveCart();
        updateCartDisplay();
    }

    checkoutBtn.addEventListener('click', function () {
        if (cart.length === 0) {
            alert('Twój koszyk jest pusty.');
            return;
        }

        // Check if user is logged in
        if (!window.isLoggedIn || !window.isLoggedIn()) {
            alert('Musisz być zalogowany, aby dokonać zakupu.');
            window.location.href = '../pages/login.php';
            return;
        }

        // Show loading state
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Przetwarzanie...';

        // Send purchase request to server
        fetch('../api/purchase_ammunition.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                cart: cart
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Show success message with purchase details
                let detailsText = `Dziękujemy za zamówienie!\n\nSzczegóły zakupu:\n`;
                result.purchase.items.forEach(item => {
                    detailsText += `${item.name}: ${item.quantity} szt. × ${item.price_per_unit.toFixed(2)} zł = ${item.total.toFixed(2)} zł\n`;
                });
                detailsText += `\nCałkowita kwota: ${result.purchase.total_amount.toFixed(2)} zł`;
                
                alert(detailsText);

                // Clear cart
                cart = [];
                saveCart();
                updateCartDisplay();

                // Update stock display if on store page
                if (typeof window.refreshAmmunitionData === 'function') {
                    window.refreshAmmunitionData();
                }
            } else {
                alert('Błąd podczas zakupu: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error during purchase:', error);
            alert('Wystąpił błąd podczas przetwarzania zakupu. Spróbuj ponownie.');
        })
        .finally(() => {
            // Restore button state
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'Kup teraz';
        });
    });

    updateCartDisplay();

    // Add empty cart message element if it doesn't exist
    if (!document.getElementById('empty-cart-message')) {
        const emptyMessage = document.createElement('div');
        emptyMessage.id = 'empty-cart-message';
        emptyMessage.style.cssText = `
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
            display: none;
        `;
        emptyMessage.innerHTML = `
            <h3>Twój koszyk jest pusty</h3>
            <p>Dodaj produkty do koszyka, aby móc dokonać zakupu.</p>
            <a href="../pages/store.php" style="color: red; text-decoration: none; font-weight: bold;">Przejdź do sklepu</a>
        `;
        cartItems.parentNode.insertBefore(emptyMessage, cartItems);
    }
});

/* F12 console 
localStorage.setItem('cart', JSON.stringify([
  {
    id: 1,
    name: '.357 Magnum',
    price: 1.50,
    quantity: 3
  },
  {
    id: 2,
    name: '7.62x39 mm',
    price: 2.00,
    quantity: 2
  }
]));
*/