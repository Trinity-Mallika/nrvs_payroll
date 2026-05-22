<?php include("../adminsession.php");
$pagename = 'api_call.php';
$title = 'api_call.php';

$response = "";

if (isset($_POST['submit'])) {

    $emp_code = $_POST['emp_code'];
    $deviceID = $_POST['deviceID'];
    $date = $_POST['attendance_date'];
    $time = $_POST['punch_time'];

    // Combine date + time → ISO format
    $punchDateTime = date("Y-m-d\TH:i:s", strtotime("$date $time"));


    $data = [
        "EmployeeID" => $emp_code,
        "SerialNo" => $deviceID,
        "AttendanceDate" => $date,
        "PunchTime" => $punchDateTime
    ];

    $json_input = json_encode($data);

    // API URL
    $url = "http://trinity/nrvs_payroll/webservices/get_data_k30.php";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_input);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $response = "Curl Error: " . curl_error($ch);
    } else {
        $response = $result;
    }

    curl_close($ch);
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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-header">
                                <h5>Attendance API Testing</h5>
                            </div>

                            <div class="card-body">

                                <form method="post">

                                    <div class="row">

                                        <div class="col-lg-3 col-12">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_code"
                                                id="emp_code">
                                                <option value="">Select Employee</option>
                                                <?php

                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_code']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?>/
                                                    <?= $key['shift_id'] ?? ''; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_code').value = '2524'
                                            </script>
                                        </div>
                                        <div class="col-lg-3 col-12">
                                            <label for="emp_id" class="form-label">Device ID<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="deviceID"
                                                id="deviceID">
                                                <option value="6728322120001144">IN </option>
                                                <option value="6728322120001025">OUT</option>

                                            </select>

                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Attendance Date</label>
                                            <input type="date" name="attendance_date"
                                                class="form-control form-control-sm" value="<?= date("Y-m-01") ?>">
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Punch Time</label>
                                            <input type="time" name="punch_time" class="form-control form-control-sm">
                                        </div>

                                        <div class="col-lg-3 mb-3 mt-4">
                                            <button type="submit" name="submit" class="btn btn-primary btn-sm">
                                                Submit
                                            </button>
                                            <a href=" <?php echo $pagename ?>" type="button"
                                                class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>

                                </form>

                                <?php if ($response != "") { ?>
                                <hr>
                                <h6>API Response:</h6>
                                <pre><?php echo htmlspecialchars($response); ?></pre>
                                <?php } ?>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include('inc/js.php') ?>
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