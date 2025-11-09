<?php if (!isLoggedIn()): ?>
<div class="content">
    <div class="alert alert-error">
        <h3>Authentication Required</h3>
        <p>Please <a href="?page=login" style="color: #1a5276; font-weight: bold;">login</a> to apply for quarters.</p>
    </div>
    
    <h2>Quarters Application Preview</h2>
    <p>Government family quarters are available for eligible officers. To apply, you need to:</p>
    <ul>
        <li>Be a government officer</li>
        <li>Provide accurate personal and professional details</li>
        <li>Declare property ownership in Vavuniya District</li>
        <li>Agree to the terms and conditions</li>
    </ul>
    <a href="?page=login" class="btn">Login to Apply</a>
</div>
<?php else: ?>
<div class="content">
    <h1>Quarters Booking Application</h1>
    <p>Please fill out the form below to apply for government family quarters.</p>
    
    <form action="?page=process_quarters" method="POST">
        <h2>Officer Details</h2>
        
        <div class="form-group">
            <label for="officer_name">Full Name</label>
            <input type="text" id="officer_name" name="officer_name" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-group">
            <label for="nic_number">National Identity Card Number</label>
            <input type="text" id="nic_number" name="nic_number" placeholder="Enter NIC number" required>
        </div>
        
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
        </div>
        
        <div class="form-group">
            <label for="designation">Designation</label>
            <input type="text" id="designation" name="designation" placeholder="Enter your designation" required>
        </div>
        
        <div class="form-group">
            <label for="service_grade">Service and Grade</label>
            <input type="text" id="service_grade" name="service_grade" placeholder="Enter service and grade" required>
        </div>
        
        <div class="form-group">
            <label for="permanent_address">Permanent Address</label>
            <textarea id="permanent_address" name="permanent_address" rows="3" placeholder="Enter permanent address" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="temporary_address">Temporary Address</label>
            <textarea id="temporary_address" name="temporary_address" rows="3" placeholder="Enter temporary address" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="monthly_salary">Monthly Salary (excluding allowances)</label>
            <input type="number" id="monthly_salary" name="monthly_salary" step="0.01" placeholder="Enter monthly salary" required>
        </div>
        
        <div class="form-group">
            <label for="duty_assumption_date">Date of Assumption of Duties in Vavuniya</label>
            <input type="date" id="duty_assumption_date" name="duty_assumption_date" required>
        </div>
        
        <div class="form-group">
            <label for="telephone_number">Telephone Number</label>
            <input type="text" id="telephone_number" name="telephone_number" placeholder="Enter telephone number" required>
        </div>
        
        <div class="form-group">
            <label>Are you a transferred officer?</label>
            <div>
                <input type="radio" id="transferred_yes" name="is_transferred_officer" value="1">
                <label for="transferred_yes" style="display: inline; margin-right: 20px;">Yes</label>
                <input type="radio" id="transferred_no" name="is_transferred_officer" value="0" checked>
                <label for="transferred_no" style="display: inline;">No</label>
            </div>
        </div>
        
        <div class="form-group" id="transfer_details" style="display: none;">
            <label for="transfer_order_details">Transfer Order Details</label>
            <textarea id="transfer_order_details" name="transfer_order_details" rows="3" placeholder="Provide transfer order details"></textarea>
        </div>
        
        <h2>Spouse Details</h2>
        
        <div class="form-group">
            <label for="marital_status">Marital Status</label>
            <select id="marital_status" name="marital_status">
                <option value="">Select Marital Status</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Divorced">Divorced</option>
                <option value="Separated">Separated</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Is your spouse employed in government service?</label>
            <div>
                <input type="radio" id="spouse_employed_yes" name="spouse_employed" value="1">
                <label for="spouse_employed_yes" style="display: inline; margin-right: 20px;">Yes</label>
                <input type="radio" id="spouse_employed_no" name="spouse_employed" value="0" checked>
                <label for="spouse_employed_no" style="display: inline;">No</label>
            </div>
        </div>
        
        <div class="form-group" id="spouse_details" style="display: none;">
            <label for="spouse_designation">Spouse's Designation</label>
            <input type="text" id="spouse_designation" name="spouse_designation" placeholder="Enter spouse's designation">
            
            <label for="spouse_department" style="margin-top: 10px;">Department / Office Name</label>
            <input type="text" id="spouse_department" name="spouse_department" placeholder="Enter department/office name">
            
            <label for="spouse_salary" style="margin-top: 10px;">Monthly Salary (excluding allowances)</label>
            <input type="number" id="spouse_salary" name="spouse_salary" step="0.01" placeholder="Enter spouse's monthly salary">
        </div>
        
        <h2>Children Details</h2>
        <div id="children_container">
            <div class="child-entry">
                <div class="form-group">
                    <label>Child Name</label>
                    <input type="text" name="child_name[]" placeholder="Enter child's name">
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="child_age[]" placeholder="Enter age">
                </div>
                <div class="form-group">
                    <label>Grade</label>
                    <input type="text" name="child_grade[]" placeholder="Enter grade">
                </div>
                <div class="form-group">
                    <label>School</label>
                    <input type="text" name="child_school[]" placeholder="Enter school">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" onclick="addChild()">Add Another Child</button>
        
        <h2>Property Ownership in Vavuniya District</h2>
        <div class="form-group">
            <label>Do you or your spouse or children under 18 own any land or house in Vavuniya District?</label>
            <div>
                <input type="radio" id="property_yes" name="owns_property_vavuniya" value="1">
                <label for="property_yes" style="display: inline; margin-right: 20px;">Yes</label>
                <input type="radio" id="property_no" name="owns_property_vavuniya" value="0" checked>
                <label for="property_no" style="display: inline;">No</label>
            </div>
        </div>
        
        <div class="form-group" id="property_details" style="display: none;">
            <label for="property_details_text">Property Details</label>
            <textarea id="property_details_text" name="property_details" rows="3" placeholder="Provide property details"></textarea>
        </div>
        
        <h2>Previous Stay in Government Quarters</h2>
        <div class="form-group">
            <label>Have you previously stayed in government quarters?</label>
            <div>
                <input type="radio" id="previous_yes" name="previous_quarters_stay" value="1">
                <label for="previous_yes" style="display: inline; margin-right: 20px;">Yes</label>
                <input type="radio" id="previous_no" name="previous_quarters_stay" value="0" checked>
                <label for="previous_no" style="display: inline;">No</label>
            </div>
        </div>
        
        <div class="form-group" id="previous_stay" style="display: none;">
            <label for="previous_stay_duration">Duration of Previous Stay</label>
            <input type="text" id="previous_stay_duration" name="previous_stay_duration" placeholder="e.g., 2 years (2020-2022)">
        </div>
        
        <h2>Declaration</h2>
        <div class="form-group">
            <p>I hereby declare that all the information provided above is true and accurate. I understand that if any changes occur, I must inform the Government Agent, Vavuniya immediately. I acknowledge that disciplinary action may be taken against me if false information is provided.</p>
            
            <div style="display: flex; gap: 20px; margin-top: 20px;">
                <div style="flex: 1;">
                    <label for="declaration_date">Date</label>
                    <input type="date" id="declaration_date" name="declaration_date" required>
                </div>
                <div style="flex: 1;">
                    <label>Applicant's Signature</label>
                    <div style="border: 1px solid #ddd; height: 50px; padding: 10px; border-radius: 4px;">
                        <em>Digital Signature</em>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn">Submit Application</button>
    </form>
