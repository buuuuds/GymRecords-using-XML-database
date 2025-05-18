<!-- Add New -->
<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center><h1 class="modal-title" id="myModalLabel">Add New Member</h1><center>
            </div>
            <div class="modal-body">
                        <div class="container-fluid">
                        <form method="POST" action="add.php">
                        <!-- ID -->
                        <div class="form-group">
                            <label for="id" class="col-sm-3 col-form-label text-right">ID:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="id" name="id" required>
                            </div>
                        </div>
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 col-form-label text-right">Name:</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name.." required pattern="[a-zA-Z\s]+" title="Only letters are allowed." oninput="validateName(this)">
                            </div>
                        </div>
                        <!-- Gender -->
                        <div class="form-group">
                            <label for="gender" class="col-sm-3 col-form-label text-right">Gender:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Boy">Boy</option>
                                    <option value="Girl">Girl</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <!-- Plan -->
                        <div class="form-group">
                            <label for="plan" class="col-sm-3 col-form-label text-right">Plan:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="plan" name="plan" required onchange="calculateEndDate()">
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
                        <div class="form-group">
                            <label for="datestart" class="col-sm-3 col-form-label text-right">Date to Start:</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="datestart" name="datestart" required onchange="calculateEndDate()">
                                <!-- Hidden field for end subscription date -->
                                <input type="hidden" id="endsubs" name="endsubs" value="">
                            </div>
                        </div>

                        <!-- Trainers -->
                        <div class="form-group">
                            <label for="trainor" class="col-sm-3 col-form-label text-right">Trainer:</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="trainor" name="trainor" required>
                                    <option value="None">None</option>
                                    <option value="trainer1">Trainer 1</option>
                                    <option value="trainer2">Trainer 2</option>
                                    <option value="trainer3">Trainer 3</option>
                                    <option value="trainer4">Trainer 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                <button type="submit" name="add" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validateName(input) {
    // Remove any characters that are not letters or spaces
    input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
}
function calculateEndDate() {
    var plan = document.getElementById("plan").value;
    var startDate = document.getElementById("datestart").value;

    if (plan && startDate) {
        var start = new Date(startDate);
        var monthsToAdd = 0;

        // Determine the months to add based on the selected plan
        switch (plan) {
            case "1month":
                monthsToAdd = 1;
                break;
            case "2months":
                monthsToAdd = 2;
                break;
            case "3months":
                monthsToAdd = 3;
                break;
            case "6months":
                monthsToAdd = 6;
                break;
            case "9months":
                monthsToAdd = 9;
                break;
            case "1year":
                monthsToAdd = 12;
                break;
        }

        // Add the months to the start date
        start.setMonth(start.getMonth() + monthsToAdd);

        // Format the end date (in YYYY-MM-DD format)
        var endDate = start.toISOString().split('T')[0];

        // Set the calculated end date into a hidden field or display it on the form
        document.getElementById("endsubs").value = endDate;
    }
}
</script>
