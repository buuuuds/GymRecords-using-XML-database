<?php
session_start(); // Start the session to access session variables
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christ Strength - User Dashboard</title>
    <link rel="stylesheet" href="userstyle.css">
    <link rel="icon" href="titlegymicon.jpg" type="x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="locationstyle.css">
</head>
<body>
    <div class="location-panel">
        <div class="location-header">
            <h3>Christ Strength Gym Location</h3>
            <button class="close-location-panel">&times;</button>
        </div>
        <div class="location-content">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d684.2872884998375!2d121.16143747783522!3d14.023144083794813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfil!2sph!4v1733241770911!5m2!1sfil!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <!-- Hamburger Menu for Mobile -->
    <div class="hamburger-menu">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Overlay for Mobile Menu -->
    <div class="overlay"></div>

    <div class="sidebar">
        <div class="sidebar-content">
            <div class="logo-container">
                <img src="logo.png" alt="Christ Strength Gym Logo" class="responsive-logo">
            </div>            
            <ul>
                <li class="active">
                    <a href="userindex.php">
                        <i class="fas fa-home nav-icon"></i>
                        Home
                    </a>
                </li>
                <li>
                    <a href="user-offers.html">
                        <i class="fas fa-hourglass nav-icon"></i>
                        Subscription
                    </a>
                </li>
                <li>
                    <a href="user-trainers.html">
                        <i class="fas fa-dumbbell nav-icon"></i>
                        Trainers
                    </a>
                </li>
                <li>
                    <a href="register-logins.html">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        Log Out
                    </a>
                </li>
            </ul>
            <footer class="footer">
                <div class="footer-links">
                    <a href="about.html">About Us</a> | 
                    <a href="contact.html">Contact</a> | 
                    <a href="privacy.html">Privacy Policy</a>
                </div>
            </footer>
        </div>
    </div>

    <div class="main-content">
        <h1>Welcome <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></h1>

        <div class="dashboard">
            <div class="card weather-forecast" id="weather-section">
                <h3>Weather Forecast</h3>
                <div id="weather-details">
                    <p>Loading weather...</p>
                    <img id="weather-icon" src="" alt="Weather Icon" style="width: 50px; height: 50px; margin-top: 10px;">
                </div>
            </div>

            <div class="card calendar" id="calendar-section">
                <h3 id="calendar-title"></h3>
                <table id="calendar-table">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="location-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>

            <div class="card countdown" id="countdown-section">
                <h3>Subscription Countdown</h3>
                <p>Your subscription expires in:</p>
                <div id="countdown-timer"></div>
            </div>

            <div class="card bmi-calculator" id="bmi-calculator-section">
                <h3>BMI Calculator</h3>
                <form id="bmi-form">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" id="weight" required>
                    <label for="height">Height (cm):</label>
                    <input type="number" id="height" required>
                    <button type="button" onclick="calculateBMI()">Calculate BMI</button>
                </form>
                <p id="bmi-result"></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const locationIcon = document.querySelector('.location-icon');
            const locationPanel = document.querySelector('.location-panel');
            const closeLocationPanel = document.querySelector('.close-location-panel');

            // Ensure initial state
            locationPanel.style.display = 'none';
            locationPanel.style.opacity = '0';
            locationPanel.style.transform = 'translate(-50%, -50%) scale(0)';

            // Open location panel
            locationIcon.addEventListener('click', (e) => {
                locationPanel.style.display = 'block';
                setTimeout(() => {
                    locationPanel.style.opacity = '1';
                    locationPanel.style.transform = 'translate(-50%, -50%) scale(1)';
                }, 10);
            });

            // Close location panel
            closeLocationPanel.addEventListener('click', () => {
                locationPanel.style.opacity = '0';
                locationPanel.style.transform = 'translate(-50%, -50%) scale(0)';
                setTimeout(() => {
                    locationPanel.style.display = 'none';
                }, 300);
            });
        });

        // Weather Forecast Script
        async function fetchWeather() {
            const apiKey = '45d44168e5b73bf77a9ecf35d78970e7'; // Replace with your OpenWeatherMap API key
            const city = 'Batangas'; // You can change the city to your location
            const weatherSection = document.getElementById('weather-details');
            const weatherIcon = document.getElementById('weather-icon'); // Image element for the icon

            try {
                const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=en`);
                const data = await response.json();

                if (data.cod === 200) {
                    const temperature = data.main.temp;
                    const description = data.weather[0].description;
                    const humidity = data.main.humidity;
                    const windSpeed = data.wind.speed;
                    const iconCode = data.weather[0].icon; // Icon code from API response

                    // Set the icon based on weather condition
                    weatherIcon.src = `http://openweathermap.org/img/wn/${iconCode}.png`; // Set weather icon image

                    weatherSection.innerHTML = ` 
                        <p><strong>Temperature:</strong> ${temperature}°C</p>
                        <p><strong>Weather:</strong> ${description}</p>
                        <p><strong>Humidity:</strong> ${humidity}%</p>
                        <p><strong>Wind Speed:</strong> ${windSpeed} m/s</p>
                    `;
                } else {
                    weatherSection.innerHTML = `<p>Weather data not available</p>`;
                }
            } catch (error) {
                weatherSection.innerHTML = `<p>Failed to load weather data</p>`;
            }
        }

        // Calendar Generation Script
        function generateCalendar() {
            const calendarTable = document.querySelector('#calendar-table tbody');
            const calendarTitle = document.querySelector('#calendar-title');
            const today = new Date();
            const year = today.getFullYear();
            const month = today.getMonth();
            const currentDate = today.getDate();

            const monthNames = [
                "January", "February", "March", "April", "May", "June", 
                "July", "August", "September", "October", "November", "December"
            ];

            calendarTitle.textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let currentDay = 1;
            const totalCells = 42;

            calendarTable.innerHTML = '';

            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');

                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('td');

                    if (i * 7 + j >= firstDay && currentDay <= daysInMonth) {
                        cell.textContent = currentDay;

                        if (currentDay === currentDate) {
                            cell.classList.add('today');
                        }

                        currentDay++;
                    }

                    row.appendChild(cell);
                }

                calendarTable.appendChild(row);
            }
        }

        // Countdown Timer Script
        function countdownTimer() {
            const expirationDate = new Date('2024-12-31');
            const countdownElement = document.getElementById('countdown-timer');

            function updateTimer() {
                const now = new Date();
                const timeLeft = expirationDate - now;

                if (timeLeft <= 0) {
                    countdownElement.textContent = "Your subscription has expired.";
                    return;
                }

                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        }

        // BMI Calculator Script
        function calculateBMI() {
            const weightInput = document.getElementById('weight');
            const heightInput = document.getElementById('height');
            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);
            const bmiResult = document.getElementById('bmi-result');

            if (weight > 0 && height > 0) {
                const bmi = (weight / ((height / 100) ** 2)).toFixed(2);
                let category = '';

                if (bmi < 18.5) category = 'Underweight';
                else if (bmi < 24.9) category = 'Normal weight';
                else if (bmi < 29.9) category = 'Overweight';
                else category = 'Obese';

                bmiResult.textContent = `Your BMI is ${bmi} (${category}).`;
            } else {
                bmiResult.textContent = 'Please enter valid values.';
            }
        }

        // Mobile Menu Toggle Script
        document.addEventListener('DOMContentLoaded', () => {
            const hamburgerMenu = document.querySelector('.hamburger-menu');
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');

            hamburgerMenu.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            generateCalendar();
            countdownTimer();
            fetchWeather(); // Call the weather function to load the weather forecast
        });
    </script>
</body>
</html>
