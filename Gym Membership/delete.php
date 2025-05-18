<?php
    session_start();
    $id = $_GET['id'];

    $users = simplexml_load_file('files/members.xml');

    // Create iterator to find the user by ID
    $index = 0;
    $i = 0;

    foreach ($users->user as $user) {
        if ($user->id == $id) {
            $index = $i;
            break;
        }
        $i++;
    }

    // Remove the user from the XML
    unset($users->user[$index]);
    file_put_contents('files/members.xml', $users->asXML());

    // Output JavaScript for the alert and redirect
    echo "<script>
        alert('Member deleted successfully');
        window.location.href = 'memberslist.php';
    </script>";
?>
