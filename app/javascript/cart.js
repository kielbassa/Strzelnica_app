document.addEventListener('DOMContentLoaded', function () {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const checkoutBtn = document.querySelector('.checkout-btn');
    const emptyMessage = document.getElementById('empty-cart-message');

    function updateCartDisplay() {
        cartItems.innerHTML = '';

        if (cart.length === 0) {
            emptyMessage.style.display = 'block';
            cartTotal.textContent = '0.00 zł';
            return;
        }

        emptyMessage.style.display = 'none';

        cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';

            cartItem.innerHTML = `
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-details">${item.price.toFixed(2)} zł/szt.</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn decrease-quantity">-</button>
                    <span class="quantity-value">${item.quantity}</span>
                    <button class="quantity-btn increase-quantity">+</button>
                </div>
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

        alert(`Dziękujemy za zamówienie!\nCałkowita kwota: ${calculateTotal()} zł`);
        cart = [];
        saveCart();
        updateCartDisplay();
    });

    updateCartDisplay();
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