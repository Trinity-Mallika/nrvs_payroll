<?php include("../adminsession.php");
$pagename = "salary_generate_detail.php";
$title = "Salary Generate Detail";
$module = "Salary Generate Detail";
$submodule = "Salary Generate List";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$btn_name = "Save";
$crit = "";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$today = new DateTime();
if (isset($_GET['month'])) {
    $month = $_GET['month'];
    if ($month != '') {
        $crit .= " and  month='$month'";
    }
} else {
    $month = "";
}
if (isset($_GET['year'])) {
    $year = $_GET['year'];
    if ($year != '') {
        $crit .= " and  year='$year'";
    }
} else {
    $year = "";
}
$total_working_day = 0;
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    $emp_data =  $obj->select_record("employee_master", array("emp_id" => $emp_id));
    $basic_salary = $emp_data['basic_salary'];
    $first_name = $emp_data['first_name'];
    $last_name = $emp_data['last_name'];
    $emp_code = $emp_data['emp_code'];
    $mobile_no = $emp_data['mobile_no'];
    $department = $obj->getvalfield("department_master", "department_name", "department_id='$emp_data[department_id]'");
    $unit = $obj->getvalfield("unit_master", "unit_name", "unit_id='$emp_data[unit_id]'");
    $date_of_joining = $emp_data['date_of_joining'];

    $total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Present'");
    $total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and month='$month' and year='$year' and attendance_status='Half Day'");
    $total_working_day = $total_present + ($total_half / 2);
    $count = $obj->getvalfield($tblname, "count(*)", "emp_id='$emp_id' and month='$month' and year ='$year'");

    if ($count > 0 && $keyvalue == 0) {
        $msgtype = "<span class='text-danger fw-bold'>Salary for this month has already been processed !!</span>";
        $actType = 1;
    } else {
        $msgtype  = '';
        $actType = 2;
    }
} else {
    $emp_id = $first_name = $last_name = $emp_code = $mobile_no = $department = $unit = $date_of_joining = "";
    $actType = '';
    $msgtype  = '';
}


if (isset($_POST['submit'])) {
    $basic_salary   = $obj->test_input($_POST['basic_salary'] ?? '');
    $increment      = $obj->test_input($_POST['increment'] ?? '');
    $revised_salary = $obj->test_input($_POST['revised_salary'] ?? '');
    $basic_pf_rate  = $obj->test_input($_POST['basic_pf_rate'] ?? '');
    $pf_esic_basic  = $obj->test_input($_POST['pf_esic_basic'] ?? '');
    $total_working_days   = $obj->test_input($_POST['total_working_days'] ?? '');
    $basic_da       = $obj->test_input($_POST['basic_da'] ?? '');
    $hra            = $obj->test_input($_POST['hra'] ?? '');
    $medical        = $obj->test_input($_POST['medical'] ?? '');
    $conveyance     = $obj->test_input($_POST['conveyance'] ?? '');
    $special        = $obj->test_input($_POST['special_allow'] ?? '');
    $total_salary   = $obj->test_input($_POST['total_salary'] ?? '');
    $pf_emp         = $obj->test_input($_POST['pf_emp'] ?? '');
    $esic_emp       = $obj->test_input($_POST['esic_emp'] ?? '');
    $pf_employer    = $obj->test_input($_POST['pf_employer'] ?? '');
    $esic_employer  = $obj->test_input($_POST['esic_employer'] ?? '');


    $form_data = array(
        "emp_id"             => $emp_id,
        "month"             => $month,
        "year"             => $year,
        "basic_salary"       => $basic_salary,
        "increment"       => $increment,
        "revised_salary"     => $revised_salary,
        "basic_pf_rate"      => $basic_pf_rate,
        "pf_esic_basic"      => $pf_esic_basic,
        "total_working_days" => $total_working_days,
        "basic_da"           => $basic_da,
        "hra"                => $hra,
        "medical"            => $medical,
        "conveyance"         => $conveyance,
        "special_allow"      => $special,
        "total_salary"       => $total_salary,
        "pf_emp"             => $pf_emp,
        "esic_emp"           => $esic_emp,
        "pf_employer"        => $pf_employer,
        "esic_employer"      => $esic_employer,
        "createdby"          => $loginid,
        "unit_id"          => $unitid,
        "ipaddress"          => $ipaddress,
        "createdate"       => date('Y-m-d H:i:s')
    );
    $is_esic  = $obj->getvalfield("employee_master", "is_esic", "emp_id='$emp_id'");
    $setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';
    $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $total_working_day, $unitid);
    $week_leave = $obj->totalWeeklyLeave($unitid, $total_working_day);
    $holiday  = $obj->getvalfield("holiday_entry", "count(*)", "unit_id='$unitid' AND MONTH(date) = '$month' AND YEAR(date) = '$year'");
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year) ?? 31;
    $total_leave = $monthly_leave + $week_leave + $holiday;
    $totalAllowedDays = $total_working_days + $total_leave;
    $overtimeDays = max(0, $totalAllowedDays - $daysInMonth);
    $overtime_data = [
        "emp_id" => $emp_id,
        "month" => $month,
        "year" => $year,
        "basic_salary" => $basic_salary,
        "total_leave" => $overtimeDays,
        "unit_id" => $unitid,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "sessionid" => $sessionid,
        "createdate" => date("Y-m-d H:i:s")
    ];



    if ($keyvalue == 0) {
        $form_data["createdate"] = $createdate;
        $obj->insert_record($tblname, $form_data);

        if ($overtimeDays > 0) {
            $obj->insert_record('emp_monthly_leave', $overtime_data);
        }
        $action = 1;
        $process = "insert";
    } else {
        $form_data["lastupdated"] = $createdate;
        $where = array($tblpkey => $keyvalue);
        $obj->update_record($tblname, $where, $form_data);
        if ($overtimeDays > 0) {
            $where = array(
                'emp_id' => $emp_id,
                'month'  => $month,
                'year'   => $year,
                'unit_id'   => $unitid
            );
            $obj->delete_record('emp_monthly_leave', $where);
            $obj->insert_record('emp_monthly_leave', $overtime_data);
        }
        $action = 2;
        $process = "updated";
    }

    $query = $_GET;
    unset($query['action']);
    $query['action'] = $action;
    $url = $pagename . '?' . http_build_query($query);
    echo "<script>location='$url'</script>";
};


