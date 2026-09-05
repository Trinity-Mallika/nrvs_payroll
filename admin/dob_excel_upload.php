<?php
ini_set('max_execution_time', 600); // 10 minutes
set_time_limit(600);
include("../adminsession.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $time = date('H:i:s', strtotime('8:00:00'));
// echo $time;
// die;

$pagename = "dob_excel_upload.php";
$title = "Excel Upload Employee's ";
$module = "Excel Upload Employee's";
$submodule = "Employee's List";
$tblname = "employee_master";
$tblpkey = "emp_id";
$btn_name = "Save";
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
require_once __DIR__ . '/src/SimpleXLSX.php';

if (isset($_POST['submit'])) {
    $totalRecords = 0;
    $insertedCount = 0;
    $skippedCount = 0;
    $skippedEpicNumbers = array();
    $MAX_INSERT = 2500;

   if (isset($_FILES['upload_excel']['tmp_name']) && $_FILES['upload_excel']['error'] == UPLOAD_ERR_OK) {

    $fileType = pathinfo($_FILES['upload_excel']['name'], PATHINFO_EXTENSION);

    $firstRow = true;

    if ($fileType === 'xlsx') {

        if ($xlsx = SimpleXLSX::parse($_FILES['upload_excel']['tmp_name'])) {

            foreach ($xlsx->rows() as $k => $data) {
                // Skip Header Row
                if ($firstRow) {
                    $firstRow = false;
                    continue;
                }

                list($emp_code, $dob) = $data;

                if (empty($emp_code)) {
                    continue;
                }

                $totalRecords++;

                // Format DOB
                $dob = !empty($dob)
                    ? date('Y-m-d', strtotime($dob))
                    : null;

                // Find Employee
                $emp_id = $obj->getvalfield(
                    "employee_master",
                    "emp_id",
                    "emp_code='" . addslashes(trim($emp_code)) . "' 
                    AND unit_id='$unitid'"
                ); 

                // If employee found then update
                if ($emp_id > 0) {
                    $update_data = array(
                        "dob" => $dob
                    );
                    $obj->update_record("employee_master",["emp_id"=>$emp_id, "unit_id"=>$unitid] ,$update_data);
                    $insertedCount++;
                } else {
                    $skippedEpicNumbers[] = [
                        'row_no'   => $k + 1,
                        'emp_code' => $emp_code,
                        'reason'   => 'Employee Not Found'
                    ];

                    $skippedCount++;
                }
            }
        }
    }
}
    //die;
    $skippedData = urlencode(json_encode($skippedEpicNumbers));
    echo "<script>
location = '$pagename?action=1&total=$totalRecords&inserted=$insertedCount&skipped=$skippedCount&skipped_epics=$skippedData';
</script>";
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
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="card-title mb-1">
                                                <?= $module; ?>
                                            </h5>
                                            <small class="text-danger fw-semibold">
                                                <b> Note:</b> <br>
                                                • Biometric ID is compulsory.<br>
                                                • Biometric ID, Employee Code, and Mobile Number must be unique.<br>
                                                • Rows with missing or duplicate values will be skipped during upload.
                                            </small>
                                        </div>

                                        <div class="col-md-4 text-end">
                                            <a href="employee_excel_new.xlsx" class="btn btn-primary btn-sm">
                                                Download Sample File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <strong><label for="">Upload File <span
                                                        class="text-danger fw-bold">*</span></label></strong>
                                            <input type="file" name="upload_excel" id="upload_excel"
                                                class="form-control form-control-sm" accept=".xlsx">
                                        </div>
                                        <div class="col-lg-4 mb-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm"
                                                value="<?php echo $btn_name ?> "
                                                onClick="return checkinputmaster('upload_excel')">
                                            <a href="<?php echo $pagename ?>" type="button"
                                                class="btn btn-danger btn-sm">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if (isset($_GET['total'])): ?>
                <div class="alert alert-info mt-3">
                    <strong>Data Processing Results:</strong>
                    <p>Total Records: <?php echo htmlspecialchars($_GET['total']); ?></p>
                    <p>Successfully Inserted: <?php echo htmlspecialchars($_GET['inserted']); ?></p>
                    <p>Skipped (Duplicates): <?php echo htmlspecialchars($_GET['skipped']); ?></p>
                    <?php
                        if (!empty($_GET['skipped_epics'])) {
                            $skippedEpicNumbers = json_decode(urldecode($_GET['skipped_epics']), true);

                            echo "<table   cellpadding='5'>
                            <tr>
                                <th>Row Number</th>
                                <th>Emp Code</th>
                                <th>Biometric ID</th>
                                
                                <th>Mobile No</th>
                                 <th>Reason</th>
                            </tr>";

                            foreach ($skippedEpicNumbers as $row) {
                                echo "<tr>
                               <td>{$row['row_no']}</td>
        <td>{$row['emp_code']}</td>
       
        <td>{$row['mobile_no']}</td>
        <td>{$row['reason']}</td>
                            </tr>";
                            }

                            echo "
                        </table>";
                        }
                        ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
    <!-- Content close-->
</body>

</html>