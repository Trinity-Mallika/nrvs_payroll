<?php include("../adminsession.php");
$pagename = "session_master.php";
$title = "Financial Year";
$module = "Financial Master";
$submodule = "Financial Year";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "m_session";
$tblpkey = "sessionid";

if (isset($_GET['sessionid']))
    $keyvalue = $_GET['sessionid'];
else
    $keyvalue = 0;
if (isset($_GET['action']))
    $action = addslashes(trim($_GET['action']));
else
    $action = "";
$dup = "";
$fromdate = $todate = $session_name = "";

if (isset($_GET['st'])) {
    $st = $_GET['st'];
    $s = $_GET['status'];
    if ($s != '') {
        $where = array('status' => 1);
        $myArray = array("status" => 0);
        $obj->update_record($tblname, $where, $myArray);
        $where = array($tblpkey => $st);
        $myArray = array("status" => 1);
        $obj->update_record($tblname, $where, $myArray);
    }
}
if (isset($_POST['submit'])) {
    $keyvalue = $obj->test_input($_POST['sessionid']);
    $fromdate =  $_POST['fromdate'];
    $todate  =  $_POST['todate'];
    $session_name =  $obj->test_input($_POST['session_name']);

    //check Duplicate
    $count = $obj->getvalfield("m_session", "count(*)", "session_name='$session_name' and sessionid!='$keyvalue'");

    if ($count > 0) {
        $action = 4;
    } else //insert
    {
        if ($keyvalue == 0) {
            $form_data = array('fromdate' => $fromdate, 'todate' => $todate, 'session_name' => $session_name, 'ipaddress' => $ipaddress,  'createdate' => $createdate);
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        } else {
            //update
            $form_data = array('fromdate' => $fromdate, 'todate' => $todate, 'session_name' => $session_name, 'ipaddress' => $ipaddress,  'lastupdated' => $createdate);
            $where = array($tblpkey => $keyvalue);
            $keyvalue = $obj->update_record($tblname, $where, $form_data);
            $action = 2;
            $process = "updated";
        }
        echo "<script>location='$pagename?action=$action'</script>";
    }
}
if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $fromdate = $sqledit['fromdate'];
    $todate = $sqledit['todate'];
    $session_name = $sqledit['session_name'];
} else {
    $fromdate = date('Y-m-d');
    $todate = date('Y-m-d');
}
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
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="bill_no" class="form-label">From Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" placeholder='dd-mm-yyyy' value="<?php echo $fromdate; ?>">
                                        </div>
                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="bill_date" class="form-label">To Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" class="form-control form-control-sm" name="todate" id="todate" placeholder='dd-mm-yyyy' value="<?php echo $todate; ?>">
                                        </div>

                                        <div class="mb-3 col-12 col-lg-3">
                                            <label for="amount" class="form-label">Session<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="session_name" id="session_name" value="<?php echo $session_name; ?>" autocomplete="off" placeholder="yyyy-yy">
                                        </div>
                                        <div class="col-12 col-lg-3 mt-4 ">
                                            <input type="submit" class="btn btn-sm btn-success" name="submit" value="<?php echo $btn_name; ?>" onClick="return checkinputmaster('fromdate,todate,session_name');">
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
                                            <th>Sr. No.</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Session Name</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $slno = 1;
                                        $res = $obj->executequery("select * from m_session order by sessionid desc");
                                        foreach ($res as $row_get) {
                                        ?>
                                            <tr>
                                                <td><?php echo $slno++; ?></td>
                                                <td><?php echo $obj->dateformatindia($row_get['fromdate']); ?></td>
                                                <td><?php echo $obj->dateformatindia($row_get['todate']); ?></td>
                                                <td><?php echo $row_get['session_name']; ?></td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center wt">
                                                        <div class="flex-grow-1 ms-2 name">
                                                            <label class="switch">
                                                                <input type="checkbox" <?php if ($row_get['status'] == 1) {
                                                                                            echo "checked";
                                                                                        } ?>>
                                                                <span class="slider round" onclick="change_status('<?php echo $row_get['sessionid']; ?>','<?php echo $row_get['status']; ?>');"> </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                            <a href="session_master.php?sessionid=<?php echo $row_get['sessionid']; ?>" class="edit-item-btn"><i class="ri-pencil-fill align-bottom text-primary"></i></a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                            <a class="remove-item-btn" onclick="funDel(<?php echo $row_get['sessionid']; ?>);">
                                                                <i class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        <?php } ?>
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

        function change_status(st, status) {
            if (st != "") {
                if (confirm("Are you sure! You want to active this session.")) {
                    location = '<?php echo $pagename; ?>?st=' + st + '&status=' + status;
                } else {
                    location = '<?php echo $pagename; ?>';
                }
            }
        }

        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename; ?>';
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };
    </script>

</body>

</html>