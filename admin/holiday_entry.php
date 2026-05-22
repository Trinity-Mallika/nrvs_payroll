<?php include("../adminsession.php");
$pagename = "holiday_entry.php";
$title = "HOLIDAY Master";
$tblname = "holiday_entry";
$tblpkey = "holiday_id";
$module = "Holiday Master";
$submodule = "Holiday Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$unit_id = "";
$setting_id = $_SESSION['setting_id'] ?? 0;


if (isset($_POST['submit'])) {

    $holiday_tittle  = $obj->test_input($_POST['holiday_tittle']);
    $date = $obj->test_input($_POST['date']);
    $holiday_type = $obj->test_input($_POST['holiday_type']);
    $unit_id = implode(',', $_POST['unit_id'] ?? []);
    $count = $obj->getvalfield($tblname, "count(*)", "holiday_tittle='$holiday_tittle' and $tblpkey!='$keyvalue'");

    $form_data = array(
        'holiday_tittle' => $holiday_tittle,
        'sessionid'   => $sessionid,
        'unit_id'   => $unit_id,
        'holiday_type'   => $holiday_type,
        'date'           => $date,
        'createdby'      => $loginid,
        'ipaddress'      => $ipaddress
    );
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;
            $obj->insert_record($tblname, $form_data);
            $action  = 1;
            $process = "insert";
        } else {
            $form_data["lastupdated"] = $createdate;
            $form_data["updatedby"] = $loginid;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
            $action  = 2;
            $process = "updated";
        }
    }

    echo "<script>location='$pagename?action=$action'</script>";
}
$is_all_unit = false;
if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $holiday_tittle =  $sqledit['holiday_tittle'];
    $unit_id =  $sqledit['unit_id'];
    $holiday_type =  $sqledit['holiday_type'];
    $date = $sqledit['date'];
} else {
    $holiday_tittle =  $unit_id = $holiday_type =  "";
    $is_all_unit = true;
}
$treeLevels = [
    [
        'id' => 'unit_id',
        'label' => 'unit_name',
        'query' => "SELECT unit_id, unit_name FROM unit_master WHERE is_deleted='0' AND setting_id='$setting_id'"
    ]
];

$tree = $obj->buildSiteTree($treeLevels);


?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

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
                <?php include('inc/bredcrum.php') ?>
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <form method="post" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?><a href="holiday_entry_list.php" class="float-end btn btn-primary btn-sm">Holiday List</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="holiday_tittle" class="form-label">Holiday Title<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="holiday_tittle" name="holiday_tittle" class="form-control form-control-sm" placeholder="Enter Holiday Title" value="<?php echo $holiday_tittle ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="date" class="form-label">Date<span class="text-danger fw-bold">*</span></label>
                                            <input type="date" id="date" name="date" class="form-control form-control-sm" value="<?php echo $date ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Holiday Type</label>
                                            <select class="form-select form-select-sm chosen-select" name="holiday_type"
                                                id="holiday_type">
                                                <option value="">Select</option>
                                                <option value="National">National</option>
                                                <option value="Seasonal">Seasonal</option>
                                                <option value="Religion">Religion</option>
                                            </select>
                                            <script>
                                                document.getElementById('holiday_type').value = '<?php echo ucfirst(strtolower($holiday_type)); ?>';
                                            </script>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Unit List<span class="text-danger fw-bold">*</span></label>
                                            <div class="p-2 border">
                                                <div class="tree-dropdown dropdown" style="width: 50%; max-height: 15%;">
                                                    <div class="tree-menu p-2" style="width: 190%; max-height: 50%;">
                                                        <ul class="list-unstyled nested-list tree-list mb-0 small">
                                                            <li>
                                                                <div class="form-check">
                                                                    <input
                                                                        class="form-check-input tree-check-all"
                                                                        type="checkbox" id="all">
                                                                    <label
                                                                        class="form-check-label fw-bold">ALL</label>
                                                                </div>
                                                                <?php echo $obj->renderCheckboxTree($tree, $treeLevels, 0, $unit_id); ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $chkadd = $obj->check_addBtn($pagename, $loginid);
                                        if ($chkadd == 1) {  ?>
                                            <div class="col-md-3 mb-3 mt-2">
                                                <br>
                                                <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return validateForm();">
                                                <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>
                <!--end col-->
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
    <?php include('inc/style-script-include.php') ?>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });
        });



        function funDel(id) {
            $('#deleteRecordModal').modal('show');
            tblname = '<?php echo $tblname; ?>';
            tblpkey = '<?php echo $tblpkey; ?>';
            pagename = '<?php echo $pagename; ?>';
            submodule = '<?php echo $submodule; ?>';

            $('#delete-record').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/delete_master.php',
                    data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                    dataType: 'html',
                    success: function(data) {
                        location = '<?php echo $pagename; ?>';
                    }
                });
                $('#deleteRecordModal').modal('hide');
            });
        };

        function numberOnly(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /[0-9]|\.|\s/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }



        function validateForm() {
            // Check required inputs
            if (!checkinputmaster('holiday_tittle,date')) {
                return false;
            }

            const checkboxes = document.querySelectorAll('.tree-list input[type="checkbox"]:not(#all)');
            let atLeastOneChecked = false;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    atLeastOneChecked = true;
                }
            });

            if (!atLeastOneChecked) {
                alert("Please Select Atleast One Unit.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>