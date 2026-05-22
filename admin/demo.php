<?php
include("../adminsession.php");

function getExtraOffBalance($obj, $emp_id, $month, $year)
{

    $current_date = date("Y-m-d", strtotime("$year-$month-01"));

    $current_month = date("m", strtotime($current_date));
    $current_year  = date("Y", strtotime($current_date));

    $prev_month = date("m", strtotime("$current_date -1 month"));
    $prev_year  = date("Y", strtotime("$current_date -1 month"));

    // Upload Data
    $upload_data = $obj->executequery("
        SELECT *
        FROM test_extra_off_upload
        WHERE emp_id = '$emp_id'
        AND (
            (month = '$current_month' AND year = '$current_year')
            OR
            (month = '$prev_month' AND year = '$prev_year')
        )
    ");

    // Upload Total
    $upload_total = $obj->executequery("
        SELECT 
            COALESCE(SUM(extra_off_days),0) as total_extra_off
        FROM test_extra_off_upload
        WHERE emp_id = '$emp_id'
        AND (
            (month = '$current_month' AND year = '$current_year')
            OR
            (month = '$prev_month' AND year = '$prev_year')
        )
    ");

    $total_extra_off = $upload_total[0]['total_extra_off'];

    // Used Data
    $used_data = $obj->executequery("
        SELECT *
        FROM test_attendance_entry
        WHERE emp_id = '$emp_id'
        AND (
            (
                MONTH(attendance_date) = '$current_month'
                AND YEAR(attendance_date) = '$current_year'
            )
            OR
            (
                MONTH(attendance_date) = '$prev_month'
                AND YEAR(attendance_date) = '$prev_year'
            )
        )
        AND attendance_status = 'Extra Off'
    ");

    // Used Total
    $used_total = $obj->executequery("
        SELECT 
            COALESCE(SUM(extra_off_days),0) as used_extra_off
        FROM test_attendance_entry
        WHERE emp_id = '$emp_id'
        AND (
            (
                MONTH(attendance_date) = '$current_month'
                AND YEAR(attendance_date) = '$current_year'
            )
            OR
            (
                MONTH(attendance_date) = '$prev_month'
                AND YEAR(attendance_date) = '$prev_year'
            )
        )
        AND attendance_status = 'Extra Off'
    ");

    $used_extra_off = $used_total[0]['used_extra_off'];

    // Balance
    $balance = $total_extra_off - $used_extra_off;

    if ($balance < 0) {
        $balance = 0;
    }

    return [

        'current_month' => $current_month,
        'prev_month'    => $prev_month,

        'upload_data'   => $upload_data,
        'used_data'     => $used_data,

        'uploaded'      => $total_extra_off,
        'used'          => $used_extra_off,

        'balance'       => $balance
    ];
}


// Dynamic Month + Year

$emp_id = 1;

$month = isset($_GET['month']) ? $_GET['month'] : date("m");
$year  = isset($_GET['year']) ? $_GET['year'] : date("Y");

$result = getExtraOffBalance($obj, $emp_id, $month, $year);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Extra Off Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .card-box {
            border: none;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .summary-card {
            border-radius: 20px;
            color: #fff;
            padding: 25px;
        }

        .bg-upload {
            background: linear-gradient(45deg, #0d6efd, #4d8bff);
        }

        .bg-used {
            background: linear-gradient(45deg, #dc3545, #ff6482);
        }

        .bg-balance {
            background: linear-gradient(45deg, #198754, #43d39e);
        }

        .table thead {
            background: #0d6efd;
            color: #fff;
        }

        .table {
            margin-bottom: 0;
        }
    </style>

</head>

<body>

    <div class="container-fluid py-4">

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">

                <!-- Header -->

                <div class="card card-box p-4 mb-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <h2 class="mb-0">
                            Extra Off Dashboard
                        </h2>

                        <form method="GET"
                            class="d-flex gap-2">

                            <select name="month"
                                class="form-select">

                                <?php

                                for ($m = 1; $m <= 12; $m++) {

                                    $mm = str_pad($m, 2, "0", STR_PAD_LEFT);

                                    $selected = ($month == $mm) ? "selected" : "";

                                    echo "<option value='$mm' $selected>$mm</option>";
                                }

                                ?>

                            </select>

                            <input type="number"
                                name="year"
                                class="form-control"
                                value="<?php echo $year; ?>">

                            <button class="btn btn-primary">
                                Check
                            </button>

                        </form>

                    </div>

                </div>


                <!-- Summary Cards -->

                <div class="row mb-4">

                    <div class="col-md-4 mb-3">

                        <div class="summary-card bg-upload">

                            <h5>Total Uploaded</h5>

                            <h1>
                                <?php echo $result['uploaded']; ?>
                            </h1>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="summary-card bg-used">

                            <h5>Total Used</h5>

                            <h1>
                                <?php echo $result['used']; ?>
                            </h1>

                        </div>

                    </div>

                    <div class="col-md-4 mb-3">

                        <div class="summary-card bg-balance">

                            <h5>Final Balance</h5>

                            <h1>
                                <?php echo $result['balance']; ?>
                            </h1>

                        </div>

                    </div>

                </div>


                <!-- Upload Data -->

                <div class="card card-box p-4 mb-4">

                    <h3 class="mb-3">
                        Upload Data
                    </h3>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>ID</th>
                                    <th>EMP ID</th>
                                    <th>MONTH</th>
                                    <th>YEAR</th>
                                    <th>EXTRA OFF DAYS</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                foreach ($result['upload_data'] as $row) {

                                ?>

                                    <tr>

                                        <td><?php echo $row['id']; ?></td>

                                        <td><?php echo $row['emp_id']; ?></td>

                                        <td><?php echo $row['month']; ?></td>

                                        <td><?php echo $row['year']; ?></td>

                                        <td><?php echo $row['extra_off_days']; ?></td>

                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>


                <!-- Used Data -->

                <div class="row">

                    <div class="col-lg-8 mb-4">

                        <div class="card card-box p-4 h-100">

                            <h3 class="mb-3">
                                Used Data
                            </h3>

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover">

                                    <thead>

                                        <tr>

                                            <th>ID</th>
                                            <th>EMP ID</th>
                                            <th>DATE</th>
                                            <th>STATUS</th>
                                            <th>EXTRA OFF DAYS</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        foreach ($result['used_data'] as $row) {

                                        ?>

                                            <tr>

                                                <td><?php echo $row['id']; ?></td>

                                                <td><?php echo $row['emp_id']; ?></td>

                                                <td><?php echo $row['attendance_date']; ?></td>

                                                <td><?php echo $row['attendance_status']; ?></td>

                                                <td><?php echo $row['extra_off_days']; ?></td>

                                            </tr>

                                        <?php
                                        }
                                        ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    <!-- Quick Info -->

                    <div class="col-lg-4 mb-4">

                        <div class="card card-box p-4 h-100">

                            <h3 class="mb-4">
                                Quick Info
                            </h3>

                            <div class="mb-3">

                                <strong>Current Month :</strong>

                                <div>
                                    <?php echo $result['current_month']; ?>
                                </div>

                            </div>

                            <div class="mb-3">

                                <strong>Previous Month :</strong>

                                <div>
                                    <?php echo $result['prev_month']; ?>
                                </div>

                            </div>

                            <div class="mb-3">

                                <strong>Total Uploaded :</strong>

                                <div>
                                    <?php echo $result['uploaded']; ?>
                                </div>

                            </div>

                            <div class="mb-3">

                                <strong>Total Used :</strong>

                                <div>
                                    <?php echo $result['used']; ?>
                                </div>

                            </div>

                            <div>

                                <strong>Balance :</strong>

                                <div>
                                    <?php echo $result['balance']; ?>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- RIGHT SIDE -->
            <div class="col-lg-4">

                <div class="card card-box p-4 sticky-top">

                    <h2 class="mb-4">
                        12 Month Report
                    </h2>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>Month</th>
                                    <th>Upload</th>
                                    <th>Used</th>
                                    <th>Balance</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                for ($i = 1; $i <= 12; $i++) {

                                    $m = str_pad($i, 2, "0", STR_PAD_LEFT);

                                    // Upload
                                    $up = $obj->executequery("
                                SELECT 
                                    COALESCE(SUM(extra_off_days),0) as total
                                FROM test_extra_off_upload
                                WHERE emp_id = '$emp_id'
                                AND month = '$m'
                                AND year = '$year'
                            ");

                                    $uploaded = $up[0]['total'];

                                    // Used
                                    $us = $obj->executequery("
                                SELECT 
                                    COALESCE(SUM(extra_off_days),0) as total
                                FROM test_attendance_entry
                                WHERE emp_id = '$emp_id'
                                AND MONTH(attendance_date) = '$m'
                                AND YEAR(attendance_date) = '$year'
                                AND attendance_status = 'Extra Off'
                            ");

                                    $used = $us[0]['total'];

                                    $balance = $uploaded - $used;

                                    if ($balance < 0) {
                                        $balance = 0;
                                    }

                                ?>

                                    <tr>

                                        <td><?php echo $m; ?></td>

                                        <td><?php echo $uploaded; ?></td>

                                        <td><?php echo $used; ?></td>

                                        <td><?php echo $balance; ?></td>

                                    </tr>

                                <?php
                                }
                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>