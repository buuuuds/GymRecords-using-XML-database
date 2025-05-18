<!-- Edit -->
<div class="modal fade" id="edit_<?php echo $row->id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center><h1 class="modal-title" id="myModalLabel">Edit Member</h1></center>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="edit.php" id="editForm">
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label class="control-label" style="position:relative; top:7px;">ID:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="id" value="<?php echo $row->id; ?>" readonly>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label class="control-label" style="position:relative; top:7px;">Name:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" value="<?php echo $row->name; ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label class="control-label" style="position:relative; top:7px;">Gender:</label>
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control" name="gender">
                                    <option value="Boy" <?php echo $row->gender == "Boy" ? "selected" : ""; ?>>Boy</option>
                                    <option value="Girl" <?php echo $row->gender == "Girl" ? "selected" : ""; ?>>Girl</option>
                                    <option value="Other" <?php echo $row->gender == "Other" ? "selected" : ""; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label class="control-label" style="position:relative; top:7px;">Plan:</label>
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control" name="plan" id="plan">
                                    <option value="1month" <?php echo $row->plan == "1month" ? "selected" : ""; ?>>1 Month</option>
                                    <option value="2months" <?php echo $row->plan == "2months" ? "selected" : ""; ?>>2 Months</option>
                                    <option value="3months" <?php echo $row->plan == "3months" ? "selected" : ""; ?>>3 Months</option>
                                    <option value="6months" <?php echo $row->plan == "6months" ? "selected" : ""; ?>>6 Months</option>
                                    <option value="9months" <?php echo $row->plan == "9months" ? "selected" : ""; ?>>9 Months</option>
                                    <option value="1year" <?php echo $row->plan == "1year" ? "selected" : ""; ?>>1 Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label class="control-label" style="position:relative; top:7px;">Date to Start:</label>
                            </div>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="datestart" id="datestart" value="<?php echo $row->datestart; ?>">
                                <!-- Hidden field for end subscription date -->
                                <input type="hidden" id="endsubs" name="endsubs" value="<?php echo $row->endsubs; ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label class="control-label" style="position:relative; top:7px;">Trainer:</label>
                            </div>
                            <div class="col-sm-10">
                                <select class="form-control" name="trainor">
                                    <option value="None" <?php echo $row->trainor == "None" ? "selected" : ""; ?>>None</option>
                                    <option value="trainer1" <?php echo $row->trainor == "trainer1" ? "selected" : ""; ?>>Trainer 1</option>
                                    <option value="trainer2" <?php echo $row->trainor == "trainer2" ? "selected" : ""; ?>>Trainer 2</option>
                                    <option value="trainer3" <?php echo $row->trainor == "trainer3" ? "selected" : ""; ?>>Trainer 3</option>
                                    <option value="trainer4" <?php echo $row->trainor == "trainer4" ? "selected" : ""; ?>>Trainer 4</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                <button type="submit" name="edit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Update</button>
            </form>
            </div>
        </div>
    </div>
</div>
<script>
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
 
<!-- Delete -->
<div class="modal fade" id="delete_<?php echo $row->id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center><h1 class="modal-title" id="myModalLabel">Delete Member</h1></center>
                <br>
            </div>
            <div class="modal-body">   
                <p class="text-center">Are you sure you want to Delete</p>
                <h1 class="text-center"><?php echo $row->name ?></h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                <button class="btn btn-danger" onclick="location.href='delete.php?id=<?php echo $row->id; ?>'"><span class="glyphicon glyphicon-trash"></span> Yes</button>
            </div>
        </div>
    </div>
</div>
