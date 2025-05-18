<?php
session_start();

if (isset($_POST['add'])) {
    // Open XML file
    $users = simplexml_load_file('files/members.xml');
    $user = $users->addChild('user');
    
    // Collect input data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $plan = $_POST['plan']; // The selected plan
    $datestart = $_POST['datestart']; // The start date
    $endsubs = $_POST['endsubs']; // The calculated end date from the JavaScript function
    
    // If for any reason endsubs is not set via JavaScript, calculate it in PHP
    if (empty($endsubs)) {
        $startDate = new DateTime($datestart);
        $monthsToAdd = 0;

        // Calculate the number of months based on the plan
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

        // Add months to the start date
        $startDate->add(new DateInterval('P' . $monthsToAdd . 'M'));
        $endsubs = $startDate->format('Y-m-d');
    }

    // Calculate remaining days (countdown) from datestart to endsubs
    $startDate = new DateTime($datestart);
    $endDate = new DateTime($endsubs);
    $remainingDays = $startDate->diff($endDate)->days;

    // Add the data to the XML
    $user->addChild('id', $id);
    $user->addChild('name', $name);
    $user->addChild('gender', $gender);
    $user->addChild('plan', $plan);
    $user->addChild('datestart', $datestart);
    $user->addChild('endsubs', $endsubs);
    $user->addChild('countdown', $remainingDays);
    $user->addChild('trainor', $_POST['trainor']);
    
    // Prettify / Format XML and save
    $dom = new DomDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($users->asXML());
    $dom->save('files/members.xml');

    // Display success message with countdown using JavaScript alert
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
