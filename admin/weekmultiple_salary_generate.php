<?php include("../adminsession.php");
$pagename = "weekmultiple_salary_generate.php";
$title = "Multiple Salary Generate [Weekly]";
$tblname = "sal_generate";
$tblpkey = "sal_id";
$module = "Multiple Salary Generate [Weekly]";
$submodule = "Multiple Salary Generate List";
$btn_name = "Save";
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$branch_id = (isset($_GET['branch_id'])) ? $obj->test_input($_GET['branch_id']) : '';
$today = new DateTime();
if (isset($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
} else {
    $start_date = (clone $today)->modify('last sunday -1 week')->format('Y-m-d');
}

if (isset($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
} else {
    $end_date = (clone $today)->modify('last week saturday')->format('Y-m-d');
}


if (isset($_POST['branch_id'], $_POST['start_date'], $_POST['end_date'])) {
    $branch_id = $obj->test_input($_POST['branch_id']);
    $start_date = $_POST['start_date'];
    $end_date  = $_POST['end_date'];
    $department_id = (isset($_POST['department_id'])) ? $obj->test_input($_POST['department_id']) : 0;

    $crit = "WHERE branch_id='$branch_id' AND status='1' AND sal_type='Weekly'";
    if ($department_id > 0) {
        $crit .= " AND department_id='$department_id'";
    }

    $employees = $obj->executequery("SELECT * FROM employee_master $crit");

    if (count($employees) > 0) {
        $total_employees = count($employees);
        $count_generated = 0;
        $count_already_generated = 0;

        foreach ($employees as $emp_data) {
            $emp_id = $emp_data['emp_id'];

            // Check if salary already exists
            $is_exist = $obj->getvalfield(
                "sal_generate",
                "count(*)",
                "emp_id='$emp_id' AND branch_id='$branch_id' AND start_date='$start_date' AND end_date='$end_date'"
            );

            if ($is_exist > 0) {
                $count_already_generated++;
                continue;
            }

            $per_day_rate = $emp_data['salary'];
            $sal_type = $emp_data['sal_type'];

            $present_days = $obj->getvalfield(
                "attendance_entry",
                "count(*)",
                "emp_id='$emp_id' AND attendance_date BETWEEN '$start_date' AND '$end_date'"
            );

            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = $start->diff($end);
            $total_days = $interval->days + 1;

            if ($present_days == 0) continue;

            $gross = round($per_day_rate * $present_days);
            $net_salary = $gross;

            $form_data = array(
                "branch_id" => $branch_id,
                "emp_id" => $emp_id,
                "payment_type" => $sal_type,
                "sal_date" => date("Y-m-d"),
                "month" => date('F', strtotime($start_date)),
                "year" => date('Y', strtotime($start_date)),
                "start_date" => $start_date,
                "end_date" => $end_date,
                "per_day_rate" => $per_day_rate,
                "present_days" => $present_days,
                "total_days" => $total_days,
                "net_pay" => $net_salary,
                "createdby" => $loginid,
                "ipaddress" => $ipaddress,
                "createdate" => date("Y-m-d H:i:s")
            );

            $obj->insert_record("sal_generate", $form_data);
            $count_generated++;
        }

        // Build the result message
        if ($count_generated > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => "$count_generated out of $total_employees salaries generated successfully."
            ]);
        } elseif ($count_already_generated == $total_employees) {
            echo json_encode([
                'status' => 'info',
                'message' => "Salaries for all $total_employees employees are already generated."
            ]);
        } else {
            echo json_encode([
                'status' => 'warning',
                'message' => "No new salaries generated. Salary Already Generated"
            ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => "No employees found for the selected criteria."]);
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
                                            <script>
                                                document.getElementById('branch_id').value = '<?= $branch_id; ?>';
                                            </script>
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
                                            <label for="start_date" class="form-label">Start Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" class="form-control" name="start_date" id="start_date" value="<?php echo $start_date ?>">
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="end_date" class="form-label">End Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" class="form-control" name="end_date" id="end_date" value="<?php echo $end_date ?>">
                                        </div>

                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn" value="Generate" onclick="return checkinputmaster('branch_id,start_date,end_date');">
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
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let department_id = $("#department_id").val();

            if (branch_id === "" || start_date === "" || end_date === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please select Branch, Start and End Date!'
                });
                return false;
            }

            Swal.fire({
                title: 'Generating Salaries...',
                html: 'Please wait while we process the data.',
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
                    start_date: start_date,
                    department_id: department_id,
                    end_date: end_date
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    let swalIcon, swalTitle;

                    switch (response.status) {
                        case 'success':
                            swalIcon = 'success';
                            swalTitle = 'Success';
                            break;
                        case 'info':
                            swalIcon = 'info';
                            swalTitle = 'Info';
                            break;
                        case 'warning':
                            swalIcon = 'warning';
                            swalTitle = 'Warning';
                            break;
                        default:
                            swalIcon = 'error';
                            swalTitle = 'Error';
                    }

                    Swal.fire({
                        icon: swalIcon,
                        title: swalTitle,
                        text: response.message
                    }).then(() => {
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