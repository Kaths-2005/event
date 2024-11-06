<?php
// Start session and handle login check
session_start();



// Initialize events array in session if not set
if (!isset($_SESSION['events'])) {
    $_SESSION['events'] = [];
}

// Handle POST request to add an event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['event'])) {
    $eventDate = $_POST['date'];
    $eventName = $_POST['event'];

    // Add event to the session
    $_SESSION['events'][$eventDate] = $eventName;

    // Return JSON response
    echo json_encode(['status' => 'success', 'message' => 'Event added successfully!']);
    exit();
}

// Handle GET request to fetch events
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_events'])) {
    echo json_encode($_SESSION['events']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #calendar div {
            margin: 5px 0;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        #eventModal {
            display: none;
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #eventModal input {
            display: block;
            margin: 10px 0;
            padding: 5px;
            width: 100%;
        }
        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Your Personal Calendar</h1>
    <div id="calendar"></div>
    <button id="addEventBtn">Add Event</button>

    <!-- Event Form Modal -->
    <div id="eventModal">
        <label for="eventDate">Event Date:</label>
        <input type="date" id="eventDate" required><br>
        <label for="eventName">Event Name:</label>
        <input type="text" id="eventName" required><br>
        <button id="saveEventBtn">Save Event</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const calendar = document.getElementById('calendar');
            const addEventBtn = document.getElementById('addEventBtn');
            const eventModal = document.getElementById('eventModal');
            const saveEventBtn = document.getElementById('saveEventBtn');

            // Fetch events from the server and display them
            function loadEvents() {
                fetch('?fetch_events=true')
                    .then(response => response.json())
                    .then(events => {
                        // Clear the calendar
                        calendar.innerHTML = '';
                        // Populate the calendar with events
                        for (let date in events) {
                            const eventItem = document.createElement('div');
                            eventItem.textContent = `${date}: ${events[date]}`;
                            calendar.appendChild(eventItem);
                        }
                    });
            }

            // Show the event modal to add an event
            addEventBtn.addEventListener('click', () => {
                eventModal.style.display = 'block';
            });

            // Save event to the server
            saveEventBtn.addEventListener('click', () => {
                const eventDate = document.getElementById('eventDate').value;
                const eventName = document.getElementById('eventName').value;

                if (eventDate && eventName) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `date=${eventDate}&event=${eventName}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            eventModal.style.display = 'none';
                            loadEvents();
                        }
                    });
                } else {
                    alert('Please enter both date and event name.');
                }
            });

            // Load events on page load
            loadEvents();
        });
    </script>
</body>
</html>