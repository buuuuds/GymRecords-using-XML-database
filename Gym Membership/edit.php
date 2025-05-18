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
            $user->countdown = $_POST['countdown'];
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
