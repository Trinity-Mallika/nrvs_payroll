<?php include("../adminsession.php");
$pagename = "daily_attendance_entry.php";
$title = "Daily Attendance";
$module = "Daily Attendance";
$submodule = "Daily Attendance";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "";
$tblpkey = "";


?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>

    <?php include('inc/css.php') ?>
    <link rel="stylesheet" href="assets/css/toogle.css">
</head>
<style>
    .cls-read {
        pointer-events: none;
        background-color: #f5f5f5;
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
                <!-- end page title -->
                <?php //include('inc/alert.php'); 
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="bill_no" class="form-label">Company Name</label>
                                            <h6 class="text-dark fw-bold"> AGRAWAL INFRABUILD PRIVATE LIMITED</h6>
                                        </div>
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="bill_date" class="form-label">Branch Name</label>
                                            <h6 class="text-dark fw-bold">CG_HEAD OFFICE</h6>
                                        </div>
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="" class="form-label">Date</label>
                                            <input type="date" class="form-control form-control-sm" name="" id="" autocomplete="off">
                                        </div>
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="" class="form-label">Attachment:</label>
                                            <input type="file" class="form-control form-control-sm" name="" id="" autocomplete="off">
                                        </div>
                                        <div class="col-12 col-lg-3 mt-1 ">
                                            <input type="submit" class="btn btn-sm btn-success" name="submit" value="<?php echo $btn_name; ?>">
                                            <input type="hidden" name="<?php echo $tblpkey; ?>" id="<?php echo $tblpkey; ?>" value="<?php echo $keyvalue; ?>">
                                            <a href="<?php echo $pagename; ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header ">
                                <h5 class="mb-0"><?php echo $module; ?></h5>
                            </div>
                            <div class="card-body">

                                <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-primary">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>
                                                <select name="" id="" class="form-select form-select-sm">
                                                    <option value="NA">NA</option>
                                                </select>
                                            </th>
                                            <th>
                                                <input type="time" name="" id="" class="form-control form-control-sm">
                                            </th>
                                            <th>
                                                <input type="time" name="" id="" class="form-control form-control-sm">
                                            </th>
                                            <th>
                                                <a href="#0" class="btn btn-danger btn-sm">Fill Time</a>
                                            </th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Sr. No.</th>
                                            <th>Employee Name / Department Name /
                                                Designation Name</th>
                                            <th>Shift Name</th>
                                            <th></th>
                                            <th>Status</th>
                                            <th>In Time</th>
                                            <th>Out time</th>
                                            <th>Remark</th>
                                            <th>On Duty / Leave Detail</th>
                                            <th>Punch Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1. &nbsp; <i class="bi bi-file-earmark-text"></i></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <select name="" id="" class="form-select form-select-sm">
                                                    <option value="NA">NA</option>
                                                </select>
                                            </td>
                                            <td><input type="time" name="" id="" class="form-control form-control-sm"></td>
                                            <td><input type="time" name="" id="" class="form-control form-control-sm"></td>
                                            <td>
                                                <input type="text" name="" id="" class="form-control form-control-sm">
                                                <span>Update By EXE</span>
                                            </td>
                                            <td></td>
                                            <td>10:35AM</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Third col End -->
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
        });
    </script>

</body>

</html>