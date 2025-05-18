<?php
session_start();

if (isset($_POST['add'])) {
    // Path to XML file
    $file = 'files/members.xml';

    // Load the XML file or create a new one if it doesn't exist
    if (file_exists($file)) {
        $users = simplexml_load_file($file);
    } else {
        $users = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><users></users>');
    }

    // Retrieve form values
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $plan = $_POST['plan'];
    $datestart = $_POST['datestart'];
    $trainor = $_POST['trainor'];

    // Calculate countdown (based on the selected plan duration)
    $planDurations = [
        '1month' => 30,
        '2months' => 60,
        '3months' => 90,
        '6months' => 180,
        '9months' => 270,
        '1year' => 365
    ];

    $daysToAdd = isset($planDurations[$plan]) ? $planDurations[$plan] : 0;
    $startDate = new DateTime($datestart);
    $endDate = $startDate->modify("+$daysToAdd days");
    $countdown = $endDate->format('Y-m-d');  // Save the end date (this can be adjusted to show days remaining as well if needed)

    // Add a new user to the XML
    $user = $users->addChild('user');
    $user->addChild('id', $id);
    $user->addChild('name', $name);
    $user->addChild('gender', $gender);
    $user->addChild('plan', $plan);
    $user->addChild('datestart', $datestart);
    $user->addChild('countdown', $countdown);  // Save the end date in the countdown field
    $user->addChild('trainor', $trainor);

    // Use DOMDocument to prettify and save the XML
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;

    // Load the SimpleXMLElement into DOMDocument
    $dom->loadXML($users->asXML());

    // Save the formatted XML back to file
    $dom->save($file);

    // Display success message using JavaScript alert
    echo "<script>
        alert('Member added successfully!');
        window.location.href = 'memberslist.php';
    </script>";
} else {
    // Display error message using JavaScript alert
    echo "<script>
        alert('Fill up the add form first!');
        window.location.href = 'memberslist.php';
    </script>";
}
?>
