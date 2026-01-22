<?php include("../adminsession.php");
$pagename = "multiple_salary_generate.php";
$title = "Multiple Salary Generate [Monthly]";
$tblname = "sal_generate";
$tblpkey = "sal_id";
$module = "Multiple Salary Generate [Monthly]";
$submodule = "Multiple Salary Generate List";
$btn_name = "Save";
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['branch_id'], $_POST['month'], $_POST['year'])) {
    $branch_id = $obj->test_input($_POST['branch_id']);
    $month     = $obj->test_input($_POST['month']);
    $year      = $obj->test_input($_POST['year']);
    $department_id = (isset($_POST['department_id'])) ? $obj->test_input($_POST['department_id']) : 0;
    $crit = "WHERE branch_id='$branch_id' AND status='1' AND sal_type='Monthly'";
    if ($department_id > 0) {
        $crit .= " and department_id='$department_id'";
    }

    $employees = $obj->executequery("SELECT * FROM employee_master $crit");
    $monthNumber = (int)date('m', strtotime($month));

    if (count($employees) > 0) {
        $count_generated = 0;
        foreach ($employees as $emp_data) {
            $emp_id   = $emp_data['emp_id'];
            $obj->delete_record("sal_generate", ['emp_id' => $emp_id, "branch_id" => $branch_id, "month" => $month, "year" => $year]);
            $salary       = $emp_data['salary'];
            $sal_type     = $emp_data['sal_type'];
            $total_days   = 30;
            $present_days = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' AND month='$monthNumber' AND year='$year'");
            $per_day_rate = ($total_days > 0) ? round($salary / $total_days, 2) : 0;
            // Skip if no attendance
            if ($present_days == 0) continue;
            $gross        = round($per_day_rate * $present_days);
            $net_salary   = $gross;

            $form_data = array(
                "branch_id" => $branch_id,
                "emp_id" => $emp_id,
                "payment_type" => $sal_type,
                "sal_date" => date("Y-m-d"),
                "month" => $month,
                "total_days" => $total_days,
                "year" => $year,
                "per_day_rate" => $per_day_rate,
                "present_days" => $present_days,
                "monthly_salary" => $salary,
                "total_deductions" => 0,
                "net_pay" => $net_salary,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "createdate" => date("Y-m-d H:i:s")
            );

            $obj->insert_record("sal_generate", $form_data);
            $count_generated++;
        }
        echo json_encode(['status' => 'success', 'message' => "$count_generated salaries generated"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "No employees found"]);
    }
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
                                            <h5 class="card-title mb-0"> <?= $module; ?> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="branch_id" class="form-label">Branch Name<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select chosen-select" name="branch_id" id="branch_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from branch_master where status='1'");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['branch_id']; ?>"><?= $key['branch_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department<span class="text-danger fw-bold"></span></label>
                                            <select class="form-select chosen-select" name="department_id" id="department_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from department_master order by department_name asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['department_id']; ?>"><?= $key['department_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
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
                                                    echo "<option value=\"$name\">$name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
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
                                        </div>
                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Generate" onclick="return checkinputmaster('branch_id,month,year');">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>
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
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen({
                width: '100%',
                search_contains: true
            });
        });
        $("form").off('submit').on("submit", function(e) {
            e.preventDefault();

            let branch_id = $("#branch_id").val();
            let department_id = $("#department_id").val();
            let month = $("#month").val();
            let year = $("#year").val();

            if (branch_id == "" || month == "" || year == "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select Branch, Month and Year!'
                });
                return false;
            }

            Swal.fire({
                title: 'Generating salaries...',
                html: 'Please wait while we process the salaries.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '', // same page ajax
                type: 'POST',
                data: {
                    branch_id: branch_id,
                    month: month,
                    department_id: department_id,
                    year: year
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    Swal.fire({
                        icon: response.status == 'success' ? 'success' : 'error',
                        title: response.status == 'success' ? 'Done!' : 'Error!',
                        text: response.message
                    }).then((result) => {
                        if (response.status === 'success') {
                            location.reload();
                        }
                    });
                },
                error: function() {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            });
        });
    </script>
</body>

</html>