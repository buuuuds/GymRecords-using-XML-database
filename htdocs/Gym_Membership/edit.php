<?php
if (isset($_POST['edit'])) {
    // Load the XML file
    $users = simplexml_load_file('files/members.xml');
    
    // Loop through each user to find the one to edit by matching the ID
    foreach ($users->user as $user) {
        if ($user->id == $_POST['id']) {
            // Update the user's details with the submitted form values
            $user->name = $_POST['name'];
            $user->gender = $_POST['gender'];
            $user->plan = $_POST['plan'];
            $user->datestart = $_POST['datestart'];
            
            // Recalculate the end subscription date (endsubs) based on the new plan and start date
            $startDate = new DateTime($user->datestart); // Use the updated datestart
            $plan = $_POST['plan']; // Get the updated plan value

            // Calculate the number of months based on the selected plan
            $monthsToAdd = 0;
            switch ($plan) {
                case "1month":
                    $monthsToAdd = 1;
                    break;
                case "2months":
                    $monthsToAdd = 2;
                    break;
                case "3months":
                    $monthsToAdd = 3;
                    break;
                case "6months":
                    $monthsToAdd = 6;
                    break;
                case "9months":
                    $monthsToAdd = 9;
                    break;
                case "1year":
                    $monthsToAdd = 12;
                    break;
            }

            // Add the number of months to the start date
            $startDate->add(new DateInterval('P' . $monthsToAdd . 'M'));
            $user->endsubs = $startDate->format('Y-m-d'); // Save the updated end subscription date
            
            // Update the trainer information
            $user->trainor = $_POST['trainor'];
            break;
        }
    }

    // Save the updated XML back to the file
    file_put_contents('files/members.xml', $users->asXML());

    // Output JavaScript for success message and redirect
    echo "<script>
        alert('Member updated successfully');
        window.location.href = 'memberslist.php';
    </script>";
    exit();
} else {
    // Output JavaScript for error message and redirect
    echo "<script>
        alert('Select member to edit first');
        window.location.href = 'memberslist.php';
    </script>";
    exit();
}
?>