</div>

<script>
// Show/hide transfer details
document.querySelectorAll('input[name="is_transferred_officer"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('transfer_details').style.display = 
            this.value === '1' ? 'block' : 'none';
    });
});

// Show/hide spouse details
document.querySelectorAll('input[name="spouse_employed"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('spouse_details').style.display = 
            this.value === '1' ? 'block' : 'none';
    });
});

// Show/hide property details
document.querySelectorAll('input[name="owns_property_vavuniya"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('property_details').style.display = 
            this.value === '1' ? 'block' : 'none';
    });
});

// Show/hide previous stay details
document.querySelectorAll('input[name="previous_quarters_stay"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('previous_stay').style.display = 
            this.value === '1' ? 'block' : 'none';
    });
});

// Add child fields
function addChild() {
    const container = document.getElementById('children_container');
    const newChild = document.createElement('div');
    newChild.className = 'child-entry';
    newChild.style.borderTop = '1px solid #ddd';
    newChild.style.paddingTop = '20px';
    newChild.style.marginTop = '20px';
    newChild.innerHTML = `
        <div class="form-group">
            <label>Child Name</label>
            <input type="text" name="child_name[]" placeholder="Enter child's name">
        </div>
        <div class="form-group">
            <label>Age</label>
            <input type="number" name="child_age[]" placeholder="Enter age">
        </div>
        <div class="form-group">
            <label>Grade</label>
            <input type="text" name="child_grade[]" placeholder="Enter grade">
        </div>
        <div class="form-group">
            <label>School</label>
            <input type="text" name="child_school[]" placeholder="Enter school">
        </div>
        <button type="button" class="btn btn-secondary" onclick="this.parentElement.remove()">Remove Child</button>
    `;
    container.appendChild(newChild);
}

// Set declaration date to today
document.getElementById('declaration_date').valueAsDate = new Date();
</script>
<?php endif; ?>