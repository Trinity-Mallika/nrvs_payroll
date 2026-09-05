<?php include("../adminsession.php");
$pagename = "employee_report.php";
$title = "Employee Report";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Report";
$submodule = "Employee Report";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = 'where 1=1';
if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit .= " and em.unit_id='$unit_id'";
    }
} else {
    $unit_id = "";
};
if (isset($_GET['designation_id'])) {
    $designation_id = $obj->test_input($_GET['designation_id']);
    if ($designation_id != '') {
        $crit .= " and em.designation_id='$designation_id'";
    }
} else {
    $designation_id = "";
};
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
};
 
if (isset($_GET['is_active'])) {
    $is_active = $obj->test_input($_GET['is_active']);
    if ($is_active != '') {
        $crit .= " and em.is_active='$is_active'";
    }
} else {
    $is_active = "";
};

if (isset($_GET['gender'])) {
    $gender = $obj->test_input($_GET['gender']);
    if ($gender != '') {
        $crit .= " and em.gender='$gender'";
    }
} else {
    $gender = "";
};
if (isset($_GET['month']) && isset($_GET['year'])) {

    $month = $obj->test_input($_GET['month']);
    $year  = $obj->test_input($_GET['year']);

    if ($month != '' && $year != '') {
        $crit .= " AND MONTH(em.date_of_joining)='$month' 
                   AND YEAR(em.date_of_joining)='$year'";
    }else {
    $month = date('n');
    $year = date('Y');
}
} else {
    $month = date('n');
    $year = date('Y');
}



$fieldMap = [
    1 => ['label' => 'Gender',         'key' => 'gender'],
    2 => ['label' => 'Date of Birth',    'key' => 'dob'],
    3 => ['label' => 'Age',    'key' => 'age'],
    4 => ['label' => 'Mobile Number',        'key' => 'mobile_no'],
    5 => ['label' => 'Present Salary',        'key' => 'basic_salary'],
    6 => ['label' => 'Department',        'key' => 'department_id'],
    7 => ['label' => 'Designation',        'key' => 'designation_id'],
    8 => ['label' => 'Date of Joining',        'key' => 'date_of_joining'],
    9 => ['label' => 'Shift Hours',        'key' => 'shift_id'],
];

$showFields = isset($_GET['show_field']) ? array_map('intval', $_GET['show_field']) : [];

