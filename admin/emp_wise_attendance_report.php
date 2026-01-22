<?php
include("../adminsession.php");

$title = "Employee Wise Attendance Report";
$pagename = "emp_wise_attendance_report.php";
$module = "Search Employee Attendance";
$submodule = "Employee Wise Attendance List";
$tblname = "attendance_entry";
$tblpkey = "attendance_id";

$userid = $_SESSION['userid'];

$month = date('m');
$year = date('Y');
$emp_id = "";

// Get filters from GET
if (isset($_GET['month'])) $month = $obj->test_input($_GET['month']);
if (isset($_GET['year'])) $year = $obj->test_input($_GET['year']);
if (isset($_GET['emp_id'])) $emp_id = $_GET['emp_id'];

$crit = " WHERE month='$month' AND year='$year' AND emp_id='$emp_id'";
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <?php include('inc/css.php') ?>
    <style>
        .table-borderless tr td {
            border: 0 !important;
            padding-bottom: 0;
        }

        .status-present {
            background-color: rgb(173, 233, 179);
        }

        .status-absent {
            background-color: rgb(251, 175, 175);
        }
    </style>
</head>

<body>
    <?php include('inc/header.php') ?>
    <?php include('inc/sidebar.php') ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php include('inc/bredcrum.php') ?>

                <!-- Filter Form -->
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="mt-2">
                            <form action="<?= $pagename ?>" method="get">
                                <div class="card">
                                    <div class="card-header"><?= $module ?></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Employee -->
                                            <div class="col-md-4 mb-2">
                                                <label>Employee Name<span class="text-danger">*</span></label>
                                                <select name="emp_id" id="emp_id" class="chosen-select form-control form-control-sm">
                                                    <option value="">--Select Employee--</option>
                                                    <?php
                                                    $employees = $obj->executequery("SELECT * FROM employee_master ORDER BY emp_name");
                                                    foreach ($employees as $emp) {
                                                        $branch = $obj->getvalfield("branch_master", "branch_name", "branch_id='{$emp['branch_id']}'");
                                                        $selected = ($emp_id == $emp['emp_id']) ? 'selected' : '';
                                                        echo "<option value='{$emp['emp_id']}' $selected>{$emp['emp_name']} / {$branch}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- Year -->
                                            <div class="col-md-3 mb-2">
                                                <label>Year<span class="text-danger">*</span></label>
                                                <select name="year" id="year" class="chosen-select form-control form-control-sm">
                                                    <?php for ($i = 2000; $i <= date('Y'); $i++): ?>
                                                        <option value="<?= $i ?>" <?= ($year == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>

                                            <!-- Month -->
                                            <div class="col-md-3 mb-2">
                                                <label>Month<span class="text-danger">*</span></label>
                                                <select name="month" id="month" class="chosen-select form-control form-control-sm">
                                                    <?php for ($i = 1; $i <= 12; $i++):
                                                        $val = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                        $selected = ($month == $val) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $val ?>" <?= $selected ?>><?= date("F", strtotime("2025-$val-01")) ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-4">
                                                <input type="submit" class="btn btn-primary btn-sm" value="Search" onclick="return checkinputmaster('emp_id,year,month');">
                                                <a href="<?= $pagename ?>" class="btn btn-danger btn-sm">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <?= $submodule ?>
                                <button class="btn btn-primary btn-sm" onclick="exportTableToExcel('example')">Export Excel</button>
                            </div>
                            <div class="card-body table-responsive">
                                <table id="example" class="table table-bordered table-sm table-hover text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Date</th>
                                            <th>In Time</th>
                                            <th>Out Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $slno = 1;
                                        $res = $obj->executequery("SELECT * FROM $tblname $crit ORDER BY attendance_date ASC");
                                        foreach ($res as $row):
                                            $in_status = $row['in_status'];
                                            $out_status = $row['out_status'];

                                            // Determine row color based on in_status
                                            switch ($in_status) {
                                                case 'Present':
                                                    $class = "status-present";
                                                    break;
                                                case 'Absent':
                                                    $class = "status-absent";
                                                    break;
                                                case 'Halfday':
                                                    $class = "status-halfday";
                                                    break;
                                                default:
                                                    $class = "status-others";
                                                    break;
                                            }

                                        ?>
                                            <tr class="<?= $class ?>">
                                                <td><?= $slno++ ?></td>
                                                <td><?= $obj->dateformatindia($row['attendance_date']) ?></td>
                                                <td><?= $row['intime'] ?></td>
                                                <td><?= $row['outtime'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>

    <script>
        $(document).ready(function() {
            $('#example').DataTable().destroy();
            $('#example').DataTable({
                lengthMenu: [
                    [50, 100, 500, 1000],
                    [50, 100, 500, 1000]
                ],
                pageLength: 50
            });
            $(".chosen-select").chosen();
        });

        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            filename = filename ? filename + '.xls' : 'excel_data.xls';
            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }
        }
    </script>

</body>

</html>