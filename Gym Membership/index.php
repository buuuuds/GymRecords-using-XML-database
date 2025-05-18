<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christ Stength Gym</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="titlegymicon.jpg" type="x-icon">
    <!-- Add Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php
    // Load XML file and count total members
    $membersFile = 'files/members.xml';
    $totalMembers = 0;

    if (file_exists($membersFile)) {
        $xml = simplexml_load_file($membersFile);
        $totalMembers = count($xml->user);
    } else {
        $totalMembers = "N/A"; // Fallback if the file is not found
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
                <li><a href="register-logins.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <!-- Dashboard Cards -->
        <div class="dashboard">
            <div class="card">
                <h3>Total Members</h3>
                <p><?php echo $totalMembers; ?></p>
            </div>
            <div class="card">
                <h3>New Members (Monthly)</h3>
                <p>34</p>
            </div>
            <div class="card">
                <h3>Total Revenue</h3>
                <p>$2,366</p>
            </div>
            <div class="card">
                <h3>Monthly Income</h3>
                <p>$1,200</p>
            </div>
            <div class="card">
                <h3>Expected Ratio</h3>
                <p>64%</p>
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
        });

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