// Table display ke liye
if (isset($_GET['submit'])) {
    if (!empty($showFields)) {
        $displayFields = $showFields;
    } else {
        // Submit hua but kuch select nahi → sab fields
        $displayFields = array_keys($fieldMap);
    }
} else {
    // Page first load → default sab fields
    $displayFields = array_keys($fieldMap);
}
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

                    <?php if (!isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_turnover_report.php" class="float-end btn btn-primary btn-sm">Back</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="get">
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label for="unit_id" class="form-label">Unit Name<span class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="unit_id" id="unit_id" onchange="get_department(this.value);">
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
                                                <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"></span></label>
                                                <select class="form-select chosen-select" name="department_id" id="department_id">
                                                    <option value="">All</option>

                                                </select>

                                            </div>

                                            <div class="col-lg-3 mb-3">
                                                <label for="designation_id" class="form-label">Designation<span
                                                        class="text-danger fw-bold"> </span></label>
                                                <select class="form-select form-select-sm chosen-select"
                                                    name="designation_id" id="designation_id">
                                                    <option value="">All</option>
                                                    <?php $res = $obj->executequery("Select * from designation_master where unit_id='$unitid' order by designation asc");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['designation_id']; ?>">
                                                            <?= $key['designation']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('designation_id').value =
                                                        '<?= $designation_id; ?>';
                                                </script>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Gender</label>
                                                <select class="form-select form-select-sm" name="gender"
                                                    id="gender">
                                                    <option value="">Select</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <script>
                                                    document.getElementById('gender').value = '<?= $gender ?>'
                                                </script>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Status</label>
                                                <select class="form-select form-select-sm" name="is_active"
                                                    id="is_active">
                                                    <option value="">Select</option>
                                                    <option value="0">Inactive</option>
                                                    <option value="1">Active</option> 
                                                </select>
                                                <script>
                                                    document.getElementById('is_active').value = '<?= $is_active ?>'
                                                </script>
                                            </div>
                                            <div class="col-md-3 md-2">
                                                <strong><label for="Fields">Fields<span class="text-danger fw-bold"></span></label></strong>
                                                <select id="show_field" name="show_field[]" class="form-control" multiple>
                                                    <?php
                                                    foreach ($fieldMap as $fid => $field) {
                                                        $selected = (in_array($fid, $showFields)) ? "selected" : "";
                                                        echo "<option value='$fid' $selected>{$field['label']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="">Joining Month/Year </label>
                                                <div class="d-flex">
                                                    <select name="month" id="month" class="form-select chosen-select">
                                                        <option value="">Select Month</option>
                                                        <option value="1">January</option>
                                                        <option value="2">February</option>
                                                        <option value="3">March</option>
                                                        <option value="4">April</option>
                                                        <option value="5">May</option>
                                                        <option value="6">June</option>
                                                        <option value="7">July</option>
                                                        <option value="8">August</option>
                                                        <option value="9">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11">November</option>
                                                        <option value="12">December</option>
                                                    </select>
                                                    
                                                    <select class="form-select chosen-select" name="year" id="year">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $startYear = 2025;
                                                        $endYear = 2100;
                                                        for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                            echo "<option value=\"$year1\">$year1</option>";
                                                        } ?>
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-3 mt-4">
                                                <input type="submit" name="submit" class="btn btn-primary add-btn" value="Search">
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
                        $smonth = date('n');
                        $syear = date('Y');
                        $firstDateOfMonth = date("Y-m-01", strtotime("$syear-$smonth-01"));
                        $lastDateOfMonth = date("Y-m-t", strtotime("$syear-$smonth-01")); 
                        $lastDatejoinMonth = date("Y-m-t", strtotime("$year-$month-01")); 
                        
                        if($is_active == 1){
                            $res = $obj->executequery("
                                SELECT 
                                    em.*, 
                                    dm.department_name,
                                    dem.designation AS current_designation,
                                    dem2.designation AS previous_designation,
                                    gm.grade_name,um.unit_name
                                FROM employee_master em
                                LEFT JOIN department_master dm 
                                ON em.department_id = dm.department_id
                                LEFT JOIN unit_master um 
                                ON em.unit_id = um.unit_id
                                LEFT JOIN designation_master dem 
                                ON em.designation_id = dem.designation_id
                                LEFT JOIN designation_master dem2 
                                ON em.employer_designation_id = dem2.designation_id
                                LEFT JOIN grade_master gm 
                                ON em.grade_id = gm.grade_id
                            
                                LEFT JOIN (
                                    SELECT a1.*
                                    FROM emp_active_status a1
                                    INNER JOIN (
                                        SELECT 
                                            emp_id,
                                            MAX(active_id) AS last_id
                                        FROM emp_active_status
                                        WHERE (
                                                YEAR(last_inactive_date) < '$year'
                                                OR (
                                                    YEAR(last_inactive_date) = '$year'
                                                    AND MONTH(last_inactive_date) <= '$month'
                                                )
                                            )
                                        GROUP BY emp_id
                                    ) a2 
                                    ON a1.active_id = a2.last_id
                                ) eas 
                                    ON eas.emp_id = em.emp_id

                            $crit And em.is_active = '1' 
                                AND em.date_of_joining <= '$lastDatejoinMonth'
                                    AND (
                                        em.resign_status != '1' 
                                        OR (
                                            em.resign_status = '1' 
                                            AND em.last_working_date >= '$firstDateOfMonth'
                                        )
                                    ) 
                                    AND (
                                        eas.active_id IS NULL
                                        OR eas.is_active = '1'
                                    )  
                                GROUP BY em.emp_id
                                ORDER BY em.emp_code"); 
                        }else{
                        $res = $obj->executequery("SELECT 
                                em.*,
                                dm.department_name,
                                dem.designation AS current_designation,
                                dem2.designation AS previous_designation,
                                gm.grade_name,um.unit_name
                            FROM $tblname AS em
                            LEFT JOIN department_master dm 
                                ON em.department_id = dm.department_id
                                   LEFT JOIN unit_master um 
                                ON em.unit_id = um.unit_id
                            LEFT JOIN designation_master dem 
                                ON em.designation_id = dem.designation_id
                            LEFT JOIN designation_master dem2 
                                ON em.employer_designation_id = dem2.designation_id
                            LEFT JOIN grade_master gm 
                                ON em.grade_id = gm.grade_id
                            $crit
                            And (em.resign_status != '1' OR (em.resign_status = '1' AND em.last_working_date >= CURDATE()))
                            ORDER BY em.emp_code ASC
                            "); 

                        }
                        
                        $count_res = count($res);
                    ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?>  <a href="employee_report.php"
                                                    class="float-end btn btn-primary btn-sm">Search Again</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="auto-scroll-wrapper"> 
                                        <div class="table-responsive">
                                            <h5 class="text-primary">Total Employee : <?= $count_res ?></h5>
                                            <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th>Sr No.</th>
                                                        <th>Unit Name</th>
                                                        <th>Employee Code</th>
                                                        <th>Employee Name</th>
                                                        <th>Father’s Name</th>
                                                        <?php
                                                        foreach ($displayFields as $fid) {
                                                            if (!isset($fieldMap[$fid])) continue;
                                                            echo "<th>{$fieldMap[$fid]['label']}</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $slno = 1;
                                                    foreach ($res as $row) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $slno++; ?></td>
                                                            <td><?= $row["unit_name"]; ?></td>
                                                            <td><?= $row["emp_code"]; ?></td>
                                                            <td> <?= ucfirst($row['first_name'] ?? ''); ?> <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                            <td><?php echo $row["father_name"]; ?></td>
                                                            <?php
                                                            foreach ($displayFields as $fid) {
                                                                if (!isset($fieldMap[$fid])) continue;

                                                                $key = $fieldMap[$fid]['key'];
                                                                if ($key == 'department_id') {
                                                                    $value = $row['department_name'] ?? '-';
                                                                } elseif ($key == 'designation_id') {
                                                                    $value = $row['current_designation'] ?? '-';
                                                                } elseif ($key == 'grade_id') {
                                                                    $value = $row['grade_name'] ?? '-';
                                                                } elseif ($key == 'age') {
                                                                    if (!empty($row['dob'])) {
                                                                        $dob = new DateTime($row['dob']);
                                                                        $today = new DateTime();
                                                                        $value = $today->diff($dob)->y; // age in years
                                                                    } else {
                                                                        $value = '-';
                                                                    }
                                                                } else {
                                                                    $value = $row[$key] ?? '-';
                                                                }
                                                                // Date format example
                                                            if (in_array($key, ['dob', 'date_of_joining', 'opening_date', 'pf_joining_date', 'esic_joining_date']) && !empty($value)) {
                                                                        $value = $obj->dateformatindia1($value);
                                                                    }
                                                            ?>
                                                                <td><?= $value ?></td>
                                                            <?php } ?>
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
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

            $('#show_field').select2({
                width: '100%'
            });

            get_department('<?= $unit_id ?>', '<?= $department_id ?>');
        });

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            imgpath = '<?php echo $imgpath; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';
            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master_emp.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&imgpath=' + imgpath + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        $("#tr_" + id).hide();
                        // alert(data);
                        // location.reload();
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