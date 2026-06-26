<?php include("../adminsession.php");
$pagename = "emp_promotion_list.php";
$title = "Employee Promotion Request List";
$tblname = "emp_promotion";
$tblpkey = "emp_promotion_id";
$module = "Employee Promotion Request List";
$submodule = "Employee Promotion Request List";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit="";

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and ep.department_id='$department_id'";
    }
} else {
    $department_id = "";
};

if (isset($_GET['designation_id'])) {
    $designation_id = $obj->test_input($_GET['designation_id']);
    if ($designation_id != '') {
        $crit .= " and ep.designation_id='$designation_id'";
    }
} else {
    $designation_id = "";
};

if (isset($_GET['status1'])) {
    $status1 = $obj->test_input($_GET['status1']);
    if ($status1 != '') {
        $crit .= " and ep.status='$status1'";
    }
} else {
    $status1 = "";
};

if (isset($_GET['status1'])) {
    $status1 = $obj->test_input($_GET['status1']);
    if ($status1 != '') {
        $crit .= " and ep.status='$status1'";
    }
} else {
    $status1 = "0";
};

if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " AND MONTH(ep.promotion_date) = '$month'";
    }
} else {
  $month = date('n'); 
}

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
      $crit .= " AND YEAR(ep.promotion_date) = '$year'";
    }
} else {
   $year  = date('Y');
};

 if (isset($_POST['department_iddd'])) {
    $department_id = $_REQUEST['department_iddd'];
    //  $depart_unit = $obj->getvalfield("department_master", "unit_id", "department_id='$department_id'");
    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($department_id != "" || $department_id > 0) {
        $res = $obj->executequery("Select * from designation_master where department_id='$department_id' order by designation asc");
        foreach ($res as $row) {
            $options .= "<option value='" . $row['designation_id'] . "' >" . $row['designation'] . "  </option>";
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
                     <?php
                    if (!isset($_GET['submit'])) {
                    ?>
                     <div class="col-lg-12">
                        <div class="card" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> 
                                                <a href="emp_promotion.php"
                                                    class="float-end btn btn-primary btn-sm">Add New</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department Name<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="department_id" id="department_id" onchange="get_designation(this.value);">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['department_id']; ?>">
                                                    <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('department_id').value =
                                                '<?= $department_id; ?>';
                                            </script>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="designation_id" class="form-label">Designation<span
                                                    class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="designation_id" id="designation_id">
                                                <option value="">All</option>
                                                <?php $res = $obj->executequery("Select * from designation_master where unit_id='$unitid' order by designation asc");
                                                    foreach ($res as $key) { ?>
                                                <option value="<?= $key['designation_id']; ?>">
                                                    <?= $key['designation']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            document.getElementById('designation_id').value =
                                                '<?= $designation_id; ?>';
                                            </script>
                                        </div>
                                        
                                        <div class="col-lg-3 col-12">
                                            <label for="month" class="form-label">Month<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="month" id="month">
                                                <option value="">Select</option>
                                                <?php
                                                $months = [
                                                    1 => 'January',
                                                    2 => 'February',
                                                    3 => 'March',
                                                    4 => 'April',
                                                    5 => 'May',
                                                    6 => 'June',
                                                    7 => 'July',
                                                    8 => 'August',
                                                    9 => 'September',
                                                    10 => 'October',
                                                    11 => 'November',
                                                    12 => 'December'
                                                ];
                                                foreach ($months as $value => $name) {
                                                    echo "<option value=\"$value\">$name</option>";
                                                }
                                                ?>
                                            </select>
                                            <script>
                                                document.getElementById('month').value = '<?php echo $month ?>'
                                            </script>
                                        </div>
                                        <!-- Year -->
                                        <div class="col-lg-3 col-12">
                                            <label for="year" class="form-label">Year<span class="text-danger fw-bold">*</span></label>
                                            <select class="form-select form-select-sm chosen-select" name="year" id="year">
                                                <option value="">Select</option>
                                                <?php
                                                $startYear = 2025;
                                                $endYear = 2100;
                                                for ($year1 = $startYear; $year1 <= $endYear; $year1++) {
                                                    echo "<option value=\"$year1\">$year1</option>";
                                                } ?>
                                            </select>
                                            <script>
                                                document.getElementById('year').value = '<?php echo $year ?>'
                                            </script>
                                        </div>
                            
                                     
                                        <div class="col-md-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select form-select-sm" name="status1"
                                                id="status1">
                                                <option value="0">Pending</option>
                                                <option value="1">Approve</option>
                                                <option value="2">Reject</option>
                                            </select>
                                            <script>
                                            document.getElementById('status1').value = '<?= $status1 ?>'
                                            </script>
                                        </div>
                                        
                                        <div class="col-lg-3 mt-4">
                                            <input type="submit" name="submit" class="btn btn-primary add-btn"
                                                value="Search">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn">Reset</a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
<?php } ?>
                    <?php
                    if (isset($_GET['submit'])) {
                    ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> <a href="emp_promotion_list.php"
                                                        class="float-end btn btn-primary btn-sm">Search Again</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="auto-scroll-wrapper">
                                        <div class="table-responsive">

                                            <table id="buttons-datatables" class="display table table-sm table-bordered"
                                                style="width:100%">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th>Sr No.</th>
                                                        <th>Actions</th>
                                                        <th>Status</th>
                                                        <th>Employee Code</th>
                                                        <th>Employee Name</th>                  
                                                        <th>Mobile No</th>
                                                        <th>Department</th>
                                                        <th>Designation</th>
                                                        <th>Promotion Date</th>
                                                        <th>Prev Salary</th>
                                                        <th>Promotion Amt</th>
                                                        <th>New Salary</th>
                                                        <th>Effected Month/Year</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $slno = 1;
                                                    $res = $obj->executequery("SELECT ep.*,em.emp_code,em.mobile_no,em.first_name,dm.department_name,dem.designation,cu.fullname as created_name,cu.username as created_username,cu.mobile as created_mobile, uu.fullname as updated_name, uu.username as updated_username,uu.mobile as updated_mobile FROM $tblname as ep LEFT JOIN employee_master em ON ep.emp_id=em.emp_id LEFT JOIN department_master dm ON ep.department_id=dm.department_id LEFT JOIN designation_master dem ON ep.designation_id=dem.designation_id LEFT JOIN user cu ON ep.createdby = cu.userid LEFT JOIN user uu ON ep.updatedby = uu.userid where ep.unit_id='$unitid' $crit"); 
                                                    foreach ($res as $row) {

                                                    ?>
                                                    <tr id="tr_<?= $row["emp_promotion_id"]; ?>" data-details="
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
                                                        
                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                
                                                                <?php  
                                                                    $chkdel = $obj->check_delBtn($pagename, $loginid);
                                                                    if ($chkdel == 1 && $row['status']==0 ) {  ?>
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="Delete">
                                                                    <a class="remove-item-btn" type="button"
                                                                        onclick="funDel('<?php echo $row[$tblpkey]; ?>');">
                                                                        <i
                                                                            class="ri-delete-bin-fill align-bottom text-danger"></i>
                                                                    </a>
                                                                </li>
                                                            
                                                                <?php } ?>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <span 
                                                                class="<?= $row['status'] == 1 ? 'badge bg-success' : ($row['status'] == 2 ? 'badge bg-danger' : 'badge bg-warning text-dark') ?>"
                                                                
                                                                <?= $row['status'] == 0 
                                                                    ? "style='cursor:pointer;' onclick=\"updatePromotionStatus('{$row['emp_promotion_id']}', '{$row['emp_id']}', '{$row['department_id']}', '{$row['designation_id']}', '{$row['basic_salary']}', '{$row['status']}', '{$row['effected_month']}', '{$row['effected_year']}')\"" 
                                                                    : "style='cursor:not-allowed;'" 
                                                                ?>
                                                                
                                                            >
                                                                <?= $row['status'] == 1 ? 'Approved' : ($row['status'] == 2 ? 'Rejected' : 'Pending') ?>
                                                            </span>
                                                        </td>
                                                        <td><?= $row["emp_code"]; ?></td>
                                                        <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                            <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                    
                                                        <td><?php echo $row['mobile_no']; ?></td>
                                                    
                                                        <td><?php echo $row['department_name']; ?></td>
                                                        <td><?php echo $row['designation']; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["promotion_date"]); ?>
                                                        </td>
                                                        
                                                        <td><?php echo $row['prev_salary']; ?></td>
                                                        <td><?php echo $row['promote_amt']; ?></td>
                                                        <td><?php echo $row['basic_salary']; ?></td>
                                                        <td>
                                                            <?= date("M Y", mktime(0, 0, 0, $row['effected_month'], 1, $row['effected_year'])) ?>
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
                     <?php } ?>
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
                data: 'id=' + id + '&tblname=' + tblname + '&tblpkey=' + tblpkey + '&submodule=' + submodule + '&pagename=' + pagename,
                dataType: 'html',
                success: function(data) {
                    $("#tr_" + id).hide();
                    // alert(data);
                    // location.reload();
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
    function updatePromotionStatus(promotion_id, emp_id, department_id, designation_id, basic_salary, status,effected_month,effected_year) {

        // already processed check
        if (status == 1) {
            Swal.fire('Info', 'Already Approved', 'info');
            return;
        }
        if (status == 2) {
            Swal.fire('Info', 'Already Rejected', 'info');
            return;
        }

        Swal.fire({
            title: 'Select Action',
            text: "Do you want to approve or reject this promotion?",
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Approve',
            denyButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            denyButtonColor: '#d33'
        }).then((result) => {

            let action = '';

            if (result.isConfirmed) {
                action = 'approve';
            } else if (result.isDenied) {
                action = 'reject';
            } else {
                return;
            }

            $.ajax({
                url: 'approve_promotion.php',
                type: 'POST',
                data: {
                    promotion_idd: promotion_id,
                    emp_id: emp_id,
                    department_id: department_id,
                    designation_id: designation_id,
                    basic_salary: basic_salary,
                    effected_month: effected_month,
                    effected_year: effected_year, 
                    action: action
                },
                success: function(res) { 
                    if(res.trim() == 'success'){
                        Swal.fire('Success!', 'Action completed successfully.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Server error.', 'error');
                }
            });

        });
    }

    function get_designation(department_id, designation_id = 0) {
            console.log(department_id);
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    department_iddd: department_id,
                },
                success: function(data) {

                    $('#designation_id').html(data).trigger("change.select2");
                }
            });
        }
    </script>
</body>

</html>