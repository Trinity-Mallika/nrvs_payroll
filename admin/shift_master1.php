<?php include("../adminsession.php");
$pagename = "shift_master.php";
$title = "SHIFT MASTER";
$tblname = "shift_master";
$tblpkey = "shift_id";
$module = "Shift Master";
$submodule = "Shift Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {

    $shift_name     = $obj->test_input($_POST['shift_name']);
    $in_time         = $_POST['in_time'];
    $out_time        = $_POST['out_time'];
    $lunch_time        = $_POST['lunch_time'];
    $grace_time_in  = intval($_POST['grace_time_in']);
    $grace_time_out = intval($_POST['grace_time_out']);
    $working_hour    = $_POST['working_hour'];
    $total_working_hour    = $_POST['total_working_hour'];
    $min_working_hrs    = $_POST['min_working_hrs'];
    $is_cross_day = isset($_POST['is_cross_day']) ? 1 : 0;
    $count = $obj->getvalfield($tblname, "count(*)", "shift_name='$shift_name' and unit_id='$unitid' and $tblpkey!='$keyvalue'");

    $form_data = array(
        'shift_name'     => $shift_name,
        'is_cross_day'     => $is_cross_day,
        'in_time'         => $in_time,
        'out_time'        => $out_time,
        'lunch_time'        => $lunch_time,
        'grace_time_in'        => $grace_time_in,
        'grace_time_out'        => $grace_time_out,
        'working_hour'  => $working_hour,
        'total_working_hour'  => $total_working_hour,
        'min_working_hrs'  => $min_working_hrs,
        'ipaddress'       => $ipaddress,
        "sessionid"   => $sessionid,
        "unit_id" => $unitid,
        'createdby'       => $loginid
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        } else {
            $form_data["lastupdated"] = $createdate;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
            $action = 2;
            $process = "updated";
        }
    }
    // die;
    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $shift_name     = $sqledit['shift_name'];
    $in_time         = $sqledit['in_time'];
    $out_time        = $sqledit['out_time'];
    $lunch_time        = $sqledit['lunch_time'];
    $grace_time_in        = $sqledit['grace_time_in'];
    $grace_time_out        = $sqledit['grace_time_out'];
    $working_hour    = $sqledit['working_hour'];
    $total_working_hour    = $sqledit['total_working_hour'];
    $min_working_hrs    = $sqledit['min_working_hrs'];
    $is_cross_day    = $sqledit['is_cross_day'];
} else {
    $shift_name = "";
    $in_time = "";
    $out_time = "";
    $lunch_time = "";
    $grace_time_in = "";
    $grace_time_out = "";
    $working_hour = "";
    $total_working_hour = "";
    $max_working_hrs = "";
    $min_working_hrs = "";
    $is_cross_day = "0";
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
</head>
<style>
    .table-borderless tr td {
        border: 0px !important;
        padding-bottom: 0px;
    }
</style>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <form method="post" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <strong><label for="shift_name">Shift Name<span class="text-danger">*</span></label></strong>
                                            <input type="text" name="shift_name" id="shift_name"
                                                class="form-control form-control-sm "
                                                value="<?php echo $shift_name; ?>"
                                                autocomplete="off" placeholder="Shift Name" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <strong><label for="in_time">In Time<span class="text-danger">*</span></label></strong>
                                            <input type="time" name="in_time" id="in_time"
                                                class="form-control form-control-sm "
                                                value="<?php echo $in_time; ?>" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <strong><label for="out_time">Out Time<span class="text-danger">*</span></label></strong>
                                            <input type="time" name="out_time" id="out_time"
                                                class="form-control form-control-sm "
                                                value="<?php echo $out_time; ?>" />
                                        </div>



                                        <div class="col-lg-3 mb-3">
                                            <strong><label for="working_hour">Actual Working Hour<span class="text-danger">*</span></label></strong>
                                            <input type="text" name="working_hour" id="working_hour"
                                                class="form-control form-control-sm "
                                                value="<?php echo $working_hour; ?>" readonly />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <strong><label>Lunch Time (Minutes)<span class="text-danger"> </span></label></strong>
                                            <input type="number" name="lunch_time" id="lunch_time"
                                                class="form-control form-control-sm" min="0" step="1"
                                                value="<?php echo $lunch_time; ?>" oninput="limitTwoDigits(this);calculateWorkingHour();" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <strong><label for="total_working_hour">Total Working Hour<span class="text-danger">*</span></label></strong>
                                            <input type="text" name="total_working_hour" id="total_working_hour"
                                                class="form-control form-control-sm"
                                                value="<?php echo $total_working_hour; ?>" readonly />
                                        </div>



                                        <div class="col-lg-3 mb-3">
                                            <strong><label>Grace Time In (Minutes)<span class="text-danger">*</span></label></strong>
                                            <input type="number" name="grace_time_in" id="grace_time_in"
                                                class="form-control form-control-sm" min="0" step="1"
                                                value="<?php echo $grace_time_in; ?>" oninput="limitTwoDigits(this)" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <strong><label>Grace Time Out (Minutes)<span class="text-danger">*</span></label></strong>
                                            <input type="number" name="grace_time_out" id="grace_time_out"
                                                class="form-control form-control-sm" min="0" step="1"
                                                value="<?php echo $grace_time_out; ?>" oninput="limitTwoDigits(this)" />
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <strong>
                                                <label for="min_working_hrs">Half Day Working Hour<span class="text-danger">*</span></label>
                                            </strong>
                                            <input type="text" name="min_working_hrs" id="min_working_hrs"
                                                class="form-control form-control-sm"
                                                placeholder="HH:MM:SS"
                                                value="<?php echo $min_working_hrs; ?>"
                                                onkeypress="return timeFormat(event, this)">
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <strong>
                                                <label for="max_working_hrs">Full Day Working Hour<span class="text-danger">*</span></label>
                                            </strong>
                                            <input type="text" name="max_working_hrs" id="max_working_hrs"
                                                class="form-control form-control-sm"
                                                placeholder="HH:MM:SS"
                                                value="<?php echo $max_working_hrs; ?>"
                                                readonly>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <strong><label>Cross Day Shift<span class="text-danger">*</span></label></strong><br>
                                            <input type="checkbox"
                                                name="is_cross_day"
                                                id="is_cross_day"
                                                value="1" <?php if ($is_cross_day == 1) echo "checked"; ?>> <strong><label for="is_cross_day">Is Cross Day Shift</label></strong><br>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('shift_name,in_time,out_time,min_working_hrs')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span class="text-danger"></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Shift Name</th>
                                                <th>In Time</th>
                                                <th>Out Time</th>
                                                <th>Actual Working Hour</th>
                                                <th>Lunch Time (Minuts)</th>
                                                <th>Total Working Hour</th>
                                                <th>Half Day Working Hour</th>
                                                <th>Grace Time In</th>
                                                <th>Grace Time Out</th>
                                                <th>Is Cross Day</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                                $slno = 1;
                                                $res = $obj->executequery("select * from $tblname where unit_id='$unitid' order by $tblpkey desc");
                                                foreach ($res as $row) {  ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?php echo $row['shift_name']; ?></td>
                                                    <td>
                                                        <?= !empty($row['in_time']) && $row['in_time'] != '00:00:00'
                                                            ? date("h:i A", strtotime($row['in_time']))
                                                            : '-' ?>
                                                    </td>

                                                    <td>
                                                        <?= !empty($row['out_time']) && $row['out_time'] != '00:00:00'
                                                            ? date("h:i A", strtotime($row['out_time']))
                                                            : '-' ?>
                                                    </td>
                                                    <td><?php echo $row['working_hour']; ?> Hrs</td>
                                                    <td>
                                                        <?php
                                                        if (!empty($row['lunch_time'])) {
                                                            echo $row['lunch_time'] . ' Minuts';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['total_working_hour']; ?> Hrs</td>
                                                    <td><?php echo $row['min_working_hrs']; ?> Hrs</td>
                                                    <td><?php echo $row['grace_time_in']; ?></td>
                                                    <td><?php echo $row['grace_time_out']; ?></td>
                                                    <td><?php echo $row['is_cross_day'] == 1 ? 'Yes' : 'No'; ?></td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <?php $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                            if ($chkedit == 1) {  ?>
                                                                <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                    <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn"><i class="ri-pencil-fill align-bottom text-success"></i></a>
                                                                </li>
                                                            <?php }
                                                            $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                            if ($chkdel == 1) {  ?>
                                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                    <a class="remove-item-btn" type="button" onclick="funDel(<?php echo $row[$tblpkey]; ?>);">
                                                                        <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                    </a>
                                                                </li>
                                                            <?php  } ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").chosen({
                width: '100%',
                search_contains: true
            });

            calculateWorkingHour();

            function calcWorkingHours() {

                let inTime = $("#in_time").val();
                let outTime = $("#out_time").val();

                // Jab tak dono filled na ho
                if (!inTime || !outTime) {
                    $("#working_hour").val('');
                    return;
                }

                let [inH, inM] = inTime.split(':').map(Number);
                let [outH, outM] = outTime.split(':').map(Number);

                let start = inH * 60 + inM;
                let end = outH * 60 + outM;

                // Validation
                // if (end <= start) {
                //     alert("Out Time must be greater than In Time");
                //     $("#working_hour").val('');
                //     return;
                // }
                if (end <= start) {
                    end += 24 * 60; // add 24 hours
                }

                let diff = end - start;

                let hours = Math.floor(diff / 60);
                let minutes = diff % 60;

                $("#working_hour").val(
                    String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0')
                );
            }
            // Event bindings
            $("#in_time, #out_time").on("change", calcWorkingHours);
        });

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename; ?>';
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

        function numberOnly(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\.|\s/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>
    <script>
        function limitTwoDigits(el) {
            if (el.value.length > 3) {
                el.value = el.value.slice(0, 3);
            }
        }

        function calculateWorkingHour() {

            let workingHour = document.getElementById("working_hour").value;
            let lunchMinutes = parseInt(document.getElementById("lunch_time").value) || 0;

            if (workingHour) {

                let parts = workingHour.split(":");
                let hours = parseInt(parts[0]);
                let minutes = parseInt(parts[1]);
                let seconds = parts[2] ? parseInt(parts[2]) : 0;

                // total minutes convert
                let totalMinutes = (hours * 60) + minutes;

                // lunch time subtract
                let finalMinutes = totalMinutes - lunchMinutes;

                if (finalMinutes < 0) finalMinutes = 0;

                // convert back
                let finalHours = Math.floor(finalMinutes / 60);
                let finalMins = finalMinutes % 60;
                let finalSecs = seconds;

                finalHours = String(finalHours).padStart(2, '0');
                finalMins = String(finalMins).padStart(2, '0');
                finalSecs = String(finalSecs).padStart(2, '0');

                document.getElementById("total_working_hour").value = finalHours + ":" + finalMins + ":" + finalSecs;
            }
        }

        function timeFormat(e, input) {
            let char = String.fromCharCode(e.which);

            // Allow only numbers
            if (!/[0-9]/.test(char)) {
                return false;
            }

            let value = input.value;

            // Auto add colon
            if (value.length === 2 || value.length === 5) {
                input.value = value + ":";
            }

            // Max length 8 (HH:MM:SS)
            if (value.length >= 8) {
                return false;
            }

            return true;
        }
    </script>
</body>

</html>