<?php include("../adminsession.php");
$pagename = "category_master.php";
$title = "Category Master";
$tblname = "category_master";
$tblpkey = "category_id";
$module = "Category Master";
$submodule = "Category Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {
    $category_name = $obj->test_input($_POST['category_name']);
    $short_name    = $obj->test_input($_POST['short_name']);
    $ot_formula    = $_POST['ot_formula'];
    $min_ot        = $_POST['min_ot'];
    $max_ot        = $_POST['max_ot'];
    $weekly_off1   = $_POST['weekly_off1'];
    $weekly_off2   = $_POST['weekly_off2'];

    $first_last_punch = isset($_POST['first_last_punch']) ? 1 : 0;
    $min_ot_enable = isset($_POST['min_ot_enable']) ? 1 : 0;
    $max_ot_enable = isset($_POST['max_ot_enable']) ? 1 : 0;
    $early_coming     = isset($_POST['early_coming']) ? 1 : 0;
    $late_going       = isset($_POST['late_going']) ? 1 : 0;

    $prefix_absent  = isset($_POST['prefix_absent']) ? 1 : 0;
    $suffix_absent  = isset($_POST['suffix_absent']) ? 1 : 0;
    $both_absent    = isset($_POST['both_prefix_suffix_absent']) ? 1 : 0;

    $late_rule_enable = isset($_POST['late_rule_enable']) ? 1 : 0;
    $late_action      = $_POST['late_action'] ?? '';
    $late_days        = $_POST['late_days'] ?? 0;

    $count = $obj->getvalfield(
        $tblname,
        "count(*)",
        "category_name='$category_name' and unit_id='$unitid' and $tblpkey!='$keyvalue'"
    );

    $form_data = array(
        "category_name" => $category_name,
        "short_name" => $short_name,
        "ot_formula" => $ot_formula,
        "min_ot" => $min_ot,
        "max_ot" => $max_ot,
        "min_ot_enable" => $min_ot_enable,
        "max_ot_enable" => $max_ot_enable,
        "weekly_off1" => $weekly_off1,
        "weekly_off2" => $weekly_off2,

        "first_last_punch" => $first_last_punch,
        "early_coming" => $early_coming,
        "late_going" => $late_going,

        "prefix_absent" => $prefix_absent,
        "suffix_absent" => $suffix_absent,
        "both_prefix_suffix_absent" => $both_absent,

        "late_rule_enable" => $late_rule_enable,
        "late_action" => $late_action,
        "late_days" => $late_days,

        "unit_id" => $unitid,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid
    );

    if ($count > 0) {
        $action = 4;
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;
            $obj->insert_record($tblname, $form_data);
            $action = 1;
        } else {
            $form_data["lastupdated"] = $createdate;
            $form_data["updatedby"] = $loginid;
            $obj->update_record($tblname, [$tblpkey => $keyvalue], $form_data);
            $action = 2;
        }
    }
    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $row = $obj->select_record($tblname, [$tblpkey => $keyvalue]);

    $category_name = $row['category_name'];
    $short_name    = $row['short_name'];
    $ot_formula    = $row['ot_formula'];
    $min_ot        = $row['min_ot'];
    $max_ot        = $row['max_ot'];
    $min_ot_enable        = $row['min_ot_enable'];
    $max_ot_enable        = $row['max_ot_enable'];
    $weekly_off1   = $row['weekly_off1'];
    $weekly_off2   = $row['weekly_off2'];

    $first_last_punch = $row['first_last_punch'];
    $early_coming     = $row['early_coming'];
    $late_going       = $row['late_going'];

    $prefix_absent  = $row['prefix_absent'];
    $suffix_absent  = $row['suffix_absent'];
    $both_prefix_suffix_absent     = $row['both_prefix_suffix_absent'];

    $late_rule_enable = $row['late_rule_enable'];
    $late_action      = $row['late_action'];
    $late_days        = $row['late_days'];
} else {
    $category_name = "";
    $short_name = "";
    $ot_formula = "1";
    $min_ot = "30";
    $max_ot = "30";
    $min_ot_enable = "0";
    $max_ot_enable = "0";
    $first_last_punch = '0';
    $early_coming   = '0';
    $late_going    = '0';
    $prefix_absent  = '0';
    $suffix_absent  = '0';
    $both_prefix_suffix_absent     = '0';
    $late_rule_enable = '0';
    $late_action  = 'half_day';
    $late_days    = '3';
    $both_prefix_suffix_absent  = '0';
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
                        <div class="col-lg-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Category Details<a href="category_master_list.php" class="float-end btn btn-primary btn-sm">Category List</a></h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">

                                        <!-- Category Name -->
                                        <div class="col-lg-3 mb-3">
                                            <label>Category Name</label>
                                            <input type="text" name="category_name" class="form-control form-control-sm" value="<?= $category_name ?>">
                                        </div>

                                        <!-- Short Name -->
                                        <div class="col-lg-3 mb-3">
                                            <label>Short Name</label>
                                            <input type="text" name="short_name" class="form-control form-control-sm" value="<?= $short_name ?>">
                                        </div>

                                        <!-- OT Formula -->
                                        <div class="col-lg-3 mb-3">
                                            <label>OT Formula</label>
                                            <select class="form-select form-select-sm" name="ot_formula" id="ot_formula">
                                                <option value="1">Total Duration - Shift Hours</option>
                                                <option value="2">OT Not Applicable</option>
                                                <option value="3">Out Punch - Shift End Time</option>
                                                <option value="4">Early Coming Late Going</option>
                                            </select>
                                            <script>
                                                document.getElementById("ot_formula").value = '<?= $ot_formula ?>';
                                            </script>
                                        </div>

                                        <!-- Min OT -->
                                        <div class="col-lg-3 mb-3">
                                            <div class="d-flex   align-items-center">
                                                <label class="mb-0">Min OT (Min)</label>

                                                <div class="form-check ms-2">
                                                    <input class="form-check-input" type="checkbox" name="min_ot_enable" value="1"
                                                        <?= isset($min_ot_enable) && $min_ot_enable ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="number" name="min_ot" class="form-control form-control-sm" value="30" value="<?= $min_ot ?>">
                                        </div>

                                        <!-- Max OT -->
                                        <div class="col-lg-3 mb-3">
                                            <div class="d-flex  align-items-center">
                                                <label class="mb-0">Max OT (Min)</label>

                                                <div class="form-check ms-2">
                                                    <input class="form-check-input" type="checkbox" name="max_ot_enable" value="1"
                                                        <?= isset($max_ot_enable) && $max_ot_enable ? 'checked' : '' ?>>
                                                </div>
                                            </div>
                                            <input type="number" name="max_ot" class="form-control form-control-sm" value="30" value="<?= $max_ot ?>">
                                        </div>


                                        <!-- Weekly Off -->
                                        <div class="col-lg-3 mb-3">
                                            <label>Weekly Off 1</label>
                                            <select name="weekly_off1" class="form-select form-select-sm" id="weekly_off1">
                                                <option value="Sunday">Sunday</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Saturday">Saturday</option>
                                            </select>
                                            <script>
                                                document.getElementById("weekly_off1").value = '<?= $weekly_off1 ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label>Weekly Off 2</label>
                                            <select name="weekly_off2" id="weekly_off2" class="form-select form-select-sm">
                                                <option value="Saturday">Saturday</option>
                                                <option value="Sunday">Sunday</option>
                                                <option value="Friday">Friday</option>
                                            </select>
                                            <script>
                                                document.getElementById("weekly_off2").value = '<?= $weekly_off2 ?>';
                                            </script>
                                        </div>

                                        <!-- Checkboxes -->
                                        <div class="col-lg-12 mb-3">

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="first_last_punch" value="1" <?= $first_last_punch ? 'checked' : '' ?>>
                                                <label class="form-check-label">Consider Only First and Last Punch</label>
                                            </div>



                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="early_coming" value="1" <?= $early_coming ? 'checked' : '' ?>>
                                                <label class="form-check-label">Consider Early Coming Punch</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="late_going" value="1" <?= $late_going ? 'checked' : '' ?>>
                                                <label class="form-check-label">Consider Late Going Punch</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="prefix_absent" value="1" <?= $prefix_absent  ? 'checked' : '' ?>>
                                                <label class="form-check-label">Mark Holiday as Absent if Preflix Day is Absent</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="suffix_absent" value="1" <?= $suffix_absent  ? 'checked' : '' ?>>
                                                <label class="form-check-label">Mark Holiday as Absent If Suffix Day is Absent</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="both_prefix_suffix_absent" value="1" <?= $both_prefix_suffix_absent  ? 'checked' : '' ?>>
                                                <label class="form-check-label">Mark Holiday as Absent If Both Prefix and Suffix Day is Absent</label>
                                            </div>


                                        </div>

                                        <div class="col-lg-12 mb-3">

                                            <div class="d-flex align-items-center flex-wrap gap-2">

                                                <!-- Checkbox -->
                                                <div class="form-check m-0">
                                                    <input class="form-check-input" type="checkbox" id="late_rule_enable" name="late_rule_enable" value="1" <?= $late_rule_enable ? 'checked' : '' ?>>
                                                    <label class="form-check-label ms-1">
                                                        Mark
                                                    </label>
                                                </div>

                                                <!-- Action -->
                                                <div id="lateRuleBox" class="d-flex align-items-center gap-2">

                                                    <select name="late_action" class="form-select form-select-sm" style="width:140px;" id="late_action">
                                                        <option value="half_day">Half Day</option>
                                                        <option value="absent">Absent</option>
                                                    </select>

                                                    <span>when Late For</span>

                                                    <select name="late_days" class="form-select form-select-sm" style="width:100px;" id="late_days">
                                                        <option value="1">1 Day</option>
                                                        <option value="2">2 Days</option>
                                                        <option value="3">3 Days</option>
                                                        <option value="4">4 Days</option>
                                                        <option value="5">5 Days</option>
                                                        <option value="6">6 Days</option>
                                                    </select>

                                                </div>

                                            </div>

                                            <!-- Value Bind -->
                                            <script>
                                                document.getElementById("late_action").value = '<?= $late_action ?>';
                                                document.getElementById("late_days").value = '<?= $late_days ?>';
                                            </script>

                                        </div>
                                        <!-- Submit -->
                                        <div class="col-lg-3 mb-3">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> "
                                                onClick="return checkinputmaster('category_name,short_name,ot_formula')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

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
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

        });
    </script>
</body>

</html>