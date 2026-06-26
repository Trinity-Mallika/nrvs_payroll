<?php include("../adminsession.php");

$title = "Page Privellege";
$pagename = "page_privellege.php";
$module = "Page Privellege";
$submodule = "Page Privellege";
$btn_name = "Save";
$keyvalue = 0;
$tblname = "m_userprivilege";
$tblpkey = "page_id";

if (isset($_GET['page_id'])) {
    $keyvalue = $_GET['page_id'];
}

if (isset($_GET['action'])) {
    $action = addslashes(trim($_GET['action']));
} else {
    $action = "";
}

if (isset($_POST['submit'])) {
    //print_r($_POST); die;
    $menuname = $obj->test_input($_POST['menuname']);
    $page_heading = $obj->test_input($_POST['page_heading']);
    $pagelink = $obj->test_input($_POST['pagelink']);
    $page_type = $obj->test_input($_POST['page_type']);

    //check Duplicate

    $count = $obj->getvalfield($tblname, "count(*)", "pagelink='$pagelink' and type='hrms' and $tblpkey !='$keyvalue'");
    if ($count == 0) {
        if ($keyvalue == 0) {
            //insert
            $form_data = array(
                'menuname' => $menuname,
                'type' => 'hrms',
                'pagelink' => $pagelink,
                'page_type' => $page_type,
                'page_heading' => $page_heading,
                'ipaddress' => $ipaddress,
                'createdate' => $createdate,
                'createdby' => $loginid
            );
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
            echo "<script>location='$pagename?action=$action'</script>";
        } else {
            //update 
            $form_data = array('menuname' => $menuname, 'pagelink' => $pagelink, 'page_type' => $page_type,'page_heading' => $page_heading, 'ipaddress' => $ipaddress, 'lastupdated' => $createdate, 'createdby' => $loginid);
            $where = array($tblpkey => $keyvalue);
            $keyvalue = $obj->update_record($tblname, $where, $form_data);
            $action = 2;
            $process = "updated";
        }
    } else {
        $action = 4;
    }
    echo "<script>location='$pagename?action=$action'</script>";
}

if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $menuname =  $sqledit['menuname'];
    $page_heading =  $sqledit['page_heading'];
    $pagelink =  $sqledit['pagelink'];
    $page_type =  $sqledit['page_type'];
} else {
    $menuname =  $obj->getvalfield($tblname, "menuname", "1=1 order by  $tblpkey desc");
    $page_heading =  "";
    $pagelink =  "";
    $page_type =  "";
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
                <?php include('inc/bredcrum.php') ?>
                <?php include('inc/alert.php'); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <strong> <label for="category">MenuName <span
                                                        class="text-danger fw-bold">*</span></label></strong>
                                            <input type="text" class="form-control form-control-sm" name="menuname"
                                                id="menuname" value="<?php echo $menuname; ?>"
                                                placeholder="Enter MenuName" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <strong> <label for="category">Page Heading <span
                                                        class="text-danger fw-bold">*</span></label></strong>
                                            <input type="text" class="form-control form-control-sm" name="page_heading"
                                                id="page_heading" value="<?php echo $page_heading; ?>"
                                                placeholder="Enter Page Heading" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <strong> <label for="category">Page Link <span
                                                        class="text-danger fw-bold">*</span></label></strong>
                                            <input type="text" class="form-control form-control-sm" name="pagelink"
                                                id="pagelink" value="<?php echo $pagelink; ?>"
                                                placeholder="Enter Page Link " autocomplete="off">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Page Type<span class="text-danger fw-bold">*</span>
                                            </label>
                                            <select class="form-select form-select-sm chosen-select" name="page_type"
                                                id="page_type">
                                                <option value="">Select</option>
                                                <option value="Entry Pages">Master</option> 
                                                <option value="Reports">Report</option> 
                                                <option value="Excel Upload">Excel</option> 
                                            </select>
                                            <script>
                                            document.getElementById('page_type').value =
                                                '<?php echo ucfirst(strtolower($page_type)); ?>';
                                            </script>
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <input type="submit"
                                                onclick="return checkinputmaster('menuname,page_heading,pagelink')"
                                                name="submit" class="btn btn-primary btn-sm"
                                                value="<?php echo $btn_name; ?>">
                                            <input type="hidden" name="<?php echo $tblpkey; ?>"
                                                id="<?php echo $tblpkey; ?>" value="<?php echo $keyvalue; ?>">
                                            <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm"> Reset </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 mb-4">
                    <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span
                                                    class="text-danger"></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>S.No.</th>
                                                <th>MenuName</th>
                                                <th>Page Type</th>
                                                <th>Page Heading</th>
                                                <th>Page Link</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $slno = 1;
                                            $res = $obj->executequery("select * from $tblname where type='hrms' order by $tblpkey desc");
                                            foreach ($res as $row_get) {
                                            ?>
                                            <tr>
                                                <td><?php echo $slno++; ?></td>
                                                <td><?php echo $row_get['menuname'] ?></td>
                                                <td><?php echo $row_get['page_type']?></td>
                                                <td><?php echo $row_get['page_heading'] ?></td>
                                                <td><?php echo $row_get['pagelink'] ?></td>
                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item " data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top"
                                                            title="Edit">
                                                            <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row_get[$tblpkey]; ?>"
                                                                class="edit-item-btn"><i
                                                                    class="ri-pencil-fill align-bottom text-success"></i></a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top"
                                                            title="Delete">
                                                            <a class="remove-item-btn" type="button"
                                                                onclick="funDel(<?php echo $row_get[$tblpkey]; ?>);">
                                                                <i
                                                                    class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <?php  } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Content close-->
        </div>
    </div>

    <?php include('inc/delete.php') ?>
    <?php include('inc/js.php') ?>
    <?php include('inc/footer.php') ?>
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
                data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' +
                    submodule + '&pagename=' + pagename,
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
    </script>
</body>

</html>