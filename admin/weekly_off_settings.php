<?php
include("../adminsession.php");
$pagename = "weekly_off_settings.php";
$module = "Weekly Off Setting";
$title = "Weekly Off Setting";
$submodule = "Weekly Off Setting";
$tblname = "weekly_off_setting";
$tblpkey = "weekly_off_setting_id";
$setting_type = 'week_off';


if (isset($_POST['submit'])) {

    $d1 = $obj->test_input($_POST['d1']);
    $d2 = $obj->test_input($_POST['d2']);
    $d3 = $obj->test_input($_POST['d3']);
    $d4 = $obj->test_input($_POST['d4']);
    $d5 = $obj->test_input($_POST['d5']);
    $w1 = $obj->test_input($_POST['w1']);
    $w2 = $obj->test_input($_POST['w2']);
    $w3 = $obj->test_input($_POST['w3']);
    $w4 = $obj->test_input($_POST['w4']);
    $w5 = $obj->test_input($_POST['w5']);


    $keyvalue = $obj->getvalfield(
        $tblname,
        $tblpkey,
        "setting_type='$setting_type' AND unit_id='$unitid'"
    );


    if (empty($keyvalue)) {
        $keyvalue = 0; // No record exists for this type
    }

    $form_data = [
        // 'week_off_Setting_r' => $week_off_Setting_r,
        'setting_type' => $setting_type,
        'd1' => $d1,
        'd2' => $d2,
        'd3' => $d3,
        'd4' => $d4,
        'd5' => $d5,
        'w1' => $w1,
        'w2' => $w2,
        'w3' => $w3,
        'w4' => $w4,
        'w5' => $w5,
        'unit_id' => $unitid,
        'createdate' => $createdate
    ];

    if ($keyvalue == 0) {
        $obj->insert_record($tblname, $form_data);
    } else {
        $where = [$tblpkey => $keyvalue];
        $obj->update_record($tblname, $where, $form_data);
    }

    echo "<script>location='$pagename'</script>";
}

// $week_off_Setting_r = $_GET['type'] ?? 'ESIC';
$sqledit = $obj->select_record($tblname, [
    'setting_type' => $setting_type,
    'unit_id' => $unitid
]);

$d1 = $sqledit['d1'] ?? '';
$d2 = $sqledit['d2'] ?? '';
$d3 = $sqledit['d3'] ?? '';
$d4 = $sqledit['d4'] ?? '';
$d5 = $sqledit['d5'] ?? '';
$w1 = $sqledit['w1'] ?? '';
$w2 = $sqledit['w2'] ?? '';
$w3 = $sqledit['w3'] ?? '';
$w4 = $sqledit['w4'] ?? '';
$w5 = $sqledit['w5'] ?? '';

?>




<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
    <?php include('inc/css.php') ?>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <!-- end auth-page-wrapper -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>
                <!-- <?php include('inc/alert.php') ?> -->
                <form method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-10">

                            <div class="card" id="customerList">

                                <div class="card-body">
                                    <div class="table-responsive">


                                        <!-- <div class="col-md-3 mb-2">
                                            <label><strong><?php echo $module ?></strong></label><br>
                                            <label><input type="radio" name="week_off_Setting_r" id="esic" value="ESIC" <?php if ($week_off_Setting_r == "ESIC") echo "checked"; ?>> ESIC</label>
                                            <label><input type="radio" name="week_off_Setting_r" id="non_esic" value="Non ESIC" <?php if ($week_off_Setting_r == "Non ESIC") echo "checked"; ?>> NON ESIC</label>
                                        </div> -->

                                        <table class="display table table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Leave</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                <tr>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="d1" value="<?php echo $d1; ?>"></td>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="w1" value="<?php echo $w1; ?>"></td>

                                                </tr>
                                                <tr>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="d2" value="<?php echo $d2; ?>"></td>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="w2" value="<?php echo $w2; ?>"></td>

                                                </tr>
                                                <tr>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="d3" value="<?php echo $d3; ?>"></td>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="w3" value="<?php echo $w3; ?>"></td>

                                                </tr>
                                                <tr>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="d4" value="<?php echo $d4; ?>"></td>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="w4" value="<?php echo $w4; ?>"> </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="d5" value="<?php echo $d5; ?>"></td>
                                                    <td><input type="number" class="form-control form-control-sm w-50" name="w5" value="<?php echo $w5; ?>"> </td>
                                                </tr>


                                            </tbody>

                                        </table>


                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-center">
                                            <button type="submit" name="submit" class="btn btn-sm btn-primary" onclick="return checkinputmaster('weekweek_off_Setting_r');">Update</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>

    <script>
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

</body>

<script>
    // function changeType(val) {
    //     let type = "<?= $week_off_Setting_r ?>";
    //     window.location.href =
    //         "weekly_off_settings.php?type=" + type + "&stype=" + val;
    // }

    // document.querySelectorAll('input[name="week_off_Setting_r"]').forEach(radio => {
    //     radio.addEventListener('change', function() {
    //         window.location.href =
    //             "weekly_off_settings.php?type=" + encodeURIComponent(this.value) +
    //             "&stype=<?= $setting_type ?>";
    //     });
    // });
</script>




</html>