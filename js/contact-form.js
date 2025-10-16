/**
 * Contact Form JavaScript
 *
 * Handles client-side validation and AJAX submission
 *
 * @package Sarai_Chinwag
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sarai-contact-form-element');

    if (!form) {
        return;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        clearErrors();

        if (!validateForm()) {
            return;
        }

        const turnstileResponse = turnstile.getResponse();
        if (!turnstileResponse) {
            showError('turnstile', 'Please complete the security check');
            return;
        }

        setSubmitting(true);

        const formData = new FormData();
        formData.append('action', 'submit_contact_form');
        formData.append('nonce', contact_form_ajax.nonce);
        formData.append('name', document.getElementById('contact-name').value.trim());
        formData.append('email', document.getElementById('contact-email').value.trim());
        formData.append('subject', document.getElementById('contact-subject').value.trim());
        formData.append('message', document.getElementById('contact-message').value.trim());
        formData.append('turnstile_token', turnstileResponse);

        try {
            const response = await fetch(contact_form_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showSuccess(data.data.message);
                form.reset();
                turnstile.reset();
            } else {
                showError('general', data.data.message || 'An error occurred');
                turnstile.reset();
            }
        } catch (error) {
            showError('general', 'Network error. Please try again.');
            turnstile.reset();
        } finally {
            setSubmitting(false);
        }
    });
});

function validateForm() {
    let isValid = true;

    const name = document.getElementById('contact-name').value.trim();
    if (name.length === 0) {
        showError('name', 'Name is required');
        isValid = false;
    }

    const email = document.getElementById('contact-email').value.trim();
    if (!isValidEmail(email)) {
        showError('email', 'Valid email is required');
        isValid = false;
    }

    const subject = document.getElementById('contact-subject').value.trim();
    if (subject.length === 0) {
        showError('subject', 'Subject is required');
        isValid = false;
    }

    const message = document.getElementById('contact-message').value.trim();
    if (message.length === 0) {
        showError('message', 'Message is required');
        isValid = false;
    }

    return isValid;
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function showError(field, message) {
    let errorSpan;

    if (field === 'turnstile') {
        errorSpan = document.getElementById('turnstile-error');
    } else if (field === 'general') {
        const formContainer = document.querySelector('.sarai-contact-form');
        errorSpan = document.createElement('div');
        errorSpan.className = 'error-message general-error';
        errorSpan.textContent = message;
        errorSpan.setAttribute('role', 'alert');
        formContainer.insertBefore(errorSpan, formContainer.firstChild);
        return;
    } else {
        const inputElement = document.getElementById('contact-' + field);
        errorSpan = inputElement.nextElementSibling;
    }

    if (errorSpan) {
        errorSpan.textContent = message;
        errorSpan.style.display = 'block';
    }
}

function clearErrors() {
    document.querySelectorAll('.error-message').forEach(el => {
        if (el.classList.contains('general-error')) {
            el.remove();
        } else {
            el.textContent = '';
            el.style.display = 'none';
        }
    });
    const successDiv = document.querySelector('.success-message');
    if (successDiv) {
        successDiv.style.display = 'none';
    }
}

function showSuccess(message) {
    const successDiv = document.querySelector('.success-message');
    successDiv.textContent = message;
    successDiv.style.display = 'block';
    successDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function setSubmitting(isSubmitting) {
    const button = document.querySelector('.submit-button');
    const buttonText = button.querySelector('.button-text');
    const spinner = button.querySelector('.spinner');

    button.disabled = isSubmitting;
    buttonText.style.display = isSubmitting ? 'none' : 'inline';
    spinner.style.display = isSubmitting ? 'inline' : 'none';
}
