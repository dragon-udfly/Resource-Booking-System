@extends('layouts.normal_body_layout')

@section('title', 'Family Quarters Application - District Secretariat Vavuniya')

@section('page_styles')
    <style>
        .page-header { text-align: center; margin-bottom: 30px; color: #333; }
        .page-header h2 { font-size: 1.8em; margin-bottom: 10px; }
        .button-bar { display: flex; justify-content: flex-start; gap: 15px; margin-bottom: 20px; width: 90%; max-width: 1200px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; text-decoration: none; color: white; transition: background-color 0.3s ease; }
        .home-btn { background-color: #6c757d; }
        .back-btn { background-color: #007bff; }
        .form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 90%; max-width: 1200px; margin-top: 20px; }
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .form-group { flex: 1; min-width: 280px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1em; }
        .form-section-title { font-size: 1.5em; font-weight: bold; margin-bottom: 20px; color: #0056b3; border-bottom: 2px solid #eee; padding-bottom: 10px; width: 100%; }
        .button-group { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
        .required { color: #dc3545; margin-left: 5px; }
    </style>
@endsection

@section('content')
    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn back-btn">Back</a>
            <a href="{{ Auth::check() ? route('homepage') : route('home') }}" class="btn home-btn">Home</a>
        </div>
        <div class="page-header">
            <h2>Application for Family Quarters</h2>
        </div>

        <div class="form-container">
            <form id="family-quarter-form" action="{{ route('familyquarter.store') }}" method="POST">
                @csrf
                {{-- Form fields from original file --}}
                <h3 class="form-section-title">A) Officer Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="officer_name">1. Name of Officer:<span class="required">*</span></label>
                        <input type="text" id="officer_name" name="officer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="nic">2. National Identity Card Number:<span class="required">*</span></label>
                        <input type="text" id="nic" name="nic" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dob">3. Date of Birth: <span class="required">*</span></label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="form-group">
                        <label for="designation">4. Designation <span class="required">*</span></label>
                        <input type="text" id="designation" name="designation" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">5. Gender <span class="required">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_and_grade">6. Service and Grade: <span class="required">*</span></label>
                        <select id="service_and_grade" name="service_and_grade" required>
                            <option value="">Select Service and Grade</option>
                            <option value="1">1 (G I)</option>
                            <option value="2">2 (G II)</option>
                            <option value="3">3 (G III)</option>
                            <option value="4">4 (GIV)</option>
                            <option value="5">5 (G V)</option>
                            <option value="5A">5A</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="permanent_address">7. Permanent Address: <span class="required">*</span></label>
                        <textarea id="permanent_address" name="permanent_address" placeholder="with Grama Niladhari Division:" maxlength="1200" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="temporary_address">8. Temporary Address: </label>
                        <textarea id="temporary_address" name="temporary_address" placeholder="with Grama Niladhari Division:" maxlength="1200"></textarea>
                    </div>
                </div> 

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_number">10. Telephone Number:<span class="required">*</span></label>
                        <input type="tel" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="email">11. Email Address: </label>
                        <input type="email" id="email" name="email">
                    </div>
                </div> 
                <div class="form-row">
                    <div class="form-group">
                        <label for="monthly_salary">9.  Monthly Salary (excluding allowances): <span class="required">*</span></label>
                        <input type="number" id="monthly_salary" name="monthly_salary" required>
                    </div>
                    <div class="form-group">
                        <label for="f_date_of_last_salary_increment">13.  Date of Last Salary Increment: <span class="required">*</span></label>
                        <input type="date" id="f_date_of_last_salary_increment" name="f_date_of_last_salary_increment" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_assumption_of_duties">12.  Date of Assumption of Duties in Vavuniya: <span class="required">*</span></label>
                        <input type="date" id="date_of_assumption_of_duties" name="date_of_assumption_of_duties" required>
                    </div>
                    <div class="form-group">
                        <label for="f_transformed_officer">14.  Is applicant a transferred officer? If yes, assigned from the District Secretariat? (Description about  transfer order): </label>
                        <textarea id="f_transformed_officer" name="f_transformed_officer" placeholder="Enter a description about transfer order documents" maxlength="1200" rows="8"></textarea>
                    </div>
                </div>

                <h3 class="form-section-title">B) Spouse Details</h3>
                <div class="form-row">
                   <div class="form-group">
                        <label for="f_marital_status">1. Marital Status: </label>
                        <select id="f_marital_status" name="f_marital_status">
                            <option value="">Select Marital Status</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="f_is_spouse_employed">2. Is your spouse employed in government service? </label>
                        <select id="f_is_spouse_employed" name="f_is_spouse_employed">
                            <option value="">Select Yes or No</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div> 

                <div class="form-row">
                    <div class="form-group">
                        <label for="f_spouse_designation">3. Spouse’s Designation: </label>
                        <input type="text" id="f_spouse_designation" name="f_spouse_designation">
                    </div>
                    <div class="form-group">
                        <label for="f_spouse_department_office">4. Department / Office Name: </label>
                        <input type="text" id="f_spouse_department_office" name="f_spouse_department_office">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="f_spouse_monthly_salary">5. Monthly Salary (excluding allowances): </label>
                        <input type="number" id="f_spouse_monthly_salary" name="f_spouse_monthly_salary">
                    </div>
                    <div class="form-group">
                        <label for="f_spouse_last_increment_date">6. Date of Last Salary Increment: </label>
                        <input type="date" id="f_spouse_last_increment_date" name="f_spouse_last_increment_date">
                    </div>
                </div>

                <h3 class="form-section-title">C) Children Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="f_children_details_description">1. Enter Description of Children (Write a clear description or use following format with new line pressing Enter): </label>
                        <label>Name: child one name Age: child one age Grade: child one grade School: child one school <br />
                            Name: child two name Age: child two age Grade: child two grade School: child two school <br />
                            Name: child three name Age: child three age Grade: child three grade School: child three school
                        </label>
                        <textarea id="f_children_details_description" name="f_children_details_description" placeholder="Name: John Deo Age: 12 Grade: 5A School: National Colledge, Vavuniya" maxlength="2000" cols="50", rows="15"></textarea>
                    </div>
                </div>

                <h3 class="form-section-title">D) Property Ownership in Vavuniya District</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="f_property_ownership_details">1. Do you or your spouse or children under 18 own any land or house in Vavuniya District? If yes, provide details: </label>
                        <textarea id="f_property_ownership_details" name="f_property_ownership_details" placeholder="Enter description" maxlength="2000" cols="50", rows="10"></textarea>
                    </div>
                </div>

                <h3 class="form-section-title">E) Previous Stay in Government Quarters</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="f_previous_government_quarter_duration">Have you previously stayed in government quarters? If yes, mention the duration (Years): </label>
                        <input type="number" id="f_previous_government_quarter_duration" name="f_previous_government_quarter_duration">
                    </div>
                </div>

                <h3 class="form-section-title">F) Marking Scheme and Marking </h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="marking_f_department">1. Select Applicant's Department <span class="required">*</span></label>
                        <select id="marking_f_department" name="marking_f_department" required>
                            <option value="">Select Department</option>
                            <option value="Officers_attached_under_the_Ministry_of_Home_Affairs">Officers attached under the Ministry of Home Affairs</option>
                            <option value="Officers_attached_to_District_and_Divisional_Secretariats">Officers attached to District and Divisional Secretariats</option>
                            <option value="Other_Officers">Other Officers</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="number_of_dependant">2. Select Number of Dependant</label>
                        <select id="number_of_dependant" name="number_of_dependant">
                            <option value="">Select Dependants</option>
                            <option value="01_person">01 person</option>
                            <option value="02_person">02 person</option>
                            <option value="03_person">03 person</option>
                            <option value="04_person">04 person</option>
                            <option value="05_or_above_05_person">05 or Above 05 person</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="is_dependant_with_disability">3. Dependant(s) with Disability <span class="required">*</span></label>
                        <select id="is_dependant_with_disability" name="is_dependant_with_disability" required>
                            <option value="">Select Yes or No</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="f_distance_of_residency">3. Distance of Residency <span class="required">*</span></label>
                        <select id="f_distance_of_residency" name="f_distance_of_residency" required>
                            <option value="">Select Distance</option>
                            <option value="Out_District_above_100km">Out District above 100km</option>
                            <option value="Out_District_between_51km_and_100km">Out District between 51km and 100km</option>
                            <option value="Out_District_between_26km_and_50km">Out District between 26km and 50km</option>
                            <option value="Out_District_below_25km">Out District below 25km</option>
                            <option value="Out_of_Urban_Council_Area_above_30km">Out of Urban Council Area above 30km</option>
                            <option value="Out_of_Urban_Council_Area_between_00km_and_30km">Out of Urban Council Area between 00km and 30km</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="f_spacial_reason">4. Provide Special Reasons (Decided by Government Agent for Quarter Reservation): </label>
                        <textarea id="f_spacial_reason" name="f_spacial_reason" placeholder="Enter Special Reason" maxlength="2000" cols="50", rows="6"></textarea>
                    </div>
                </div>

                <h3 class="form-section-title">G) Requester Details (Enter details to add this application to system)</h3> 
                <div class="form-row" title="Filling person should have requester permission to submit this application">
                    <div class="form-group">
                        <label for="filled_by_nic">Requester Officer's NIC <span class="required">*</span></label>
                        <input type="text" id="filled_by_nic" name="filled_by_nic" required>
                    </div>
                    <div class="form-group">
                        <label for="filled_by_phone">Requester Officer's Phone <span class="required">*</span></label>
                        <input type="tel" id="filled_by_phone" name="filled_by_phone" required>
                    </div>
                </div>
                 <div class="form-group" style="margin-top: 20px; display: flex; align-items: center;">
                    <input type="checkbox" id="confirm_details" name="confirm_details" required style="width: 20px; height: 20px; margin-right: 15px; cursor: pointer;">
                    <label for="confirm_details" style="margin-bottom: 0; cursor: pointer;">I filled this form with applicant details. All details filled here are correct.</label>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn" style="background-color: #007bff;">Submit</button>
                    <button type="reset" class="btn" style="background-color: #6c757d;">Reset</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Generic Modal Overlay -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-title"></h3>
            <p id="modal-message"></p>
            <div id="modal-buttons" class="modal-buttons"></div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: #fff; padding: 30px; border-radius: 8px; text-align: center; max-width: 450px; width: 90%; }
    /* Added styles for submit and back buttons within the modal */
    .submit-btn { background-color: #007bff; }
    .back-btn { background-color: #6c757d; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('family-quarter-form');
    const modalOverlay = document.getElementById('modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalButtons = document.getElementById('modal-buttons');

    const showModal = (title, message, buttons) => {
        modalTitle.textContent = title;
        modalMessage.innerHTML = message;
        modalButtons.innerHTML = '';
        buttons.forEach(btn => {
            const buttonEl = document.createElement('button');
            buttonEl.textContent = btn.text;
            buttonEl.className = 'btn';
            if(btn.class) buttonEl.classList.add(btn.class);
            buttonEl.addEventListener('click', btn.onClick);
            modalButtons.appendChild(buttonEl);
        });
        modalOverlay.style.display = 'flex';
    };
    const hideModal = () => { modalOverlay.style.display = 'none'; };

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        showModal('Processing...', 'Verifying requester...', []);
        
        // Step 1: Verify Requester
        try {
            const verifyResponse = await fetch('{{ route('quarters.requester.verify') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    nic_number: form.querySelector('#filled_by_nic').value,
                    contact_number: form.querySelector('#filled_by_phone').value
                })
            });
            const verifyResult = await verifyResponse.json();

            if (!verifyResult.success) {
                showModal('Verification Failed', verifyResult.message, [{ text: 'OK', class:'back-btn', onClick: hideModal }]);
                return;
            }
        } catch (error) {
            showModal('Error', 'Could not connect to the verification server.', [{ text: 'OK', class:'back-btn', onClick: hideModal }]);
            return;
        }

        // Step 2: Confirm Submission
        showModal('Confirm Submission', 'Requester verified. Are you sure you want to submit this application?', [
            { text: 'Submit', class: 'submit-btn', onClick: performSubmit },
            { text: 'Cancel', class: 'back-btn', onClick: hideModal }
        ]);
    });

    const performSubmit = async () => {
        showModal('Processing...', 'Submitting application...', []);
        const formData = new FormData(form);
        const url = form.action;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const responseText = await response.text();

            if (!response.ok) {
                let message = `Error: ${response.status} ${response.statusText}`;
                try {
                    const result = JSON.parse(responseText);
                    if(result.errors) {
                        message = '<ul style="text-align: left; margin: 0; padding-left: 20px;">';
                        for (const key in result.errors) { message += `<li>${result.errors[key][0]}</li>`; }
                        message += '</ul>';
                    } else { message = result.message || message; }
                } catch (e) { /* Ignore */ }
                showModal('Error', message, [{ text: 'OK', class: 'back-btn', onClick: hideModal }]);
            } else {
                showModal('Success', JSON.parse(responseText).message, [
                    { text: 'OK', class: 'submit-btn', onClick: () => window.location.href = "{{ route('bookquarter') }}" }
                ]);
            }
        } catch (error) {
            showModal('Request Failed', 'Could not connect to the server.', [{ text: 'OK', class: 'back-btn', onClick: hideModal }]);
        }
    };

    modalOverlay.addEventListener('click', (e) => { if (e.target === modalOverlay) hideModal(); });
});
</script>
@endpush
