<?php include("../adminsession.php");
$pagename = "department_setting.php";
$title = "Department Setting";
$tblname = "department_master";
$tblpkey = "department_id";
$module = "Department Setting";
$submodule = "Department Setting List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {
    $department_ids    = $_POST['department_ids'] ?? [];
    $c_off_check       = $_POST['c_off_check'] ?? [];
    $allow_weekly_off  = $_POST['allow_weekly_off'] ?? [];
    // echo '<pre>';
    // print_r($_POST);
    // die;



    foreach ($department_ids as $dept_id) {

        $dept_id = (int)$dept_id;

        $cOff  = isset($c_off_check[$dept_id]) ? 1 : 0;
        $wOff  = isset($allow_weekly_off[$dept_id]) ? 1 : 0;

        $update_data = [
            'c_off_check'      => $cOff,
            'allow_weekly_off' => $wOff,
            'lastupdated'      => $createdate
        ];

        $where = [
            'department_id' => $dept_id,
            'unit_id'       => $unitid
        ];

        $obj->update_record($tblname, $where, $update_data);

        $update_employee = [
            'allow_weekly_off' => $wOff
        ];

        $where_employee = [
            'department_id' => $dept_id,
            'unit_id'       => $unitid
        ];
        $obj->update_record("employee_master", $where_employee, $update_employee);
    }

    echo "<script>location='$pagename?action=2'</script>";
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

                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span class="text-danger"></span></h5>
                                        </div>
                                        <p class="text-danger small mb-0">
                                            <strong>Note:</strong><br>
                                            • <strong>Allow C-Off : </strong> Weekly off & earning leave will be carried forward to next month
                                            (Weekly off valid for 3 months, Earning leave valid for 1 year).
                                            </strong>.<br>
                                            • <strong>Allow Weekly Off : </strong> Employees of that department will get weekly leave.
                                            If unchecked, weekly leave will not be provided.
                                        </p>


                                    </div>
                                </div>
                            </div>
                            <form action="" method="post">
                                <div class="card-body">
                                    <div class=" ">
                                        <table id="" class="display table table-sm table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Division Name</th>
                                                    <th>Sub Division Name</th>
                                                    <th>Department Name</th>
                                                    <th>Allow C-Off <input type="checkbox" id="check_all_coff" class="form-check-input" /></th>
                                                    <th>Allow Weekly Off <input type="checkbox" id="check_all_woff" class="form-check-input" /></th>
                                                </tr>
                                            </thead>
                                            <tbody><?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("SELECT dm.*, dv.division_name, sdv.sub_division_name FROM $tblname AS dm LEFT JOIN division_master AS dv ON dm.division_id = dv.division_id LEFT JOIN subdivision_master AS sdv ON dm.subdivision_id = sdv.subdivision_id WHERE dm.unit_id = '$unitid' ORDER BY dm.department_name ASC");
                                                    foreach ($res as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $slno++ ?></td>
                                                        <td><?php echo $row["division_name"]; ?></td>
                                                        <td><?php echo $row["sub_division_name"]; ?></td>
                                                        <td><?php echo $row["department_name"]; ?>

                                                            <input type="hidden" name="department_ids[]" value="<?= $row['department_id'] ?>">
                                                        </td>
                                                        <td>
                                                            <input class="form-check-input coff-checkbox"
                                                                type="checkbox"
                                                                name="c_off_check[<?= $row['department_id'] ?>]"
                                                                id="c_off_check_<?= $row['department_id'] ?>"
                                                                value="1"
                                                                <?= ($row['c_off_check'] == 1) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="c_off_check">
                                                        </td>

                                                        <td>
                                                            <input class="form-check-input woff-checkbox"
                                                                type="checkbox"
                                                                name="allow_weekly_off[<?= $row['department_id'] ?>]"
                                                                id="allow_weekly_off_<?= $row['department_id'] ?>"
                                                                value="1"
                                                                <?= ($row['allow_weekly_off'] == 1) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="allow_weekly_off"></label>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                                $chkadd = $obj->check_addBtn($pagename, $loginid);
                                if ($chkadd == 1) {
                                ?>
                                    <div class="col-lg-12 mb-3 mt-2 text-center">
                                        <br>

                                        <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('')">
                                        <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                    </div>
                                <?php } ?>
                            </form>
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
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });
            $('#check_all_coff').on('change', function() {
                $('.coff-checkbox').prop('checked', $(this).is(':checked'));
            });

            // Weekly Off Select All
            $('#check_all_woff').on('change', function() {
                $('.woff-checkbox').prop('checked', $(this).is(':checked'));
            });
        });
    </script>
</body>

</html>