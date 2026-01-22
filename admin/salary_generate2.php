<?php include("../adminsession.php");
$pagename = "salary_generate2.php";
$title = "Salary Generate";
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$module = "Salary Generate";
$submodule = "Salary Generate List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';


$today = new DateTime();
if (isset($_GET['month'])) {
    $month = $_GET['month'];
    $monthNumber = (int)date('m', strtotime($month));
    if ($month != '') {
        $crit .= " and ae.month='$month'";
    }
} else {
    $monthNumber = 0;
    $month = "";
}
if (isset($_GET['year'])) {
    $year = $_GET['year'];
    if ($year != '') {
        $crit .= " and ae.year='$year'";
    }
} else {
    $year = "";
}

if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and em.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
}
if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and em.department_id='$department_id'";
    }
} else {
    $department_id = "";
}

if (isset($_POST['save_salary'])) {
    $emp_id  = $_POST['emp_id'];
    $present_salary    = $_POST['present_salary'];
    $increment    = $_POST['increment'];
    $revised_salary    = $_POST['revised_salary'];
    $basic_pf_rate     = $_POST['basic_pf_rate'];
    $pf_esic_basic     = $_POST['pf_esic_basic'];
    $days_work         = $_POST['days_work'];
    $basic_da          = $_POST['basic_da'];
    $hra               = $_POST['hra'];
    $medical           = $_POST['medical'];
    $conveyance        = $_POST['conveyance'];
    $special           = $_POST['special'];
    $total_salary      = $_POST['total_salary'];
    $pf_emp            = $_POST['pf_emp'];
    $esic_emp          = $_POST['esic_emp'];
    $pf_employer       = $_POST['pf_employer'];
    $esic_employer     = $_POST['esic_employer'];

    for ($i = 0; $i < count($emp_id); $i++) {
        $form_data = array(
            "emp_id"             => $emp_id[$i],
            "month"             => $month,
            "year"             => $year,
            "basic_salary"       => $present_salary[$i],
            "increment"       => $increment[$i],
            "revised_salary"     => $revised_salary[$i],
            "basic_pf_rate"      => $basic_pf_rate[$i],
            "pf_esic_basic"      => $pf_esic_basic[$i],
            "total_working_days" => $days_work[$i],
            "basic_da"           => $basic_da[$i],
            "hra"                => $hra[$i],
            "medical"            => $medical[$i],
            "conveyance"         => $conveyance[$i],
            "special_allow"      => $special[$i],
            "total_salary"       => $total_salary[$i],
            "pf_emp"             => $pf_emp[$i],
            "esic_emp"           => $esic_emp[$i],
            "pf_employer"        => $pf_employer[$i],
            "esic_employer"      => $esic_employer[$i],
            "createdby"          => $loginid,
            "unit_id"          => $unitid,
            "ipaddress"          => $ipaddress,
            "createdate"       => date('Y-m-d H:i:s')
        );

        $where = array(
            'emp_id' => $emp_id[$i],
            'month'  => $month,
            'year'   => $year
        );

        $obj->delete_record('salary_structure', $where);
        $obj->insert_record($tblname, $form_data);
        $action = 1;
    }
    $query = $_GET;
    unset($query['action']);
    $query['action'] = $action;
    $url = $pagename . '?' . http_build_query($query);
    echo "<script>location='$url'</script>";
};



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
                <?php include('inc/alert.php'); ?>
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
                                <form onsubmit="return check_validation();">
                                    <div class="row">
                                        <!-- Employee -->
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Employee Name<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from employee_master order by emp_id asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['emp_id']; ?>">
                                                        <?= $key['first_name']; ?> <?= $key['last_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('emp_id').value =
                                                    '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="emp_id" class="form-label">Department<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select" name="department_id" id="department_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from department_master order by department_id asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['department_id']; ?>">
                                                        <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('department_id').value =
                                                    '<?= $department_id; ?>';
                                            </script>
                                        </div>

                                        <!-- Month -->
                                        <div class="col-lg-3 mb-3">
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
                                        <div class="col-lg-3 mb-3">
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


                                        <div class="col-lg-12 mt-4 text-end">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="Search" onClick="return checkinputmaster('month,year')">
                                            <a href="<?php echo $pagename ?>" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($_GET['emp_id'])) { ?>
                        <div class="col-lg-12">
                            <form method="post" action="">
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
                                            <table id="tablesss" class="display table table-sm table-bordered text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th colspan="15" class="text-center fw-bold">
                                                            NRVS STEELS LIMITED
                                                        </th>
                                                    </tr>

                                                    <tr class="table-primary">
                                                        <th>S.No.</th>
                                                        <th class="nowrap">Employee Name</th>
                                                        <th class="nowrap">Present Salary</th>
                                                        <th class="nowrap">Increament</th>
                                                        <th class="nowrap">Revised Gross Salary</th>
                                                        <th class="nowrap">Basic+PF+ESIC Rate</th>
                                                        <th class="nowrap">PF+ESIC Paid Basic</th>
                                                        <th class="nowrap">Days Work</th>
                                                        <th class="nowrap">Basic + DA</th>
                                                        <th class="nowrap">HRA</th>
                                                        <th class="nowrap">Medical Allowance</th>
                                                        <th class="nowrap">Conveyance Allowance</th>
                                                        <th class="nowrap">Special Allowance</th>
                                                        <th class="nowrap">Total Salary</th>
                                                        <th class="nowrap">PF Emp Share</th>
                                                        <th class="nowrap">ESIC Emp Share</th>
                                                        <th class="nowrap">PF Employer Share</th>
                                                        <th class="nowrap">ESIC Employer Share</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    $res = $obj->executequery("SELECT em.*, em.emp_id as main_emp_id, ss.basic_salary as present_salary,ss.increment,ss.total_working_days FROM employee_master em LEFT JOIN salary_structure ss ON ss.emp_id = em.emp_id  AND ss.month = '$month' AND ss.year  = '$year' LEFT JOIN attendance_entry ae ON ae.emp_id = em.emp_id WHERE em.unit_id = '$unitid' $crit group by em.emp_id ORDER BY em.emp_id DESC");

                                                    // $res = $obj->executequery("select * from employee_master where unit_id='$unitid' $crit order by emp_id desc");

                                                    foreach ($res as $key) {
                                                        $total_present = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$key[main_emp_id]' and month='$month' and year='$year' and attendance_status='Present'");
                                                        $total_half = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$key[main_emp_id]' and month='$month' and year='$year' and attendance_status='Half Day'");
                                                        $total_working_day = $total_present + ($total_half / 2);
                                                        $total_working_days = $key['total_working_days'] ?? $total_working_day;

                                                        $setting_type = ($key['is_esic']  == 1) ? 'ESIC' : 'Non ESIC';

                                                        $total_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $total_working_days, $unitid);

                                                        $total_week_leave = $obj->totalWeeklyLeave($unitid, $total_working_days);

                                                    ?>
                                                        <tr oninput="calculateRow(this)">
                                                            <td><?php echo $i++; ?></td>
                                                            <td>
                                                                <input type="hidden" name="emp_id[]" value="<?= $key['main_emp_id']; ?>" />
                                                                <?= $key['first_name'] . " " . $key['last_name'] ?>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="present_salary[]" value="<?= $key['present_salary'] ?? $key['basic_salary'] ?>" onkeypress="numberOnly(event);" id="present_salary_<?= $key['emp_id']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="increment[]" onkeypress="numberOnly(event);" id="increment_<?= $key['emp_id']; ?>" value="<?= $key['increment'] ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="revised_salary[]" onkeypress="numberOnly(event);" id="revised_salary<?= $key['emp_id']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="basic_pf_rate[]" onkeypress="numberOnly(event);" id="basic_pf_rate<?= $key['emp_id']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="pf_esic_basic[]" onkeypress="numberOnly(event);" id="pf_esic_basic<?= $key['emp_id']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="days_work[]" value="<?= $total_working_days ?>" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="basic_da[]" onkeypress="numberOnly(event);" style="width: 130px;">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" style="width: 130px;" name="hra[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="medical[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="conveyance[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="special[]" onkeypress="numberOnly(event);" value="<?= $key['special_allowance'] ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="total_salary[]" readonly onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="pf_emp[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="esic_emp[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="pf_employer[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="esic_employer[]" onkeypress="numberOnly(event);">
                                                            </td>
                                                        </tr>
                                                    <?php }

                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3 text-center">
                                        <br>
                                        <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                        <input type="submit" name="save_salary" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> ">
                                        <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                    </div>

                                </div>
                            </form>
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
            });
            document.querySelectorAll("#tablesss tbody tr")
                .forEach(row => calculateRow(row));
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

        function round(val) {
            return Math.round(Number(val) || 0);
        }


        function calculateRow(row) {

            if (!row) return;
            const salarySlabs = <?= json_encode($slabs); ?>;


            let presentSalary = parseFloat(row.querySelector('[name="present_salary[]"]').value) || 0;
            let increment_salary = parseFloat(row.querySelector('[name="increment[]"]').value) || 0;
            let revisedSalaryInput = row.querySelector('[name="revised_salary[]"]');
            let basicRateInput = row.querySelector('[name="basic_pf_rate[]"]');
            let pfBasicInput = row.querySelector('[name="pf_esic_basic[]"]');
            let daysWorked = parseFloat(row.querySelector('[name="days_work[]"]').value) || 31;

            let basicDAInput = row.querySelector('[name="basic_da[]"]');
            let hraInput = row.querySelector('[name="hra[]"]');
            let medicalInput = row.querySelector('[name="medical[]"]');
            let conveyanceInput = row.querySelector('[name="conveyance[]"]');
            let specialInput = row.querySelector('[name="special[]"]');
            let totalSalaryInput = row.querySelector('[name="total_salary[]"]');

            let pfEmpInput = row.querySelector('[name="pf_emp[]"]');
            let esicEmpInput = row.querySelector('[name="esic_emp[]"]');
            let pfEmployerInput = row.querySelector('[name="pf_employer[]"]');
            let esicEmployerInput = row.querySelector('[name="esic_employer[]"]');

            // Revised Gross Salary
            total_revised_salary = presentSalary + increment_salary;

            revisedSalaryInput.value = Math.round(total_revised_salary);

            let revisedSalary = revisedSalaryInput.value;


            let slab = salarySlabs.find(s =>
                revisedSalary >= parseFloat(s.from_salary) &&
                (parseFloat(s.to_salary) == 0 || revisedSalary < parseFloat(s.to_salary))
            );
            if (slab) {
                let basicPercent = parseFloat(slab.basic_percent) || 0;
                let hraPercent = parseFloat(slab.hra_percent) || 0;
                let medicalAllow = parseFloat(slab.medical_allow) || 0;
                let conveyAllow = parseFloat(slab.conve_allow) || 0;

                basicRate = round(revisedSalary * basicPercent / 100);
                basicDA = round(basicRate / 31 * daysWorked);

                hra = round(basicDA * hraPercent / 100);
                // FIXED ALLOWANCES 
                medical = round(medicalAllow / 31 * daysWorked);
                conveyance = round(conveyAllow / 31 * daysWorked);
                // SPECIAL
                let perDaySalary = round(revisedSalary / 31 * daysWorked);
                special = perDaySalary - (basicDA + hra + medical + conveyance);

                // PF / ESIC
                pf_val = (basicRate <= 15000) ? round(basicDA * slab.pf_per / 100) : 0;
                esic_val = (basicRate <= 21000) ? round(basicDA * slab.esic_per / 100) : 0;

                pf_emp_val = (basicRate <= 15000) ? round(basicDA * slab.pf_emp_per / 100) : 0;
                esic_emp_val = (basicRate <= 21000) ? round(basicDA * slab.esic_emp_per / 100) : 0;


            }

            basicRateInput.value = basicRate;
            pfBasicInput.value = basicDA;
            basicDAInput.value = basicDA;
            hraInput.value = hra;
            medicalInput.value = medical;
            conveyanceInput.value = conveyance;
            specialInput.value = special;

            totalSalaryInput.value = basicDA + hra + medical + conveyance + special;

            pfEmpInput.value = pf_val;
            esicEmpInput.value = esic_val;
            pfEmployerInput.value = pf_emp_val;
            esicEmployerInput.value = esic_emp_val;
        }
    </script>

</body>

</html>