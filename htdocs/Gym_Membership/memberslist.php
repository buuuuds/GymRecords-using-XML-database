<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Christ Stength Gym | Members List</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="memberslist.css">
    <link rel="icon" href="titlegymicon.jpg" type="x-icon">
    <!-- Add Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
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

        <!-- Main Content -->
        <main class="main-content">
            <h1>Members List</h1>
            <div class="table-header">
                <!-- Search bar -->
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search..."
                    class="search-bar"
                />
                <!-- 'New' button placed outside the search bar -->
            <a href="#addnew" class="btn new-button" data-toggle="modal">
                <span class="glyphicon glyphicon-plus"></span> New
            </a>

            </div>
            
            <!-- Members Table Section -->
            <div class="table-card">
                <div class="table-header">
                    <?php
                        session_start();
                        if(isset($_SESSION['message'])){
                            ?>
                            <div class="alert alert-info text-center" style="margin-top:20px;">
                                <?php echo $_SESSION['message']; ?>
                            </div>
                            <?php
                            unset($_SESSION['message']);
                        }
                    ?>
                    <table id="membersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Plan</th>
                                <th>Date of Start</th>
                                <th>Countdown</th>
                                <th>End Subscription</th>
                                <th>Trainor</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // Load XML file
                            $file = simplexml_load_file('files/members.xml');
                            
                            foreach ($file->user as $row) {
                                // Use datestart as the base date for the countdown calculation
                                $startDate = new DateTime($row->datestart);
                                $endDate = new DateTime($row->endsubs);
                                
                                // Calculate remaining days based on datestart
                                $remainingDays = $startDate > $endDate ? 0 : $startDate->diff($endDate)->days;

                                // Update the XML if remaining days are recalculated to 0
                                if ($remainingDays == 0 && $row->countdown != "0") {
                                    $row->countdown = "0";
                                    $dom = new DomDocument();
                                    $dom->preserveWhiteSpace = false;
                                    $dom->formatOutput = true;
                                    $dom->loadXML($file->asXML());
                                    $dom->save('files/members.xml');
                                }
                            ?>
                                <tr>
                                    <td><?php echo $row->id; ?></td>
                                    <td><?php echo $row->name; ?></td>
                                    <td><?php echo $row->gender; ?></td>
                                    <td><?php echo $row->plan; ?></td>
                                    <td><?php echo $row->datestart; ?></td>
                                    <td><?php echo $remainingDays > 0 ? $remainingDays . " days remaining" : "Expired"; ?></td>
                                    <td><?php echo $row->endsubs; ?></td>
                                    <td><?php echo $row->trainor; ?></td>
                                    <td>
                                        <a href="#edit_<?php echo $row->id; ?>" data-toggle="modal" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                        <a href="#delete_<?php echo $row->id; ?>" data-toggle="modal" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                    </td>
                                    <?php include('edit_delete_modal.php'); ?>
                                </tr>
                                <?php
                            }
                
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
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

        <?php include('add_modal.php'); ?>
        <script src="jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
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
            // Event listener for the search input field
            document.getElementById('searchInput').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase(); // Get the filter value
                let table = document.getElementById('membersTable');
                let rows = table.getElementsByTagName('tr'); // Get all rows in the table

                // Loop through all the table rows
                for (let i = 1; i < rows.length; i++) { // Start from index 1 to skip table headers
                    let cells = rows[i].getElementsByTagName('td'); // Get all cells in the row
                    let match = false;

                    // Loop through each cell in the row
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j] && cells[j].innerText.toLowerCase().includes(filter)) {
                            match = true; // If any cell matches, mark the row as a match
                            break;
                        }
                    }

                    // Show/hide the row based on the match
                    rows[i].style.display = match ? '' : 'none'; // Show if match, hide otherwise
                }
            });
            
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
</html>