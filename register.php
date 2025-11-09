<?php
if (isLoggedIn()) {
    header("Location: ?page=dashboard");
    exit();
}
?>
<div class="content auth-container">
    <h1>Create Your Account</h1>
    <p>Please fill in the details to register.</p>
    
    <div class="auth-form">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form action="?page=auth" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email address" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <div class="form-group">
                <label for="user_type">I am a...</label>
                <select id="user_type" name="user_type" required>
                    <option value="">Select User Type</option>
                    <option value="internal">Internal Staff</option>
                    <option value="external">External Guest</option>
                </select>
            </div>
            
            <div class="form-group" id="department_field" style="display: none;">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" placeholder="Enter your department">
            </div>
            
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" placeholder="Enter your contact number">
            </div>
            
            <button type="submit" name="register">Register</button>
        </form>
        
        <div style="margin-top: 20px; text-align: center;">
            <p>Already have an account? <a href="?page=login">Log in</a></p>
        </div>
    </div>
</div>

<script>
document.getElementById('user_type').addEventListener('change', function() {
    const departmentField = document.getElementById('department_field');
    if (this.value === 'internal') {
        departmentField.style.display = 'block';
    } else {
        departmentField.style.display = 'none';
    }
});
</script>