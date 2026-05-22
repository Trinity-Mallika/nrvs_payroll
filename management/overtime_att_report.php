<?php include("../adminsession.php");
$pagename = "overtime_att_report.php";
$title = "Employee Reward Report";
$tblname = "employee_master";
$tblpkey = "emp_id";
$module = "Employee Reward Report";
$submodule = "Employee Reward Report";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit2 = 'where 1=1';
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit2 .= " and em.department_id = '$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['unit_id'])) {
    $unit_id = $obj->test_input($_GET['unit_id']);
    if ($unit_id != '') {
        $crit2 .= " and em.unit_id = '$unit_id'";
    }
} else {
    $unit_id = $unitid;
};
$unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
$month = date('m');
$year = date('Y');
$year_month = "";
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $obj->test_input($_GET['month']);

    $year = $obj->test_input($_GET['year']);
}
$showFields = [];

if (isset($_REQUEST['show_field_encoded'])) {

    if ($_REQUEST['show_field_encoded'] !== '') {
        $showFields = array_map(
            'intval',
            explode(',', $_REQUEST['show_field_encoded'])
        );
    } else {
        $showFields = [];
    }
} else {
    $showFields = [2, 3, 7, 8];
}


$fieldMap = [
    // 1 => ['label' => 'Mobile Number',     'key' => 'mobile_no'],
    2 => ['label' => 'Emp Code',         'key' => 'emp_code'],
    3 => ['label' => 'Emp Name',         'key' => 'first_name'],
    4 => ['label' => 'Aadhaar No',         'key' => 'aadhar_no'],
    5 => ['label' => 'Present Salary',    'key' => 'basic_salary'],
    6 => ['label' => 'Grade',             'key' => 'grade_name'],
    7 => ['label' => 'Department',        'key' => 'department_name'],
    8 => ['label' => 'Designation',       'key' => 'designation'],
    9 => ['label' => 'Date of Joining',   'key' => 'date_of_joining'],
    10 => ['label' => 'Job Location',       'key' => 'job_location'],
    11 => ['label' => 'Shift Hours',        'key' => 'shift_hours'],
];

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

                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form action="<?php echo $pagename; ?>" method="get">
                                <div class="card">
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
                                            <div class="col-md-3 md-2">
                                                <strong><label for="Month">Month<span class="text-danger fw-bold">*</span></label></strong></br>
                                                <select name="month" class="chosen-select form-control form-control" id="month">
                                                    <option value="">--Select Month--</option>
                                                    <?php for ($iM = 1; $iM <= 12; $iM++) {
                                                    ?>
                                                        <option value="<?php echo str_pad($iM, 2, '0', STR_PAD_LEFT); ?>"><?php echo date("F", strtotime(str_pad($iM, 2, '0', STR_PAD_LEFT) . "/12/10")); ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('month').value = '<?php echo $month; ?>';
                                                </script>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                                <select class="form-select chosen-select" name="year" id="year">
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

                                            <div class="col-md-3 md-2">
                                                <strong><label for="Fields">Fields<span class="text-danger fw-bold"></span></label></strong>
                                                <select id="show_field" class="form-control" multiple>
                                                    <!-- <option value="1">Mobile Number</option> -->
                                                    <option value="2">Emp Code</option>
                                                    <option value="3">Emp Name</option>
                                                    <option value="4">Aadhaar No</option>
                                                    <option value="5">Present Salary</option>
                                                    <option value="6">Grade</option>
                                                    <option value="7">Department</option>
                                                    <option value="8">Designation</option>
                                                    <option value="9">Date of Joining</option>
                                                    <option value="10">Job Location</option>
                                                    <option value="11">Shift Hours</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="show_field_encoded" id="show_field_encoded">

                                            <div class="col-md-3 mt-4 ">
                                                <input type="submit" class="btn btn-primary add-btn" onclick="return checkinputmaster('year,month')" name="search" value="Search">
                                                <a href="<?php echo $pagename; ?>" class="btn btn-danger" name="reset" id="reset">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                    <?php if (isset($_GET['search'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> </h5>
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
                                                    <th>Unit Name</th>
                                                    <?php
                                                    foreach ($showFields as $fid) {
                                                        if (!isset($fieldMap[$fid])) continue;
                                                        echo "<th>{$fieldMap[$fid]['label']}</th>";
                                                    }
                                                    ?>
                                                    <th>Date</th>
                                                    <th>Shift</th>
                                                    <th>Basic Salary</th>
                                                    <th>OT Type</th>
                                                    <th>Overtime</th>
                                                    <th>Approved By</th>
                                                    <th>OT Cost Cente</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                $fromDate = "$year-$month-01";
                                                $toDate   = date("Y-m-t", strtotime($fromDate));
                                                $res = $obj->executequery("SELECT 
                                            em.*,
                                            dm.department_name,
                                            dem.designation,
                                            sm.shift_name,
                                            gm.grade_name,
                                            ae.attendance_date,
                                            ae.attendance_status,
                                            ae.prev_attendance_status,
                                            ae.in_remark,
                                            ae.out_remark,
                                            ae.createdate,
                                            ae.entry_type,
                                            ae.machineid,
                                            ae.entry_type_out,
                                            ae.intime,
                                            ae.outtime,
                                            ae.lastupdated,
                                            ae.overtime,
                                            u.username,
                                            u.fullname, 
                                            u.mobile, 
                                            u.email
                                            FROM attendance_entry as ae

                                            LEFT JOIN employee_master em 
                                            ON ae.emp_id = em.emp_id

                                            LEFT JOIN user u 
                                            ON u.userid = ae.updateby

                                            LEFT JOIN department_master dm 
                                            ON em.department_id = dm.department_id

                                               LEFT JOIN shift_master sm 
                                            ON ae.shift_id = sm.shift_id

                                            LEFT JOIN designation_master dem 
                                            ON em.designation_id = dem.designation_id

                                            LEFT JOIN grade_master gm 
                                            ON em.grade_id = gm.grade_id

                                            $crit2 AND ae.attendance_date BETWEEN '$fromDate' AND '$toDate' AND TIME_TO_SEC(ae.overtime) > 0

                                            ORDER BY em.emp_code ASC
                                            ");

                                                foreach ($res as $row) {
                                                ?>
                                                    <tr id="tr_<?= $row["emp_id"]; ?>">
                                                        <td><?php echo $slno++; ?></td>
                                                        <td><?php echo $unit_name; ?></td>

                                                        <?php
                                                        foreach ($showFields as $fid) {

                                                            if (!isset($fieldMap[$fid])) continue;

                                                            $key = $fieldMap[$fid]['key'];

                                                            $value = $row[$key] ?? '-';

                                                            if ($fid == 9 && !empty($value)) {
                                                                $value = $obj->dateformatindia($value);
                                                            }

                                                            echo "<td>{$value}</td>";
                                                        }
                                                        ?>
                                                        <td><?= $obj->dateformatindia($row["attendance_date"]); ?></td>
                                                        <td> <?= $row['shift_name']; ?> </td>
                                                        <td><?= $row["basic_salary"]; ?></td>
                                                        <td><?= $row['entry_type']; ?></td>
                                                        <td><?= $row['overtime']; ?></td>
                                                        <td>
                                                            <!-- <?php if (!empty($row['username'])) { ?>
                                                                <strong><?= $row['fullname'] ?> </strong>
                                                                <div style='font-size:12px;color:#666'>
                                                                    User: <?= $row['username'] ?><br>
                                                                    Mobile: <?= $row['mobile'] ?><br>
                                                                    Email: <?= $row['email'] ?>
                                                                </div>
                                                            <?php   }   ?> -->
                                                        </td>

                                                        <td> </td>


                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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
            $(".chosen-select").select2();
            const $select = $("#show_field").select2({
                placeholder: "Select Fields",
                width: "100%",
                closeOnSelect: false
            });

            const selectedFields = <?= json_encode($showFields) ?> || [];
            if (selectedFields.length) {
                $select.val(selectedFields.map(String)).trigger("change");
            }

            $("form").on("submit", function() {
                const selected = $select.val() || [];
                $("#show_field_encoded").val(selected.join(","));
                $select.removeAttr("name");
            });

            get_department('<?= $unit_id ?>', '<?= $department_id ?>');

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