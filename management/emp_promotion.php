<?php include("../adminsession.php");
$pagename = "emp_promotion.php";
$title = "Employee Promotion";
$tblname = "emp_promotion";
$tblpkey = "emp_promotion_id";
$module = "Employee Promotion";
$submodule = "Employee Promotion List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$emp_id = (isset($_GET['emp_id'])) ? $obj->test_input($_GET['emp_id']) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
if ($emp_id > 0) {
    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);
    $first_name = $emp_data['first_name'] ?? '';
    $last_name = $emp_data['last_name'] ?? '';
    $emp_code = $emp_data['emp_code'] ?? '';
    $date_of_joining = $emp_data['date_of_joining'] ?? '';
    $basic_salary = $emp_data['basic_salary'] ?? '';
    $shift_hrs = $emp_data['shift_id'] ?? '';
    $department_id = $emp_data['department_id'] ?? '';
    $designation_id = $emp_data['designation_id'] ?? '';
} else {
    $first_name = '';
    $last_name = '';
    $emp_code = '';
    $date_of_joining = '';
    $basic_salary = '';
    $shift_hrs = '';
    $department_id = '';
    $designation_id = '';
}

 
if (isset($_POST['department_iddd'])) {
    $department_id = $_REQUEST['department_iddd'];
    //  $depart_unit = $obj->getvalfield("department_master", "unit_id", "department_id='$department_id'");
    $options = "<option value=''>Select Designation</option>";
    $selected = "";
    if ($department_id != "" || $department_id > 0) {
        $res = $obj->executequery("Select * from designation_master where department_id='$department_id' order by designation asc");
        foreach ($res as $row) {
            $selected = $designation_id == $row['designation_id'] ? 'selected' : '';
            $options .= "<option value='" . $row['designation_id'] . " $selected' >" . $row['designation'] . "  </option>";
        }
    }

    echo $options;
    die;
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
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a
                                                        href="emp_promotion_list.php"
                                                        class="float-end btn btn-primary btn-sm ms-2">Pomotion Request
                                                        List</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-2">
                                            <label for="emp_id" class="form-label">Employee Name<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                id="emp_id" onchange="get_url(this.value);">
                                                <option value="0">Select Employee</option>
                                                <?php
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE  (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");
                                                foreach ($res as $key) { ?>
                                                <option value="<?= $key['emp_id']; ?>">
                                                    <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                    <?= ucfirst($key['last_name'] ?? ''); ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('emp_id').value = '<?= $emp_id; ?>';
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($_GET['emp_id'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <div class="table-responsive">

                                                <table id="promotion_table" class="display table table-sm table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Sr No.</th>
                                                            <th>Employee Name</th>
                                                            <th>Work Start Date</th>
                                                            <th>Unit Name</th>
                                                            <th>Department / Designation </th>
                                                            <th>Promote Amt</th>
                                                            <th>Basic Salary</th>
                                                            <th>Type</th>
                                                            <th>Remark</th>
                                                            <th>Effected Month/Year</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $sn = 1;
                                                            $res = $obj->executequery("SELECT ep.* , um.unit_name,dpt.department_name,des.designation,  em.first_name , em.last_name , em.emp_code ,cu.fullname as created_name,cu.username as created_username,cu.mobile as created_mobile, uu.fullname as updated_name, uu.username as updated_username, uu.mobile as updated_mobile  from emp_promotion as ep left join unit_master um on ep.unit_id=um.unit_id left join department_master dpt on ep.department_id=dpt.department_id left join designation_master des on ep.designation_id=des.designation_id left join employee_master em on ep.emp_id=em.emp_id LEFT JOIN user cu ON em.createdby = cu.userid LEFT JOIN user uu ON em.updatedby = uu.userid  where ep.emp_id='$emp_id' order by emp_promotion_id desc");
 

                                                            foreach ($res as $key) {

                                                            ?>
                                                        <tr id="tr_<?= $row["emp_promotion_id"]; ?>" data-details="
                                    <div style='background:#dafced; padding:4px;'>
                                    <?php if (!empty($key['created_name'])): ?>
                                    Added by (User: <?= $key['created_name'] ?>,
                                    Username: <?= $key['created_username'] ?>,
                                    Mobile: <?= $key['created_mobile'] ?>,
                                     Date: <?= $key['createdate'] ?>,)<br>
                                    <?php endif; ?>

                                    <?php if (!empty($key['updated_name'])): ?>
                                    Last Edited by (User: <?= $key['updated_name'] ?>,
                                    Username: <?= $key['updated_username'] ?>,
                                    Mobile: <?= $key['updated_mobile'] ?>,
                                    Date: <?= $key['lastupdated'] ?>) 
                                    <?php endif; ?>
                                     </div>
                                ">
                                                            <td class="details-control text-center" style="cursor:pointer;">
                                                        <?php echo $sn++; ?>
                                                        <i class="ri-add-circle-fill text-primary"></i>
                                                    </td>
                                                            <td><?= $first_name . " " . $last_name . " - (" . $emp_code . ")" ?>
                                                            </td>
                                                            <td>
                                                                <?= $obj->dateformatindia($key['promotion_date']) ?><br>
                                                            </td>
                                                            <td><?= $key['unit_name']; ?> </td>
                                                            <td><b><?= $key['department_name']; ?></b><br>
                                                                <small><?= $key['designation']; ?> </small>
                                                            </td>
                                                            <td><?= $key['promote_amt']; ?> </td>
                                                            <td><?= $key['basic_salary']; ?> </td>
                                                            <td><?= ucfirst($key['type']); ?> </td>
                                                            <td><?= $key['remark']; ?> </td>
                                                            <td>
                                                                <?php
                                                                        if ($key['effected_month'] != 0 && $key['effected_year'] != 0) {

                                                                            echo date("F", mktime(0, 0, 0, $key['effected_month'], 1))
                                                                                . '-' . $key['effected_year'];
                                                                        }
                                                                        ?>
                                                            </td>
                                                            <td>

                                                                <span
                                                                    class="<?= $key['status'] == 1 ? 'badge bg-success' : ($key['status'] == 2 ? 'badge bg-danger' : 'badge bg-warning text-dark') ?>">
                                                                    <?= $key['status'] == 1 ? 'Approved' : ($key['status'] == 2 ? 'Rejected' : 'Pending') ?>
                                                                </span>

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
                        </div>
                        <?php } ?>

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

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

        });
 $(document).ready(function() {
        var table = $('#promotion_table').DataTable();
        $('#promotion_table tbody').on('click', 'td.details-control', function() {
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
   
        function get_url(emp_id) {
            location = '<?= $pagename ?>?emp_id=' + emp_id;
        }
    </script>
</body>

</html>