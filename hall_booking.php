<?php if (!isLoggedIn()): ?>
<div class="content">
    <div class="alert alert-error">
        <h3>Authentication Required</h3>
        <p>Please <a href="?page=login" style="color: #1a5276; font-weight: bold;">login</a> to book halls.</p>
    </div>
    
    <h2>Available Halls Preview</h2>
    <?php
    // Show hall preview even when not logged in
    $sql = "SELECT * FROM halls";
    $result = $conn->query($sql);
    $halls = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $halls[] = $row;
        }
    }
    ?>
    
    <div class="hall-grid">
        <?php foreach($halls as $hall): ?>
        <div class="hall-card available">
            <h3><?php echo $hall['name']; ?></h3>
            <p><?php echo $hall['description']; ?></p>
            <p><strong>Capacity:</strong> <?php echo $hall['capacity']; ?> people</p>
            <div class="price">
                Internal Users: Free | External Users: $<?php echo $hall['external_price']; ?>/hour
            </div>
            <a href="?page=login" class="btn">Login to Book</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="content">
    <h1>Hall Booking</h1>
    
    <?php
    // Get all halls
    $sql = "SELECT * FROM halls";
    $result = $conn->query($sql);
    $halls = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $halls[] = $row;
        }
    }

    // Get booked halls for the selected date
    $selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    $booked_halls = [];

    $sql = "SELECT hall_id FROM hall_bookings WHERE booking_date = ? AND status = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $booked_result = $stmt->get_result();

    while($row = $booked_result->fetch_assoc()) {
        $booked_halls[] = $row['hall_id'];
    }
    ?>
    
    <div class="form-group">
        <label for="booking_date">Select Date:</label>
        <input type="date" id="booking_date" name="booking_date" value="<?php echo $selected_date; ?>" 
               min="<?php echo date('Y-m-d'); ?>" onchange="window.location.href='?page=hall_booking&date=' + this.value">
    </div>
    
    <h2>Available Halls for <?php echo date('F j, Y', strtotime($selected_date)); ?></h2>
    
    <div class="hall-grid">
        <?php foreach($halls as $hall): 
            $is_booked = in_array($hall['id'], $booked_halls);
            $price = ($_SESSION['user_type'] === 'internal') ? $hall['internal_price'] : $hall['external_price'];
        ?>
        <div class="hall-card <?php echo $is_booked ? 'booked' : 'available'; ?>">
            <h3><?php echo $hall['name']; ?></h3>
            <p><?php echo $hall['description']; ?></p>
            <p><strong>Capacity:</strong> <?php echo $hall['capacity']; ?> people</p>
            <div class="price">
                <?php if ($_SESSION['user_type'] === 'internal'): ?>
                    Internal Users: Free | External Users: $<?php echo $hall['external_price']; ?>/hour
                <?php else: ?>
                    $<?php echo $hall['external_price']; ?>/hour
                <?php endif; ?>
            </div>
            <p><strong>Status:</strong> <?php echo $is_booked ? 'Booked' : 'Available'; ?></p>
            
            <?php if (!$is_booked): ?>
            <button onclick="openBookingModal(<?php echo $hall['id']; ?>, '<?php echo $hall['name']; ?>', <?php echo $price; ?>)"
                    class="btn">Book Now</button>
            <?php else: ?>
            <button class="btn btn-secondary" disabled>Already Booked</button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <h2>Book Hall</h2>
        <form action="?page=process_booking" method="POST">
            <input type="hidden" id="modal_hall_id" name="hall_id">
            <input type="hidden" name="booking_date" value="<?php echo $selected_date; ?>">
            
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" id="event_name" name="event_name" required>
            </div>
            
            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea id="event_description" name="event_description" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" id="start_time" name="start_time" required>
            </div>
            
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" id="end_time" name="end_time" required>
            </div>
            
            <div class="form-group">
                <label for="participants_count">Number of Participants</label>
                <input type="number" id="participants_count" name="participants_count" min="1" required>
            </div>
            
            <div class="form-group">
                <p><strong>Selected Hall:</strong> <span id="modal_hall_name"></span></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($selected_date)); ?></p>
                <p><strong>Estimated Total:</strong> $<span id="modal_total_amount">0</span></p>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Confirm Booking</button>
                <button type="button" class="btn btn-secondary" onclick="closeBookingModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentPrice = 0;

function openBookingModal(hallId, hallName, price) {
    currentPrice = price;
    document.getElementById('modal_hall_id').value = hallId;
    document.getElementById('modal_hall_name').textContent = hallName;
    document.getElementById('bookingModal').style.display = 'block';
    calculateTotal();
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
}

function calculateTotal() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    
    if (startTime && endTime) {
        const start = new Date('2000-01-01 ' + startTime);
        const end = new Date('2000-01-01 ' + endTime);
        const hours = (end - start) / (1000 * 60 * 60);
        
        if (hours > 0) {
            const total = hours * currentPrice;
            document.getElementById('modal_total_amount').textContent = total.toFixed(2);
        }
    }
}

// Add event listeners for time inputs
document.getElementById('start_time').addEventListener('change', calculateTotal);
document.getElementById('end_time').addEventListener('change', calculateTotal);

// Close modal when clicking outside
document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBookingModal();
    }
});
</script>
<?php endif; ?>