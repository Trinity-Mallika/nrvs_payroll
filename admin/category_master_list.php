<?php include("../adminsession.php");
$pagename = "category_master_list.php";
$pagename2 = "category_master.php";
$title = "Category Master";
$tblname = "category_master";
$tblpkey = "category_id";
$module = "Category Master";
$submodule = "Category Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

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
                        <div class="col-lg-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><?php echo $submodule; ?><a href="category_master.php" class="float-end btn btn-primary btn-sm">Add Category</a></h5>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Category Name</th>
                                                    <th>Short Name</th>
                                                    <th>OT Formula</th>
                                                    <th>Min OT</th>
                                                    <th>Max OT</th>
                                                    <th>Weekly Off</th>
                                                    <th>Late Rule</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                // $res = $obj->executequery("select * from $tblname where unit_id='$unitid' order by $tblpkey desc");
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

                                                    WHERE t.unit_id = '$unitid'

                                                    ORDER BY t.$tblpkey DESC
                                                ");
                                                foreach ($res as $row) {

                                                    // OT Formula Text
                                                    $ot_text = "";
                                                    if ($row['ot_formula'] == 1) $ot_text = "Total Duration";
                                                    elseif ($row['ot_formula'] == 2) $ot_text = "No OT";
                                                    elseif ($row['ot_formula'] == 3) $ot_text = "Out - Shift End";
                                                    elseif ($row['ot_formula'] == 4) $ot_text = "Early + Late";

                                                    // Late Rule Text
                                                    $late_rule = "-";
                                                    if ($row['late_rule_enable'] == 1) {
                                                        $late_rule = ucfirst(str_replace("_", " ", $row['late_action'])) . " after " . $row['late_days'] . " days";
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
                                                        <td><?= $row['category_name']; ?></td>
                                                        <td><?= $row['short_name']; ?></td>
                                                        <td><?= $ot_text; ?></td>
                                                        <td><?= $row['min_ot']; ?> Min</td>
                                                        <td><?= $row['max_ot']; ?> Min</td>
                                                        <td><?= $row['weekly_off1'] . " / " . $row['weekly_off2']; ?></td>
                                                        <td><?= $late_rule; ?></td>
                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">

                                                                <!-- Edit -->
                                                                <li class="list-inline-item">
                                                                    <a href="<?= $pagename2 ?>?<?= $tblpkey ?>=<?= $row[$tblpkey]; ?>">
                                                                        <i class="ri-pencil-fill text-success"></i>
                                                                    </a>
                                                                </li>

                                                                <!-- Delete -->
                                                                <li class="list-inline-item">
                                                                    <a onclick="funDel(<?= $row[$tblpkey]; ?>)">
                                                                        <i class="ri-delete-bin-fill text-danger"></i>
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