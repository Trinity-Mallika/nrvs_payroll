<?php include("../adminsession.php");
$pagename = "department_master_list.php";
$pagename2 = "department_master.php";
$title = "DEPARTMENT Master";
$tblname = "department_master";
$tblpkey = "department_id";
$module = "Department Master";
$submodule = "Department Master List";
$btn_name = "Search";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

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

    .detail-row {
        display: none;
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
                    <form method="get" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $submodule; ?><a
                                                        href="department_master.php"
                                                        class="float-end btn btn-primary btn-sm">Add Department</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_name" class="form-label">Department Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="department_name" name="department_name"
                                                class="form-control form-control-sm" placeholder="Enter Department Name"
                                                value="<?php echo isset($_GET['department_name']) ? $_GET['department_name'] : ''; ?>"
                                                autocomplete="off" />
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">C-Off Status</label>
                                            <select name="c_off_check"
                                                class="form-control form-control-sm chosen-select">
                                                <option value="">-- Select --</option>
                                                <option value="1"
                                                    <?= (isset($_GET['c_off_check']) && $_GET['c_off_check'] == '1') ? 'selected' : '' ?>>
                                                    Allowed</option>
                                                <option value="0"
                                                    <?= (isset($_GET['c_off_check']) && $_GET['c_off_check'] == '0') ? 'selected' : '' ?>>
                                                    Not Allowed</option>
                                            </select>

                                        </div>
                                        
                                            <div class="col-lg-4 mb-3 mt-2">
                                                <br>
                                                <input type="hidden" name="<?php echo $tblpkey ?>"
                                                    value="<?php echo $keyvalue ?>">
                                                <input type="submit" class="btn btn-sm btn-primary add-btn"
                                                    value="<?php echo $btn_name ?> ">
                                                <a href=" <?php echo $pagename ?>" type="button"
                                                    class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                                                <th>Sr No.</th>
                                                
                                                <th>Sub Division</th>
                                                <th>Department Name</th>
                                                <th>Reporting Manager Name</th>
                                                <th>C-Off</th>

                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                                $slno = 1;
                                                $search_department = isset($_GET['department_name']) ? $obj->test_input($_GET['department_name']) : '';
                                                $c_off_filter = isset($_GET['c_off_check']) ? $_GET['c_off_check'] : '';

                                                // $where = "unit_id='$unitid'";
                                                $where = "t.unit_id='$unitid'";

                                                if ($search_department != '') {
                                                    $where .= " AND department_name LIKE '%$search_department%'";
                                                }

                                                if ($c_off_filter !== '') {
                                                    $where .= " AND c_off_check = '$c_off_filter'";
                                                }

                                                $res = $obj->executequery("
                                                    SELECT 
                                                        t.*,
                                                        em.first_name,
                                                        em.emp_code,

                                                        cu.fullname as created_name,
                                                        cu.username as created_username,
                                                        cu.mobile as created_mobile,

                                                        uu.fullname as updated_name,
                                                        uu.username as updated_username,
                                                        uu.mobile as updated_mobile,
                                                        sm.sub_division_name AS subdivision_name
                                                       
                                                    FROM $tblname t

                                                    LEFT JOIN user cu 
                                                        ON t.createdby = cu.userid

                                                    LEFT JOIN employee_master em 
                                                        ON t.emp_id = em.emp_id

                                                    LEFT JOIN user uu 
                                                        ON t.updatedby = uu.userid

                                                    LEFT JOIN subdivision_master sm 
                                                        ON t.subdivision_id = sm.subdivision_id                               

                                                    WHERE $where

                                                    ORDER BY t.$tblpkey DESC
                                                ");


                                                foreach ($res as $row) {
                                                   
                                                ?>
                                                <tr data-details="
                                    <div style='background:#dafced; padding:4px;'>
                                    <?php if (!empty($row['created_name'])): ?>
                                    Added by (User: <?= $row['created_name'] ?>,
                                    Username: <?= $row['created_username'] ?>,
                                    Mobile: <?= $row['created_mobile'] ?>,
                                     Date: <?= $row['createdate'] ?>,)<br>
                                    <?php endif; ?>

                                    <?php if (!empty($row['updated_name'])): ?>
                                    Last Edited by (User: <?= $row['updated_name'] ?>,
                                    Username: <?= $row['updated_username'] ?>,
                                    Mobile: <?= $row['updated_mobile'] ?>,
                                    Date: <?= $row['lastupdated'] ?>) 
                                    <?php endif; ?>
                                     </div>
                                ">

                                                    <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $slno++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>

                                                    </td>
                                                    
                                                    <td><?php echo $row["subdivision_name"]; ?></td>
                                                    <td><?php echo $row["department_name"]; ?></td>
                                                    <td><?= $row["emp_code"].'-'. $row["first_name"]; ?></td>
                                                    <td>
                                                        <?= ($row['c_off_check'] == 1) ? 'Allowed' : 'Not Allowed' ?>
                                                    </td>

                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <?php $chkedit = $obj->check_editBtn($pagename, $loginid);
                                                            if ($chkedit == 1) {  ?>
                                                                <li class="list-inline-item " data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Edit">
                                                                    <a href="<?php echo $pagename2 ?>?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>"
                                                                        class="edit-item-btn"><i
                                                                            class="ri-pencil-fill align-bottom text-success"></i></a>
                                                                </li>
                                                            <?php }
                                                            $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                            if ($chkdel == 1) {  ?>
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Delete">
                                                                    <a class="remove-item-btn" type="button"
                                                                        onclick="funDel(<?php echo $row[$tblpkey]; ?>);">
                                                                        <i
                                                                            class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
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

        $(document).ready(function() {

            var table = $('#buttons-datatables').DataTable();

            $('#buttons-datatables tbody').on('click', 'td.details-control', function() {

                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    var details = tr.data('details');
                    row.child(details).show();
                    tr.addClass('shown');
                }

            });

        });
    </script>
</body>

</html>