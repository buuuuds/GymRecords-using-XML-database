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
                <li><a href="register-logins.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
            </ul>
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
                                <th>Trainor</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //load xml file
                            $file = simplexml_load_file('files/members.xml');
                            
                            foreach($file->user as $row){
                                ?>
                                <tr>
                                    <td><?php echo $row->id; ?></td>
                                    <td><?php echo $row->name; ?></td>
                                    <td><?php echo $row->gender; ?></td>
                                    <td><?php echo $row->plan; ?></td>
                                    <td><?php echo $row->datestart; ?></td>
                                    <td><?php echo $row->countdown; ?></td>
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
        });

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
</html>