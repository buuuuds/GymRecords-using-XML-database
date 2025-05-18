<!-- Add New -->
<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center><h1 class="modal-title text-center" id="myModalLabel">Add New Member</h1></center>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="add.php">
                        <!-- ID -->
                        <div class="form-group row">
                            <label for="id" class="col-sm-3 col-form-label text-right">ID:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id" name="id" placeholder="Enter ID" required>
                            </div>
                        </div>
                        <!-- Name -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label text-right">Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                            </div>
                        </div>
                        <!-- Gender -->
                        <div class="form-group row">
                            <label for="gender" class="col-sm-3 col-form-label text-right">Gender:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <!-- Plan -->
                        <div class="form-group row">
                            <label for="plan" class="col-sm-3 col-form-label text-right">Plan:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="plan" name="plan" required>
                                    <option value="" disabled selected>Select Plan</option>
                                    <option value="1month">1 Month</option>
                                    <option value="2months">2 Months</option>
                                    <option value="3months">3 Months</option>
                                    <option value="6months">6 Months</option>
                                    <option value="9months">9 Months</option>
                                    <option value="1year">1 Year</option>
                                </select>
                            </div>
                        </div>
                        <!-- Date to Start -->
                        <div class="form-group row">
                            <label for="datestart" class="col-sm-3 col-form-label text-right">Date to Start:</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="datestart" name="datestart" required>
                            </div>
                        </div>
                        <!-- Countdown -->
                        <div class="form-group row">
                            <label for="countdown" class="col-sm-3 col-form-label text-right">Countdown:</label>
                            <div class="col-sm-9">
                                <span id="countdown" class="form-control-static bg-light p-2 rounded" style="border: 1px solid #ccc;">0 days remaining</span>
                            </div>
                        </div>
                        <!-- Trainers -->
                        <div class="form-group row">
                            <label for="trainor" class="col-sm-3 col-form-label text-right">Trainer:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="trainor" name="trainor" required>
                                    <option value="n/a">N/A</option>
                                    <option value="trainer1">Trainer 1</option>
                                    <option value="trainer2">Trainer 2</option>
                                    <option value="trainer3">Trainer 3</option>
                                    <option value="trainer4">Trainer 4</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> Cancel
                </button>
                <button type="submit" name="add" class="btn btn-primary">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Save
                </button>
                    </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Plan durations in days
const planDurations = {
    '1month': 30,
    '2months': 60,
    '3months': 90,
    '6months': 180,
    '9months': 270,
    '1year': 365
};

// Function to calculate and display the countdown
function calculateCountdown(startDateStr, plan) {
    const startDate = new Date(startDateStr);
    const daysToAdd = planDurations[plan] || 0;

    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + daysToAdd);

    const countdownSpan = document.getElementById('countdown');
    
    // Update countdown every second
    const interval = setInterval(function () {
        const now = new Date();
        const timeDifference = endDate - now;
        const daysLeft = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
        
        if (daysLeft > 0) {
            countdownSpan.textContent = `${daysLeft} days remaining`;
        } else {
            countdownSpan.textContent = 'Plan ended';
            clearInterval(interval); // Stop the interval once the plan ends
        }
    }, 1000); // Update every second
}

// Fetch and parse XML data (for demonstration purposes)
function fetchXMLData() {
    fetch('data.xml') // Fetch the XML data from the server
        .then(response => response.text())
        .then(xmlStr => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlStr, 'application/xml');

            // Assuming there's one subscription in the XML
            const subscription = xmlDoc.querySelector('subscription');
            const datestart = subscription.querySelector('datestart').textContent;
            const plan = subscription.querySelector('plan').textContent;

            // Calculate and display the countdown based on the fetched data
            calculateCountdown(datestart, plan);
        })
        .catch(error => console.error('Error fetching XML:', error));
}

// Load countdown on page load
document.addEventListener('DOMContentLoaded', function () {
    fetchXMLData();
});

// Update countdown dynamically on form input changes
document.getElementById('datestart').addEventListener('change', function () {
    const selectedPlan = document.querySelector('select[name="plan"]').value;
    calculateCountdown(this.value, selectedPlan);
});

document.querySelector('select[name="plan"]').addEventListener('change', function () {
    const dateInput = document.getElementById('datestart');
    if (dateInput.value) {
        calculateCountdown(dateInput.value, this.value);
    }
});

</script>

