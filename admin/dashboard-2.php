<?php include("../adminsession.php");
$pagename = "dashboard.php";
$prevMonth = date('F', strtotime('-1 month'));
$prevYear  = date('Y', strtotime('-1 month'));
$deptLabels =  $deptValues =  $branchLabels =  $branchValues = '';


$datecurrent = date('Y-m-d');
$total_emp = $obj->getvalfield("employee_master", "count(*)", "unit_id='$unitid'");
$todayin = $obj->getvalfield("attendance_entry", "count(*)", "in_status ='IN' and attendance_date='$datecurrent'");

$total_absent = $total_emp - $todayin;

$date = date('Y-m-d');
$sql_att = $obj->executequery("select * from attendance_entry where unit_id='$unitid' and attendance_date='$date'");
$today_latein = 0;
foreach ($sql_att as $key) {
    $shift_id = $key['shift_id'];
    $emp_id = $key['emp_id'];
    $in_time =  $obj->getvalfield("shift_master", "in_time", "shift_id='$shift_id'");
    $in_margin = $obj->getvalfield("shift_master", "grace_time_in", "shift_id='$shift_id'");
    if (empty($in_margin)) {
        $in_margin = 0;
    }
    $adjusted_in_time = date('H:i:s', strtotime('+' . $in_margin . ' minutes', strtotime($in_time)));
    $late_count = $obj->getvalfield("attendance_entry", "count(*)", "emp_id='$emp_id' and attendance_date ='$date' and intime > '$adjusted_in_time'");
    $today_latein += $late_count; // Accumulate the result
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Dashboard</title>
    <?php include('inc/css.php') ?>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include('inc/header.php') ?>

        <!-- ========== App Menu ========== -->
        <?php include('inc/sidebar.php') ?>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="h-100">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card">
                                            <div class="card-header text-center bg-primary ">
                                                <h5 class="text-white mb-0"> Total Employees</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img src="assets/images/user.png" class="w-100" alt="">
                                                    </div>
                                                    <div class="col-9">
                                                        <h3 class="mt-2 text-end"> <?= $total_emp ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card">
                                            <div class="card-header text-center bg-primary ">
                                                <h5 class="text-white mb-0"> Today In</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img src="assets/images/user.png" class="w-100" alt="">
                                                    </div>
                                                    <div class="col-9">
                                                        <h3 class="mt-2 text-end"> <?= $todayin; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card">
                                            <div class="card-header text-center bg-primary ">
                                                <h5 class="text-white mb-0"> Today Absent</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img src="assets/images/user.png" class="w-100" alt="">
                                                    </div>
                                                    <div class="col-9">
                                                        <h3 class="mt-2 text-end"> <?= $total_absent; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                    <div class="col-xl-3 col-md-6">
                                        <!-- card -->
                                        <div class="card">
                                            <div class="card-header text-center bg-primary ">
                                                <h5 class="text-white mb-0"> Today Late In</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <img src="assets/images/user.png" class="w-100" alt="">
                                                    </div>
                                                    <div class="col-9">
                                                        <h3 class="mt-2 text-end"> <?= $today_latein; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                </div> <!-- end row-->

                            </div> <!-- end .h-100-->

                        </div> <!-- end col -->


                    </div>

                </div>
                <!-- container-fluid -->

                <div class="row mt-4">
                    <!-- Department Chart -->
                    <div class="col-md-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <h5 class="card-title">Salary Distribution by Department of <?= $prevMonth . " " . $prevYear; ?></h5>
                                <div style="height:400px;">
                                    <canvas id="deptChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Branch Chart -->
                    <div class="col-md-6">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <h5 class="card-title">Salary Distribution by Branch of <?= $prevMonth . " " . $prevYear; ?></h5>
                                <div style="height:400px;">
                                    <canvas id="branchChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Department Chart (Bar)
        new Chart(document.getElementById('deptChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($deptLabels); ?>,
                datasets: [{
                    label: 'Total Salary (₹)',
                    data: <?= json_encode($deptValues); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Branch Chart (Pie)
        new Chart(document.getElementById('branchChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: <?= json_encode($branchLabels); ?>,
                datasets: [{
                    data: <?= json_encode($branchValues); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw || 0;
                                return context.label + ': ₹' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>