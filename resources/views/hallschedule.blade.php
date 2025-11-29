<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Schedule - District Secretariat - Vavuniya</title>
    <link href='{{ asset('icons/right_logo.png') }}' rel='icon' type='image/png'>
    <style>
        /* Copied from bookhall.blade.php */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; }
        .header { background-color: #f8f9fa; display: flex; flex-direction: column; align-items: center; border-bottom: 3px solid #ddd; }
        .header-main { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        .logo-left { width: 110px; height: 22vh; margin-left: 70px; }
        .header-content { flex: 1; text-align: center; padding: 0 10px; }
        .header-content h1 { font-size: 40px; font-weight: bold; color: #000; padding-bottom: 20px; }
        .header-content h2 { font-size: 25px; font-weight: normal; color: #333; }
        .logo-right { width: 130px; height: 22vh; margin-right: 70px; }
        .banner { background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%); min-height: 58vh; width: 100%; display: flex; flex-direction: row; align-items: flex-start; padding: 20px; gap: 20px; }
        .footer { background-color: #000; height: 17vh; width: 100%; color: white; text-align: center; padding-top: 20px; }

        /* Styles for hall schedule page */
        .left-content-area {
            width: 30%;
            display: flex;
            flex-direction: column;
        }
        .button-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }
        .event-list-container {
            flex-grow: 1;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow-y: auto;
            min-height: 300px; /* Give it a minimum height */
        }
        .event-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .event-item:last-child { border-bottom: none; }
        .event-item strong { color: #0056b3; }
        .event-item p { font-size: 0.9em; color: #555; }

        .calendar-section {
            width: 70%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        .calendar-month { margin-bottom: 20px; }
        .calendar-month h3 { text-align: center; font-size: 1.5em; margin-bottom: 10px; }
        .calendar-grid { width: 100%; border-collapse: collapse; }
        .calendar-grid th, .calendar-grid td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
            height: 60px;
            vertical-align: top;
            font-size: 0.8em;
        }
        .calendar-grid th { background-color: #f2f2f2; font-weight: bold; }
        .day-number { font-weight: bold; font-size: 1em; }
        .today .day-number { color: #fff; background-color: #007bff; border-radius: 50%; padding: 4px; display: inline-block; width: 25px; height: 25px; line-height: 18px; }
        .other-month { color: #ccc; }
        .booked-date { background-color: #05fd01; }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-main">
            <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
            <div class="header-content">
                <h1>District Secretariat - Vavuniya</h1>
                <h2>Hall and Quarters Booking System</h2>
            </div>
            <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
        </div>
    </header>

    <section class="banner">
        <div class="left-content-area">
            <div class="button-bar">
                <a href="#" onclick="history.back(); return false;" class="btn" style="background-color: #6c757d;">Back</a>
                <a href="{{ route('halls.book') }}" class="btn" style="background-color: #007bff;">New Event</a>
            </div>
            <div class="event-list-container">
                <h2 style="color: rgb(6, 4, 60); font-weight: bold; padding: 10px">Scheduled Events/Programms</h2>
                @forelse($bookings as $booking)
                    <div class="event-item">
                        <strong>Programme: {{ $booking->programme }}</strong>
                        <p>Applicant: {{ $booking->applicant_name }} ({{ $booking->applicant_type }})</p>
                        <p>Date: {{ $booking->event_date }}</p>
                        <p>Hall: {{ $booking->hall->hall_type ?? 'N/A' }}</p>
                        <p>Participants: {{ $booking->participants }}</p>
                        <p>Duration: {{ $booking->event_duration }} hours</p>
                        <p>Status: {{ ucfirst($booking->final_approval) }}</p>
                    </div>
                @empty
                    <p>No scheduled events found.</p>
                @endforelse
            </div>
        </div>
        <div class="calendar-section" id="calendar">
            <!-- Calendar will be generated here by JavaScript -->
        </div>
    </section>

    <footer class="footer" style="color: white; text-align: center; padding-top: 20px;">
        <p>&copy; 2025 District Secretariat, Vavuniya. All Rights Reserved.</p>
        <br />
        <p style="margin-top: 10px;">
            <a href="/privacy" style="color: white; text-decoration: none; margin: 0 10px;">Privacy and Policy</a>
            |
            <a href="/agreement" style="color: white; text-decoration: none; margin: 0 10px;">User Agreement</a>
        </p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookings = @json($bookings->map->event_date);
            const bookedDates = new Set(bookings);
            const calendarContainer = document.getElementById('calendar');
            const today = new Date();
            const currentYear = today.getFullYear();
            const currentMonth = today.getMonth();
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            function generateCalendar(year, month) {
                const monthDiv = document.createElement('div');
                monthDiv.className = 'calendar-month';
                const title = document.createElement('h3');
                title.textContent = `${monthNames[month]} ${year}`;
                monthDiv.appendChild(title);
                const table = document.createElement('table');
                table.className = 'calendar-grid';
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                dayNames.forEach(day => {
                    const th = document.createElement('th');
                    th.textContent = day;
                    headerRow.appendChild(th);
                });
                thead.appendChild(headerRow);
                table.appendChild(thead);
                const tbody = document.createElement('tbody');
                const firstDayOfMonth = new Date(year, month, 1);
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const startingDay = firstDayOfMonth.getDay();
                let date = 1;
                for (let i = 0; i < 6; i++) {
                    const weekRow = document.createElement('tr');
                    for (let j = 0; j < 7; j++) {
                        const cell = document.createElement('td');
                        if (i === 0 && j < startingDay || date > daysInMonth) {
                             cell.classList.add('other-month');
                        } else {
                            const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                            if (bookedDates.has(currentDate)) {
                                cell.classList.add('booked-date');
                            }
                            const dayNumber = document.createElement('div');
                            dayNumber.className = 'day-number';
                            dayNumber.textContent = date;
                            cell.appendChild(dayNumber);
                            if (year === today.getFullYear() && month === today.getMonth() && date === today.getDate()) {
                                cell.classList.add('today');
                            }
                            date++;
                        }
                        weekRow.appendChild(cell);
                    }
                    tbody.appendChild(weekRow);
                    if (date > daysInMonth) break;
                }
                table.appendChild(tbody);
                monthDiv.appendChild(table);
                return monthDiv;
            }

            for (let i = 0; i < 6; i++) {
                let month = currentMonth + i;
                let year = currentYear;
                if (month >= 12) {
                    year = currentYear + Math.floor(month / 12);
                    month = month % 12;
                }
                const calendarHTML = generateCalendar(year, month);
                calendarContainer.appendChild(calendarHTML);
            }
        });
    </script>
</body>
</html>
