<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Schedule - District Secretariat - Vavuniya</title>
    <link href='{{ asset('icons/right_logo.png') }}' rel='icon' type='image/png'>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .header { background-color: #f8f9fa; padding: 10px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #ddd; }
        .logo-left { width: 110px; height: 22vh; margin-left: 70px; }
        .header-content { flex: 1; text-align: center; padding: 0 10px; }
        .header-content h1 { font-size: 40px; font-weight: bold; color: #000; padding-bottom: 20px; }
        .header-content h2 { font-size: 25px; font-weight: normal; color: #333; }
        .logo-right { width: 130px; height: 22vh; margin-right: 70px; }
        .banner { background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%); width: 100%; display: flex; flex-direction: row; align-items: flex-start; padding: 20px; min-height: 58vh; }
        .footer { background-color: #000; color: white; text-align: center; padding: 20px 0; }
        .btn, .submit-btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; font-weight: bold; text-decoration: none; color: white; }
        .btn:hover, .submit-btn:hover { opacity: 0.9; }

        .calendar-container {
            width: 50%;
            /* margin: 0; */ /* Removed to allow flexbox to manage spacing */
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-height: 70vh; /* Set a max height */
            overflow-y: auto; /* Add vertical scroll when content overflows */
        }

        .calendar-month {
            margin-bottom: 20px;
        }

        .calendar-month h3 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .calendar-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-grid th, .calendar-grid td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
            height: 60px;
            vertical-align: top;
            font-size: 0.8em;
        }

        .calendar-grid th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .day-number {
            font-weight: bold;
            font-size: 1em;
        }

        .today .day-number {
            color: #fff;
            background-color: #007bff;
            border-radius: 50%;
            padding: 4px;
            display: inline-block;
            width: 25px;
            height: 25px;
            line-height: 18px;
        }

        .other-month {
            color: #ccc;
        }

        .button-bar {
            width: 50%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 20px 20px 0; /* Align with calendar left margin, add right margin for spacing */
            /* padding-top: 20px; */ /* Removed as banner handles top padding */
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="{{ asset('icons/left_logo.png') }}" alt="Sri Lanka government logo" class="logo-left">
        <div class="header-content">
            <h1>District Secretariat - Vavuniya</h1>
            <h2>Hall and Quarters Booking System</h2>
        </div>
        <img src="{{ asset('icons/right_logo.png') }}" alt="district Secretariat vavuniya logo" class="logo-right">
    </header>

    <section class="banner">
        <div class="button-bar">
            <a href="#" onclick="history.back(); return false;" class="btn" style="background-color: #6c757d;">Back</a>
            <a href="{{ route('halls.book') }}" class="btn" style="background-color: #007bff;">New Event</a>
        </div>
        <div class="scheduled-events"></div>
        <div class="calendar-container" id="calendar">
            <!-- Calendar will be generated here by JavaScript -->
        </div>
    </section>

   <footer class="footer" style="color: white; text-align: center; padding-top: 20px;">
        <p>&copy; 2025 District Secretariat, Vavuniya. All Rights Reserved.</p>
        <p style="margin-top: 10px;">
            <a href="/privacy" style="color: white; text-decoration: none; margin: 0 10px;">Privacy and Policy</a>
            |
            <a href="/agreement" style="color: white; text-decoration: none; margin: 0 10px;">User Agreement</a>
        </p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

                // Create table header (day names)
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                dayNames.forEach(day => {
                    const th = document.createElement('th');
                    th.textContent = day;
                    headerRow.appendChild(th);
                });
                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Create table body (days)
                const tbody = document.createElement('tbody');
                const firstDayOfMonth = new Date(year, month, 1);
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const startingDay = firstDayOfMonth.getDay(); // 0 for Sunday, 1 for Monday, etc.

                let date = 1;
                for (let i = 0; i < 6; i++) { // Max 6 weeks in a month
                    const weekRow = document.createElement('tr');
                    for (let j = 0; j < 7; j++) {
                        const cell = document.createElement('td');
                        if (i === 0 && j < startingDay) {
                            // Empty cells before the start of the month
                             cell.classList.add('other-month');
                        } else if (date > daysInMonth) {
                            // Empty cells after the end of the month
                             cell.classList.add('other-month');
                        } else {
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
                    if (date > daysInMonth) break; // Stop if we've added all days
                }
                table.appendChild(tbody);
                monthDiv.appendChild(table);
                return monthDiv;
            }

            // Generate 6 months starting from the current month
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