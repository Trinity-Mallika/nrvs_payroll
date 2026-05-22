<?php include("../adminsession.php");
$pagename = "holiday_entry_list.php";
$pagename2 = "holiday_entry.php";
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
                    <form method="post" action="">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $submodule; ?><a
                                                        href="holiday_entry.php"
                                                        class="float-end btn btn-primary btn-sm">Add Holiday</a></h5>
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
                                                        <th>Holiday Title</th>
                                                        <th>Holiday Type</th>
                                                        <th>Unit</th>
                                                        <th style="font-weight: bold;">Start Date</th>
                                                        <!-- <th>Created By</th>
                                                        <th>Updated By</th> -->
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody><?php
                                                        $slno = 1;
                                                        // $res = $obj->executequery("select * from $tblname  order by $tblpkey desc");
                                                        $res = $obj->executequery("
                                                            SELECT 
                                                                t.*,

                                                                cu.fullname as created_name,
                                                                cu.username as created_username,
                                                                cu.mobile as created_mobile,

                                                                uu.fullname as updated_name,
                                                                uu.username as updated_username,
                                                                uu.mobile as updated_mobile

                                                            FROM $tblname t

                                                            LEFT JOIN user cu 
                                                                ON t.createdby = cu.userid

                                                            LEFT JOIN user uu 
                                                                ON t.updatedby = uu.userid

                                                            ORDER BY t.$tblpkey DESC
                                                        ");
                                                        foreach ($res as $row) {
                                                            if (!empty($row['unit_id'])) {
                                                                $unit_name = $obj->getvalfield(
                                                                    "unit_master",
                                                                    "GROUP_CONCAT(unit_name)",
                                                                    "unit_id IN ({$row['unit_id']})"
                                                                );
                                                            } else {
                                                                $unit_name = '';
                                                            }

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
                                                        <td><?php echo $row["holiday_tittle"]; ?></td>
                                                        <td><?php echo $row["holiday_type"]; ?></td>
                                                        <td><?php echo $unit_name ?></td>
                                                        <td><?php echo $obj->dateformatindia($row['date']); ?></td>
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
                                                                <?php  } ?>
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