<?php include("../adminsession.php");
$pagename = "emp_promotion_list.php";
$title = "Employee Promotion Request List";
$tblname = "emp_promotion";
$tblpkey = "emp_promotion_id";
$module = "Employee Promotion Request List";
$submodule = "Employee Promotion Request List";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit=" where 1=1";

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and em.unit_id = '$unit_id'";
    }
} else {
    $unit_id = $unitid;
};

if (isset($_GET['status1'])) {
    $status1 = $obj->test_input($_GET['status1']);
    if ($status1 != '') {
        $crit .= " and ep.status='$status1'";
    }
} else {
    $status1 = "";
};

 

if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " AND MONTH(ep.promotion_date) = '$month'";
    }
} else {
  $month = date('n'); 
}

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
      $crit .= " AND YEAR(ep.promotion_date) = '$year'";
    }
} else {
   $year  = date('Y');
};

if (isset($_POST['department_idd'])) {
    $department_id = $_POST['department_idd'];
    $unit_id = $_POST['unit_id'];
    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($unit_id != "" || $unit_id > 0) {
        $res = $obj->executequery("Select * from department_master where unit_id='$unit_id' order by department_name asc");
        foreach ($res as $row) {
        $selected = ($department_id == $row['department_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['department_id'] . "' $selected>" . $row['department_name'] . "  </option>";
        }
    }

    echo $options;
    die;
}

?>


<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
                    <?php
                    if (!isset($_GET['submit'])) {
                    ?>
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?>
                                                <a href="emp_promotion.php"
                                                    class="float-end btn btn-primary btn-sm">Show Single Details</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="unit_id" class="form-label">Unit Name<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="unit_id" id="unit_id"
                                                onchange="get_department(this.value);">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from unit_master order by unit_name asc");
                                                        foreach ($res as $key) {
                                                            echo "<option value='" . $key['unit_id'] . "'>" . $key['unit_name'] . "</option>";
                                                        } ?>
                                            </select>
                                            <script>
                                            document.getElementById('unit_id').value = '<?= $unit_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department Name<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="department_id"
                                                id="department_id">
                                                <option value="">All</option>

                                            </select>

                                        </div>

                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="month"
                                                id="month">
                                                <option value="">Select</option>
                                                <?php
                                                $months = [
                                                    1 => 'January',
                                                    2 => 'February',
                                                    3 => 'March',
                                                    4 => 'April',
                                                    5 => 'May',
                                                    6 => 'June',
                                                    7 => 'July',
                                                    8 => 'August',
                                                    9 => 'September',
                                                    10 => 'October',
                                                    11 => 'November',
                                                    12 => 'December'
                                                ];
                                                foreach ($months as $value => $name) {
                                                    echo "<option value=\"$value\">$name</option>";
                                                }
                                                ?>
                                            </select>
                                            <script>
                                            document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>
                                        <!-- Year -->
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year"
                                                id="year">
                                                <option value="">Select</option>
                                                <?php
                                                $startYear = 2025;
                                                $endYear = 2100;
                                                for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                    echo "<option value=\"$year1\">$year1</option>";
                                                } ?>
                                            </select>
                                            <script>
                                            document.getElementById('year').value = '<?php echo $year ?>'
                                            </script>
                                        </div>


                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select form-select-sm" name="status1" id="status1">
                                                <option value="">All</option>
                                                <option value="0">Pending</option>
                                                <option value="1">Approve</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <script>
                                            document.getElementById('status1').value = '<?= $status1 ?>'
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn"
                                                value="Search" onclick="">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php
                    if (isset($_GET['submit'])) {
                    ?>
                    <div class="col-lg-12">

                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h5 class="card-title mb-0"><?= $module; ?></h5>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <?php if ($month > 0 && $year > 0) { ?>
                                            <h5 class="mb-0">
                                                <?= date("F", mktime(0, 0, 0, $month, 1)) . " " . $year; ?>
                                            </h5>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="emp_promotion_list.php" class="float-end btn btn-primary btn-sm">Search
                                            Again</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="auto-scroll-wrapper">
                                    <div class="table-responsive">

                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Unit Name</th>
                                                    <th>Status</th>
                                                    <th>Employee Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Mobile No</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Promotion Date</th>
                                                    <th>Type</th>
                                                    <th>Prev Salary</th>
                                                    <th>Promotion Amt</th>
                                                    <th>New Salary</th>
                                                    <th>Effected Month/Year</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("SELECT ep.*,um.unit_name,em.emp_code,em.mobile_no,em.first_name,dm.department_name,dem.designation,cu.fullname as created_name,cu.username as created_username,cu.mobile as created_mobile, uu.fullname as updated_name, uu.username as updated_username,uu.mobile as updated_mobile FROM $tblname as ep LEFT JOIN employee_master em ON ep.emp_id=em.emp_id LEFT JOIN unit_master um ON um.unit_id=em.unit_id LEFT JOIN department_master dm ON ep.department_id=dm.department_id LEFT JOIN designation_master dem ON ep.designation_id=dem.designation_id LEFT JOIN user cu ON ep.createdby = cu.userid LEFT JOIN user uu ON ep.updatedby = uu.userid $crit"); 
                                                    foreach ($res as $row) {

                                                    ?>
                                                <tr id="tr_<?= $row["emp_promotion_id"]; ?>" data-details="
                                                        <div style='background:#dafced; padding:4px;'>
                                                        <?php if (!empty($row['created_name'])): ?>
                                                        Added by (User: <?= $row['created_name'] ?>,
                                                        Username: <?= $row['created_username'] ?>,
                                                        Mobile: <?= $row['created_mobile'] ?>,
                                                        Date: <?= $row['createdate'] ?>,)<br>
                                                        <?php endif; ?>

                                                        <?php if (!empty($row['updated_name'])): ?>
                                                        Last Edited by (User: <?= $row['updated_name'] ?>,
                                                        Username: <?= $row['updated_username'] ?>,
                                                        Mobile: <?= $row['updated_mobile'] ?>,
                                                        Date: <?= $row['lastupdated'] ?>) 
                                                        <?php endif; ?>
                                                        </div>
                                                    ">
                                                    <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $slno++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>
                                                    </td>
 
                                                    <td><?= $row["unit_name"]; ?></td>
                                                    <td>
                                                        <span
                                                            class="<?= $row['status'] == 1 ? 'badge bg-success' : ($row['status'] == 2 ? 'badge bg-danger' : 'badge bg-warning text-dark') ?>">
                                                            <?= $row['status'] == 1 ? 'Approved' : ($row['status'] == 2 ? 'Rejected' : 'Pending') ?>
                                                        </span>
                                                    </td>
                                                    <td><?= $row["emp_code"]; ?></td>
                                                    <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                        <?= ucfirst($row['last_name'] ?? ''); ?></td>

                                                    <td><?php echo $row['mobile_no']; ?></td>

                                                    <td><?php echo $row['department_name']; ?></td>
                                                    <td><?php echo $row['designation']; ?></td>
                                                    <td><?php echo $obj->dateformatindia($row["promotion_date"]); ?>
                                                    </td>

                                                    <td><?php echo $row['type']; ?></td>
                                                    <td><?php echo $row['prev_salary']; ?></td>
                                                    <td><?php echo $row['promote_amt']; ?></td>
                                                    <td><?php echo $row['basic_salary']; ?></td>
                                                    <td>
                                                        <?php
                                                            if ($row['effected_month'] != 0 && $row['effected_year'] != 0) {
                                                                echo date("M Y", mktime(0, 0, 0, $row['effected_month'], 1, $row['effected_year']));
                                                            }
                                                            ?>
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
                    <?php } ?>
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
        get_department('<?= $unit_id ?>', '<?= $department_id ?>');
    });



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


    $(document).ready(function() {

        var table = $('#buttons-datatables').DataTable();

        $('#buttons-datatables tbody').on('click', 'td.details-control', function() {

            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                var details = tr.data('details');
                row.child(details).show();
                tr.addClass('shown');
            }

        });

    });

    function get_department(unit_id, department_id = 0) {
        $.ajax({
            type: "POST",
            url: '',
            data: {
                department_idd: department_id,
                unit_id: unit_id,
            },
            success: function(data) {
                $('#department_id').html(data).trigger("change.select2");
            }
        });
    }
    </script>
</body>

</html>