if ($keyvalue != 0) {
    $btn_name = "Update";
    $edit_data = $obj->select_record("salary_structure", array("salary_struc_id" => $keyvalue));
    $basic_salary = $edit_data['basic_salary'] ?? '';
    $increment = $edit_data['increment'] ?? '';
    $emp_id = $edit_data['emp_id'];
    $month = $edit_data['month'];
    $year = $edit_data['year'];
    $revised_salary = $edit_data['revised_salary'];
    $basic_pf_rate = $edit_data['basic_pf_rate'];
    $pf_esic_basic = $edit_data['pf_esic_basic'];
    $total_working_days = $edit_data['total_working_days'];
    $basic_da = $edit_data['basic_da'];
    $hra = $edit_data['hra'];
    $medical = $edit_data['medical'];
    $conveyance = $edit_data['conveyance'];
    $special_allow = $edit_data['special_allow'];
    $total_salary = $edit_data['total_salary'];
    $pf_emp = $edit_data['pf_emp'];
    $esic_emp = $edit_data['esic_emp'];
    $pf_employer = $edit_data['pf_employer'];
    $esic_employer = $edit_data['esic_employer'];
} else {
    $total_working_days = $total_working_day;
    $increment = $present_days = $basic_pf_rate = '';
    $revised_salary = '';
    $pf_esic_basic = '';

    $basic_da = '';
    $hra = '';
    $medical = '';
    $conveyance = '';
    $special_allow = '';
    $total_salary = '';
    $pf_emp = '';
    $esic_emp = '';
    $pf_employer = '';
    $esic_employer = '';
}


