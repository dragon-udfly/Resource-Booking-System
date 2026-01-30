<!-- Logout Confirmation Overlay -->
<div id="logout-confirmation-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 10000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 400px; width: 90%; text-align: center; position: relative;">
        <h3 style="margin-top: 0; color: #333;">Confirm Logout</h3>
        <p style="font-size: 1.1em; color: #555; margin: 20px 0;">You are going to log out.</p>
        <div style="display: flex; justify-content: center; gap: 15px; margin-top: 20px;">
            <button id="confirm-logout-btn" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold;">
                Yes, Log Out
            </button>
            <button id="cancel-logout-btn" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find all logout forms on the page (both with action="/logout" and logout-trigger class)
    const logoutForms = document.querySelectorAll('form[action*="/logout"], form button.logout-trigger');

    // If we found forms with action attribute
    let formsToProcess = [];

    // Add forms with action attribute
    const actionForms = document.querySelectorAll('form[action*="/logout"]');
    actionForms.forEach(form => formsToProcess.push(form));

    // Add forms that contain logout-trigger buttons
    const triggerButtons = document.querySelectorAll('form button.logout-trigger');
    triggerButtons.forEach(button => {
        const form = button.closest('form');
        if (form && !formsToProcess.includes(form)) {
            formsToProcess.push(form);
        }
    });

    if (formsToProcess.length > 0) {
        // Add event listeners to all logout buttons
        formsToProcess.forEach(form => {
            // Look for submit buttons in the form
            const logoutBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            logoutBtns.forEach(logoutBtn => {
                // Check if the button has the logout-trigger class or if it's the only submit button in a logout form
                if (logoutBtn.classList.contains('logout-trigger') || form.action.includes('/logout')) {
                    logoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Store reference to the clicked form
                        document.__logoutForm = form;

                        // Show the confirmation overlay
                        const logoutOverlay = document.getElementById('logout-confirmation-overlay');
                        if (logoutOverlay) {
                            logoutOverlay.style.display = 'flex';
                        }
                    });
                }
            });
        });

        // Confirm logout action
        const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', function() {
                if (document.__logoutForm) {
                    document.__logoutForm.submit();
                }
            });
        }

        // Cancel logout action
        const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
        if (cancelLogoutBtn) {
            cancelLogoutBtn.addEventListener('click', function() {
                const logoutOverlay = document.getElementById('logout-confirmation-overlay');
                if (logoutOverlay) {
                    logoutOverlay.style.display = 'none';
                }
                document.__logoutForm = null;
            });
        }

        // Close overlay when clicking outside the dialog
        const logoutOverlay = document.getElementById('logout-confirmation-overlay');
        if (logoutOverlay) {
            logoutOverlay.addEventListener('click', function(e) {
                if (e.target === logoutOverlay) {
                    logoutOverlay.style.display = 'none';
                    document.__logoutForm = null;
                }
            });
        }
    }
});
</script>