document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.getElementById('reservation-form');
    
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // No need to check login status here since the form is only displayed to logged-in users
            
            // Get form data
            const formData = new FormData(reservationForm);
            const data = {
                date: formData.get('date'),
                time: formData.get('time'),
                people: formData.get('people'),
                instructor: formData.get('instructor'),
                group: formData.get('group')
            };
            
            // Validate form data
            if (!data.date || !data.time || !data.people || !data.instructor || !data.group) {
                showMessage('error', 'Wszystkie pola formularza są wymagane');
                return;
            }
            
            // Additional validation
            if (new Date(data.date) < new Date().setHours(0,0,0,0)) {
                showMessage('error', 'Nie można dokonać rezerwacji na datę z przeszłości');
                return;
            }
            
            // Show loading state
            const submitButton = reservationForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Przetwarzanie...';
            submitButton.disabled = true;
            
            // Send reservation request
            fetch('../api/reserve.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showMessage('success', result.message);
                    
                    // Show confirmation details
                    const confirmationHTML = `
                        <div class="reservation-confirmation" style="margin-top: 20px; padding: 15px; background-color: rgba(0, 0, 0, 0.7); border-radius: 10px;">
                            <h3 style="color: #4CAF50; text-align: center;">Rezerwacja została potwierdzona!</h3>
                            <p><strong>Data:</strong> ${result.reservation.date}</p>
                            <p><strong>Godzina:</strong> ${result.reservation.time}</p>
                            <p><strong>Liczba osób:</strong> ${result.reservation.people}</p>
                            <p><strong>Instruktor:</strong> ${result.reservation.instructor}</p>
                            <p><strong>Numer stanowiska:</strong> ${result.reservation.station_id}</p>
                            <p>Pamiętaj o wpłacie zadatku w wysokości ${result.reservation.people * 50} zł.</p>
                        </div>
                    `;
                    
                    // Insert confirmation after form
                    const confirmationElement = document.createElement('div');
                    confirmationElement.innerHTML = confirmationHTML;
                    reservationForm.parentNode.appendChild(confirmationElement);
                    
                    // Reset form
                    reservationForm.reset();
                    
                    // Scroll to confirmation
                    confirmationElement.scrollIntoView({ behavior: 'smooth' });
                } else {
                    showMessage('error', result.message || 'Wystąpił błąd podczas rezerwacji');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('error', 'Wystąpił błąd podczas przetwarzania rezerwacji');
            })
            .finally(() => {
                // Restore button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    }
    
    // Function is no longer needed since we only display the form to logged-in users
    
    function showMessage(type, message) {
        // Use userAuth.showMessage if available
        if (window.userAuth && typeof window.userAuth.showMessage === 'function') {
            window.userAuth.showMessage(type, message);
            return;
        }
        
        // Create message container
        let messageContainer = document.getElementById('messageContainer');
        
        if (!messageContainer) {
            messageContainer = document.createElement('div');
            messageContainer.id = 'messageContainer';
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
});