$slabs = $obj->executequery("SELECT sm.slab_id,sm.from_salary,sm.to_salary, ss.basic_percent,ss.hra_percent,ss.medical_allow,ss.conve_allow,ss.pf_per,ss.esic_per,ss.pf_emp_per,ss.esic_emp_per FROM salary_slab sm JOIN salary_slab_master ss ON ss.slab_id = sm.slab_id ORDER BY sm.from_salary ASC");
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
                <?php include('inc/alert.php');
                ?>
                <div class="row">
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
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 col-12">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">Select Employee</option>
                                                <?php $res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?> <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <!-- Month -->
                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="month" id="month">
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
                                        <div class="col-lg-3 col-12 mt-4">
                                            <input type="submit" name="search" class="btn btn-sm btn-primary add-btn" value="Search" onClick="return checkinputmaster('emp_id,month,year')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>
                                        <div class="col-lg-12">
                                            <?php if ($msgtype != "") {
                                            ?>
                                                <span><?php echo $msgtype . "<br>"  ?></span><?php } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if ($emp_id > 0) { ?>
                            <div class="card card-body">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr class="text-primary">
                                            <th>Employee Code</th>
                                            <th>Employee Name</th>
                                            <th>Date Of Joining</th>
                                            <th>Department</th>
                                            <th>Unit</th>
                                            <th>Contact No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $emp_code ?></td>
                                            <td><?= $first_name . " " . $last_name ?></td>
                                            <td><?= $obj->dateformatindia($date_of_joining); ?></td>
                                            <td><?= $department ?></td>
                                            <td><?= $unit ?></td>
                                            <td><?= $mobile_no ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($actType == 2) { ?>
                            <div class="card bg-body">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0 text-primary">Monthly Payment</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="salaryForm">
                                        <div class="row">
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Basic Salary </label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $basic_salary; ?>" onkeyup="calculateForm()" name="basic_salary" id="basic_salary">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="increment"> Increment</label>
                                                <input type="text" class="form-control form-control-sm" name="increment" value="<?= $increment; ?>" id="increment" onkeyup="calculateForm()">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Revised Gross Salary</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $revised_salary ?>" name="revised_salary" id="revised_salary">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="basic_pf_rate">Basic+PF+ESIC Rate</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $basic_pf_rate ?>" name="basic_pf_rate" id="basic_pf_rate">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">PF+ESIC Paid Basic</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $pf_esic_basic ?>" name="pf_esic_basic" id="pf_esic_basic">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Total Working Days</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $total_working_days ?>" name="total_working_days" id="total_working_days" onkeyup="calculateForm()">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Basic + DA</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $basic_da ?>" name="basic_da" id="basic_da">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">HRA</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $hra ?>" name="hra" id="hra">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Medical Allowance </label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $medical ?>" name="medical" id="medical">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Conveyance Allowance </label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $conveyance ?>" name="conveyance" id="conveyance">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Special Allowance</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $special_allow ?>" name="special_allow" id="special_allow">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">Total Salary </label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $total_salary ?>" name="total_salary" id="total_salary">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for=""> PF Emp Share </label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $pf_emp ?>" name="pf_emp" id="pf_emp">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">ESIC Emp Share </label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $esic_emp ?>" name="esic_emp" id="esic_emp">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">PF Employer Share</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $pf_employer ?>" name="pf_employer" id="pf_employer">
                                            </div>
                                            <div class="col-lg-2 col-12 mb-3">
                                                <label for="">ESIC Employer Share</label>
                                                <input type="text" class="form-control form-control-sm" value="<?= $esic_employer ?>" name="esic_employer" id="esic_employer">
                                            </div>


                                            <div class="col-lg-12 col-12 text-center">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?= $btn_name ?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
            });
            calculateForm();
        });


        function round(val) {
            return Math.round(Number(val) || 0);
        }
    </script>
    <script>
        function calculateForm() {
            const salarySlabs = <?= json_encode($slabs); ?>;
            const form = document.getElementById('salaryForm');

            let presentSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
            let incrementSalary = parseFloat(document.getElementById('increment').value) || 0;
            let daysWorked = parseFloat(document.getElementById('total_working_days').value) || 31;

            let revisedSalaryInput = document.getElementById('revised_salary');
            let basicRateInput = document.getElementById('basic_pf_rate');
            let pfBasicInput = document.getElementById('pf_esic_basic');

            let basicDAInput = document.getElementById('basic_da');
            let hraInput = document.getElementById('hra');
            let medicalInput = document.getElementById('medical');
            let conveyanceInput = document.getElementById('conveyance');
            let specialInput = document.getElementById('special_allow');
            let totalSalaryInput = document.getElementById('total_salary');

            let pfEmpInput = document.getElementById('pf_emp');
            let esicEmpInput = document.getElementById('esic_emp');
            let pfEmployerInput = document.getElementById('pf_employer');
            let esicEmployerInput = document.getElementById('esic_employer');


            let totalRevisedSalary = presentSalary + incrementSalary;
            revisedSalaryInput.value = Math.round(totalRevisedSalary);

            let slab = salarySlabs.find(s =>
                totalRevisedSalary >= parseFloat(s.from_salary) &&
                (parseFloat(s.to_salary) == 0 || totalRevisedSalary < parseFloat(s.to_salary))
            );

            if (!slab) return;

            let basicRate = round(totalRevisedSalary * slab.basic_percent / 100);
            let basicDA = round(basicRate / 31 * daysWorked);
            let hra = round(basicDA * slab.hra_percent / 100);

            let medical = round(slab.medical_allow / 31 * daysWorked);
            let convey = round(slab.conve_allow / 31 * daysWorked);

            let perDaySal = round(totalRevisedSalary / 31 * daysWorked);
            let special = perDaySal - (basicDA + hra + medical + convey);



            let pf_val = (basicRate <= 15000) ? round(basicDA * slab.pf_per / 100) : 0;
            let esic_val = (basicRate <= 21000) ? round(basicDA * slab.esic_per / 100) : 0;

            let pf_emp_val = (basicRate <= 15000) ? round(basicDA * slab.pf_emp_per / 100) : 0;
            let esic_emp_val = (basicRate <= 21000) ? round(basicDA * slab.esic_emp_per / 100) : 0;

            basicRateInput.value = basicRate;
            pfBasicInput.value = basicDA;
            basicDAInput.value = basicDA;
            hraInput.value = hra;
            medicalInput.value = medical;
            conveyanceInput.value = convey;
            specialInput.value = special;
            totalSalaryInput.value = basicDA + hra + medical + convey + special;

            pfEmpInput.value = pf_val;
            esicEmpInput.value = esic_val;
            pfEmployerInput.value = pf_emp_val;
            esicEmployerInput.value = esic_emp_val;
        }
    </script>

</body>

</html>