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

$pagename = "perform_incent_excel.php";
$title = "Performance Incentive Excel ";
$module = "Performance Incentive Excel";
$submodule = "Performance Incentive Excel";
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
                    if ($firstRow) {
                        $firstRow = false;
                        continue;
                    } 
                    list($emp_code,$is_perform_incen) = $data; 
 
                    if (empty($emp_code)) {
                        continue;
                    } 
                    $totalRecords++;    
                    if ($is_perform_incen == 'Yes'|| $is_perform_incen == 'YES') { 
                        $is_perform_incen = '1';
                    } else {
                        $is_perform_incen = '0';
                    }  
                    $where = ['emp_code'=>$emp_code , "unit_id"=>$unitid];

                    $obj->update_record('employee_master', $where, ["is_perform_incen"=> $is_perform_incen , "lastupdated"=>$createdate]); 
                    $insertedCount++;
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
                                           
                                        </div>

                                        <div class="col-md-4 text-end">
                                            <a href="perform_incentive.php" class="btn btn-primary btn-sm">
                                              Incentive Report
                                            </a>
                                            <a href="perform_incen.xlsx" class="btn btn-primary btn-sm">
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
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                        <div class="col-lg-4 mb-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary btn-sm"
                                                value="<?php echo $btn_name ?> "
                                                onClick="return checkinputmaster('upload_excel')">
                                            <a href="<?php echo $pagename ?>" type="button"
                                                class="btn btn-danger btn-sm">Reset</a>
                                        </div>
                                         <?php } ?>
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
        <td>{$row['biomatric_id']}</td>
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