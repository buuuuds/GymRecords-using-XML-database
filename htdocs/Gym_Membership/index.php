<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christ Strength Gym</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="titlegymicon.jpg" type="x-icon">
    <!-- Add Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- New styles for locations, about, and contact modals -->
    <style>
        .locations-modal, .about-modal, .contact-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--white);
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            z-index: 1000;
            box-shadow: 0 10px 20px rgba(77, 166, 255, 0.2);
        }

        .locations-modal-overlay, .about-modal-overlay, .contact-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .locations-modal-close, .about-modal-close, .contact-modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: var(--deep-blue);
            cursor: pointer;
        }

        .logout-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--white);
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            z-index: 1000;
            box-shadow: 0 10px 20px rgba(77, 166, 255, 0.2);
            text-align: center;
        }

        .logout-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .logout-modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .logout-modal-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-modal-buttons button:first-child {
            background-color: var(--deep-blue);
            color: black;
        }

        .logout-modal-buttons button:last-child {
            background-color: red;
            color: black;
        }

        .logout-modal-buttons button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<?php
// Load XML file and count total members
$membersFile = 'files/members.xml';
$totalMembers = 0;
$totalMale = 0;
$totalFemale = 0;
$totalOthers = 0;

if (file_exists($membersFile)) {
    $xml = simplexml_load_file($membersFile);
    $totalMembers = count($xml->user);

    // Count male and female members
    foreach ($xml->user as $user) {
        if (strtolower($user->gender) == 'boy') {
            $totalMale++;
        } elseif (strtolower($user->gender) == 'girl') {
            $totalFemale++;
        } elseif (strtolower($user->gender) == 'other') {
            $totalOthers++;
        }
    }

    // Create or update the totals section in the XML
    // Check if 'totals' element exists, otherwise create it
    if (!isset($xml->totals)) {
        $totals = $xml->addChild('totals');
    } else {
        $totals = $xml->totals;
    }

    // Update the gender counts
    $totals->totalMembers = $totalMembers;
    $totals->male = $totalMale;
    $totals->female = $totalFemale;
    $totals->others = $totalOthers;

    // Save the updated XML file
    $xml->asXML($membersFile);
} else {
    $totalMembers = "N/A"; // Fallback if the file is not found
    $totalMale = "N/A";
    $totalFemale = "N/A";
    $totalOthers = "N/A";
}
?>

    
    <!-- Hamburger Menu -->
    <div class="hamburger-menu" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Overlay for when the menu is active -->
    <div class="overlay"></div>

    <div class="sidebar">
        <div class="sidebar-content">
            <div class="logo-container">
                <img src="logo.png" alt="Christ Strength Gym Logo" class="responsive-logo">
            </div>            
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="trainers.html"><i class="fas fa-dumbbell"></i> Trainers</a></li>
                <li><a href="memberslist.php"><i class="fas fa-users"></i> Members List</a></li>
                <li><a href="offers.html"><i class="fas fa-tags"></i> Gym Offers</a></li>
                <li><a href="#" onclick="openLogoutModal()"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
            </ul>
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
            <hr/>
                <ul>
                    <li><a href="#" onclick="openLocationsModal()"><i class="fas fa-map-marker-alt"></i> Locations</a></li>
                    <li><a href="about.html"><i class="fas fa-info-circle"></i> About</a></li>
                    <li><a href="contacts.html"><i class="fas fa-envelope"></i> Contacts</a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="main-content">
    <h1>Welcome to Christ Strength Gym!</h1>
        <!-- Dashboard Cards -->
        <div class="dashboard">
            <div class="card">
                <h3>Total Members</h3>
                <p><?php echo $totalMembers; ?></p>
            </div>
            <div class="card">
                <h3>Male</h3>
                <p><?php echo $totalMale; ?></p>
            </div>
            <div class="card">
                <h3>Female</h3>
                <p><?php echo $totalFemale; ?></p>
            </div>
            <div class="card">
                <h3>Others</h3>
                <p><?php echo $totalOthers; ?></p>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="calendar">
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
    </div>

    <!-- Modal -->
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-content"></div>
    </div>

    <!-- Locations Modal -->
    <div class="locations-modal-overlay" id="locationsModalOverlay" onclick="closeLocationsModal()"></div>
    <div class="locations-modal" id="locationsModal">
        <span class="locations-modal-close" onclick="closeLocationsModal()">&times;</span>
        <h2 style="text-align: center; margin-bottom: 15px;">Our Location</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d684.2872884998375!2d121.16143747783522!3d14.023144083794813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfil!2sph!4v1733241770911!5m2!1sfil!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <!-- About Modal -->
    <div class="about-modal-overlay" id="aboutModalOverlay" onclick="closeAboutModal()"></div>
    <div class="about-modal" id="aboutModal">
        <span class="about-modal-close" onclick="closeAboutModal()">&times;</span>
        <h2 style="text-align: center; margin-bottom: 15px;">About Christ Strength Gym</h2>
        <p>Christ Strength Gym is more than just a fitness center – we're a community dedicated to helping individuals transform their lives through fitness. Founded in 2010, our mission is to provide top-quality training, state-of-the-art equipment, and personalized support to help our members achieve their health and fitness goals.</p>
        <p>Our team of expert trainers brings years of experience and passion to help you build strength, improve health, and boost confidence. We believe in a holistic approach to fitness that goes beyond physical training, focusing on mental well-being and personal growth.</p>
    </div>

    <!-- Contact Modal -->
    <div class="contact-modal-overlay" id="contactModalOverlay" onclick="closeContactModal()"></div>
    <div class="contact-modal" id="contactModal">
        <span class="contact-modal-close" onclick="closeContactModal()">&times;</span>
        <h2 style="text-align: center; margin-bottom: 15px;">Contact Us</h2>
        <div class="contact-info">
            <p><strong>Phone:</strong> (555) 123-4567</p>
            <p><strong>Email:</strong> info@christstrengthgym.com</p>
            <p><strong>Address:</strong> 123 Fitness Street, Wellness City, HC 12345</p>
            <p><strong>Hours of Operation:</strong></p>
            <ul>
                <li>Monday-Friday: 5:00 AM - 10:00 PM</li>
                <li>Saturday-Sunday: 7:00 AM - 8:00 PM</li>
            </ul>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal-overlay" id="logoutModalOverlay" onclick="closeLogoutModal()"></div>
    <div class="logout-modal" id="logoutModal">
        <h2>Are you sure you want to log out?</h2>
        <div class="logout-modal-buttons">
            <button onclick="confirmLogout()">Yes</button>
            <button onclick="closeLogoutModal()">No</button>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const hamburgerMenu = document.querySelector(".hamburger-menu");
            const sidebar = document.querySelector(".sidebar");
            const modalOverlay = document.querySelector(".modal-overlay");
        
            // Toggle the sidebar visibility
            hamburgerMenu.addEventListener("click", () => {
                hamburgerMenu.classList.toggle("active");
                sidebar.classList.toggle("active");
                sidebar.classList.toggle("hidden");
            });
        
            // Close the sidebar if modal overlay is clicked (optional)
            modalOverlay?.addEventListener("click", () => {
                hamburgerMenu.classList.remove("active");
                sidebar.classList.remove("active");
                sidebar.classList.add("hidden");
            });

            // Add event listeners for new modals
            const aboutLink = document.querySelector('.sidebar-footer ul li a[href="about.html"]');
            const contactLink = document.querySelector('.sidebar-footer ul li a[href="contacts.html"]');
            
            if (aboutLink) {
                aboutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    openAboutModal();
                });
            }

            if (contactLink) {
                contactLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    openContactModal();
                });
            }
        });

        // Existing Modal Scripts...
        // (Previous openModal and closeModal scripts remain the same)

        // Locations Modal Functions (as before)
        function openLocationsModal() {
            const locationsModal = document.getElementById('locationsModal');
            const locationsModalOverlay = document.getElementById('locationsModalOverlay');
            
            locationsModal.style.display = 'block';
            locationsModalOverlay.style.display = 'block';
        }

        function closeLocationsModal() {
            const locationsModal = document.getElementById('locationsModal');
            const locationsModalOverlay = document.getElementById('locationsModalOverlay');
            
            locationsModal.style.display = 'none';
            locationsModalOverlay.style.display = 'none';
        }

        // About Modal Functions
        function openAboutModal() {
            const aboutModal = document.getElementById('aboutModal');
            const aboutModalOverlay = document.getElementById('aboutModalOverlay');
            
            aboutModal.style.display = 'block';
            aboutModalOverlay.style.display = 'block';
        }

        function closeAboutModal() {
            const aboutModal = document.getElementById('aboutModal');
            const aboutModalOverlay = document.getElementById('aboutModalOverlay');
            
            aboutModal.style.display = 'none';
            aboutModalOverlay.style.display = 'none';
        }

        // Contact Modal Functions
        function openContactModal() {
            const contactModal = document.getElementById('contactModal');
            const contactModalOverlay = document.getElementById('contactModalOverlay');
            
            contactModal.style.display = 'block';
            contactModalOverlay.style.display = 'block';
        }

        function closeContactModal() {
            const contactModal = document.getElementById('contactModal');
            const contactModalOverlay = document.getElementById('contactModalOverlay');
            
            contactModal.style.display = 'none';
            contactModalOverlay.style.display = 'none';
        }

        // Log Out Modal Functions
        function openLogoutModal() {
        const logoutModal = document.getElementById('logoutModal');
        const logoutModalOverlay = document.getElementById('logoutModalOverlay');
        
        logoutModal.style.display = 'block';
        logoutModalOverlay.style.display = 'block';
        }

        function closeLogoutModal() {
        const logoutModal = document.getElementById('logoutModal');
        const logoutModalOverlay = document.getElementById('logoutModalOverlay');
            
        logoutModal.style.display = 'none';
        logoutModalOverlay.style.display = 'none';
        }

        function confirmLogout() {
            window.location.href = 'USER INTERFACE/register-logins.html'; // Replace with actual logout endpoint if necessary
        }

        // Bio information for each trainer (remains the same)
        const bioData = [
            // ... (previous bioData remains unchanged)
        ];

        // Generate Calendar
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

            for (let i = 0; i < totalCells; i++) {
                if (i % 7 === 0) {
                    const row = document.createElement('tr');
                    calendarTable.appendChild(row);
                }

                const cell = document.createElement('td');
                if (i >= firstDay && currentDay <= daysInMonth) {
                    cell.textContent = currentDay;
                    if (currentDay === currentDate) {
                        cell.classList.add('today');
                    }
                    currentDay++;               
                }
                calendarTable.lastChild.appendChild(cell);
            }
        }

        // Initialize Calendar
        generateCalendar();
    </script>
    <script>
        window.embeddedChatbotConfig = {
        chatbotId: "g8kGxA_OKnyHBU8oL_sRL",
        domain: "www.chatbase.co"
        }
    </script>
    <script
        src="https://www.chatbase.co/embed.min.js"
        chatbotId="g8kGxA_OKnyHBU8oL_sRL"
        domain="www.chatbase.co"
        defer>
    </script>
</body>
</html>