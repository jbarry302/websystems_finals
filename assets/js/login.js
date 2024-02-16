// Function to remove the success message after a certain duration
function removeSuccessMessage() {
    var successMessage = document.getElementById('success-message');
    if (successMessage) {
        successMessage.remove();
    }
}

// Delayed removal of the success message
setTimeout(removeSuccessMessage, 5000); // Remove after 5 seconds


function togglePasswordVisibility(inputDiv) {
    var input = inputDiv.querySelector('.password-input-field');
    var icon = inputDiv.querySelector('.toggle-password i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
