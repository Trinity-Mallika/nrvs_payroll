<?php include("../adminsession.php");
$pagename = "emp_separation_report.php";
$title = "Employee Separation Report";
$tblname = "employee_exit";
$tblpkey = "exit_id";
$module = "Employee Separation Report";
$submodule = "Employee Separation Report List";
$btn_name = "Search";
$new_emp_code = $obj->getcode("employee_master", "emp_code",  "1='1'");
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = ' and 1=1';
if (isset($_GET['month'])) {
    $month = $obj->test_input($_GET['month']);
    if ($month != '') {
        $crit .= " and t.month='$month'";
    }
} else {
    $month = "";
};

if (isset($_GET['year'])) {
    $year = $obj->test_input($_GET['year']);
    if ($year != '') {
        $crit .= " and t.year='$year'";
    }
} else {
    $year = "";
};
if (isset($_GET['emp_id'])) {
    $emp_id = $obj->test_input($_GET['emp_id']);
    if ($emp_id != '') {
        $crit .= " and t.emp_id='$emp_id'";
    }
} else {
    $emp_id = "";
};

$fromdate = $_GET['fromdate'] ?? '';
$todate = $_GET['todate'] ?? '';

if ($fromdate != '' && $todate != '') {
    $crit .= " AND t.resignation_date BETWEEN '$fromdate' AND '$todate'";
} elseif ($fromdate != '') {
    $crit .= " AND t.resignation_date >= '$fromdate'";
} elseif ($todate != '') {
    $crit .= " AND t.resignation_date <= '$todate'";
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
                    <?php if (!isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"> <?= $module; ?> <a href="emp_separation.php"
                                                        class="float-end btn btn-primary btn-sm">Add New</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="get">
                                        <div class="row">
                                            <div class="col-lg-3 mb-3">
                                                <label for="emp_id" class="form-label">Employee Name<span
                                                        class="text-danger fw-bold"> </span></label>
                                                <select class="form-select form-select-sm chosen-select" name="emp_id"
                                                    id="emp_id">
                                                    <option value="">All</option>
                                                    <?php
                                                    //$res = $obj->executequery("Select * from employee_master where unit_id='$unitid' order by first_name asc");
                                                    $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' ORDER BY first_name ASC");
                                                    foreach ($res as $key) { ?>
                                                        <option value="<?= $key['emp_id']; ?>">
                                                            <?= $key['emp_code']; ?>-<?= ucfirst($key['first_name'] ?? ''); ?>
                                                            <?= ucfirst($key['last_name'] ?? ''); ?></option>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    document.getElementById('emp_id').value =
                                                        '<?= $emp_id; ?>';
                                                </script>
                                            </div>

                                            <div class="col-lg-3 mb-2">
                                                <label for="" class="form-label">Resignation Date </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="date" class="form-control form-control-sm" name="fromdate"
                                                        id="fromdate" placeholder='dd-mm-yyyy'
                                                        value="<?php echo $fromdate; ?>">
                                                    <span class="input-group-text">To</span>
                                                    <input type="date" class="form-control form-control-sm" name="todate"
                                                        id="todate" placeholder='dd-mm-yyyy' value="<?php echo $todate; ?>">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 mt-4">
                                                <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn"
                                                    value="Search">
                                                <a href="<?php echo $pagename ?>"
                                                    class="btn btn-sm btn-danger add-btn">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($_GET['submit'])) { ?>
                        <div class="col-lg-12">
                            <div class="card" id="customerList">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <div>
                                                <h5 class="card-title mb-0"><?php echo $submodule; ?> <a href="emp_separation_list.php"
                                                        class="float-end btn btn-primary btn-sm">Search Again</a></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body auto-scroll-wrapper">
                                    <div class="table-responsive">
                                        <table id="buttons-datatables" class="display table table-sm table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Sr No.</th>
                                                    <th>Emp Code</th>
                                                    <th>Employee Name</th>
                                                    <th>Department</th>
                                                    <th>Designation</th>
                                                    <th>Basic Salary</th>
                                                    <th>Aadhar No</th>
                                                    <th>Mobile No</th>
                                                    <th>Exit Type</th>
                                                    <th>Joining Date</th>
                                                    <th>Resignation Date</th>
                                                    <th>Last Working Date</th>
                                                    <th>Total Working Days</th>
                                                    <th>Notice Period (Days)</th>
                                                    <th>Reason</th>
                                                    <th>Approved Status  </th>

                                                    <th>Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $slno = 1;
                                                // $res = $obj->executequery("SELECT * FROM $tblname where unit_id='$unitid' $crit ORDER BY $tblpkey desc ");

                                                $res = $obj->executequery("SELECT 
                                                        t.*, 
                                                        em.date_of_joining,
                                                        em.emp_code,
                                                        em.basic_salary,
                                                        em.first_name,
                                                        em.last_name,
                                                        dm.department_name,
                                                        em.mobile_no,
                                                        em.aadhar_no,
                                                        desi.designation,
                                                        cu.fullname as created_name,
                                                        cu.username as created_username,
                                                        cu.mobile as created_mobile,

                                                        uu.fullname as updated_name,
                                                        uu.username as updated_username,
                                                        uu.mobile as updated_mobile
                                                    FROM $tblname t
                                                    LEFT JOIN employee_master em ON em.emp_id = t.emp_id
                                                    LEFT JOIN department_master dm ON dm.department_id = em.department_id
                                                    LEFT JOIN designation_master desi ON desi.designation_id = em.designation_id
                                                    LEFT JOIN user cu ON t.createdby = cu.userid
                                                    LEFT JOIN user uu ON t.updatedby = uu.userid
                                                    WHERE t.unit_id = '$unitid' $crit
                                                    ORDER BY t.$tblpkey DESC
                                                ");

                                                foreach ($res as $row) {


                                                    if ($row['is_approved'] == 0) {
                                                        $statusText = 'Pending';
                                                        $badgeClass = 'bg-warning';
                                                    } elseif ($row['is_approved'] == 1) {
                                                        $statusText = 'Approved';
                                                        $badgeClass = 'bg-success';
                                                    } else {
                                                        $statusText = 'Rejected';
                                                        $badgeClass = 'bg-danger';
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
                                                            <?php echo $slno++; ?> <i
                                                                class="ri-add-circle-fill text-primary"></i></td>
                                                        <td><?= $row['emp_code']; ?> </td>
                                                        <td> <?= ucfirst($row['first_name'] ?? ''); ?>
                                                            <?= ucfirst($row['last_name'] ?? ''); ?></td>
                                                        <td><?php echo $row["department_name"]; ?></td>
                                                        <td><?php echo $row["designation"]; ?></td>
                                                        <td><?php echo $row["basic_salary"]; ?></td>
                                                        <td><?php echo $row["aadhar_no"]; ?></td>
                                                        <td><?php echo $row["mobile_no"]; ?></td>
                                                        <td><?php echo $row["exit_type"]; ?></td>
                                                        <td><?php echo $obj->dateformatindia($row["date_of_joining"]); ?></td>
                                                        <td><?= $obj->dateformatindia($row["resignation_date"]); ?></td>
                                                        <td><?= $obj->dateformatindia($row["last_working_date"]); ?></td>
                                                        <td>
                                                            <?php
                                                            echo $obj->getWorkingDuration($row["date_of_joining"], $row["last_working_date"]);
                                                            ?>
                                                        </td>
                                                        <td><?php echo $row["notice_period"]; ?></td>
                                                        <td><?php echo $row["reason_for_leaving"]; ?></td>
                                                        <td class="text-center">
                                                            <?php $chkapr = $obj->check_aprBtn($pagename, $loginid);
                                                            if ($chkapr == 1 && $row['is_approved'] == 0) { ?>
                                                                <a href="javascript:void(0)" title="Change Status" >
                                                                    <span class="badge <?= $badgeClass; ?> me-2">
                                                                        <?= $statusText; ?>
                                                                    </span>
                                                                </a>
                                                              
                                                            <?php } else { ?>
                                                                <span class="badge <?= $badgeClass; ?> me-2">
                                                                    <?= $statusText; ?>
                                                                </span>
                                                                <p><?= $row["reason_for_reject"]; ?></p>
                                                            <?php } ?>
                                                        </td>


                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                <?php  
                                                                if ($row['is_rejoined'] == 1) { ?>
                                                                    <div style="cursor:pointer;" onclick="openRejoinDetailsModal(
                                                                '<?= ucfirst($row['first_name'] . ' ' . $row['last_name']); ?>',
                                                                '<?= $row['rejoin_with_new_id']; ?>',
                                                                '<?= $row['prev_emp_code']; ?>',
                                                                '<?= $row['new_emp_code']; ?>',
                                                                '<?= $row['biometric_id']; ?>',
                                                                '<?= $row['emp_code']; ?>',
                                                                '<?= addslashes($row['rejoin_remark']); ?>'
                                                            )">
                                                                    <span class="badge bg-success mb-1 d-inline-block">
                                                                        Re-joined
                                                                    </span>

                                                                    </div>
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
                    <?php } ?>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!-- container-fluid -->
    </div>

    <!-- Rejoin Details Modal -->
    <div class="modal fade" id="rejoinDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title">Employee Rejoin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>


                <div class="modal-body">

                    <div class="mb-2">
                        <b>Employee :</b>
                        <span id="detail_emp_name"></span>
                    </div>

                    <div class="mb-2">
                        <b>Rejoin Type :</b>
                        <span id="detail_rejoin_type"></span>
                    </div>

                    <div class="mb-2">
                        <b>Previous Code :</b>
                        <span id="detail_prev_code"></span>
                    </div>

                    <div class="mb-2">
                        <b>New Code :</b>
                        <span id="detail_new_code"></span>
                    </div>

                    <div class="mb-2" id="bio_div">
                        <b>Biometric ID :</b>
                        <span id="detail_biometric"></span>
                    </div>

                    <div class="mb-2">
                        <b>Remark :</b><br>
                        <span id="detail_remark"></span>
                    </div>

                </div>

            </div>
        </div>
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

     

        function openRejoinDetailsModal(emp_name, rejoin_type, prev_code, new_code, biometric_id, emp_code, remark) {
            $('#detail_emp_name').text(emp_name);
            if (rejoin_type == 1) {
                $('#detail_rejoin_type').html(
                    '<span class="badge bg-info text-dark">With New Employee Code</span>'
                );
                $('#detail_prev_code').text(prev_code);
                $('#detail_new_code').text(new_code);
            } else {
                $('#detail_rejoin_type').html(
                    '<span class="badge bg-secondary">With Same Employee Code</span>'
                );
                // same code case
                $('#detail_prev_code').text(emp_code);
                $('#detail_new_code').text(emp_code);
            }
            // biometric
            if (biometric_id != '') {
                $('#bio_div').show();
                $('#detail_biometric').text(biometric_id);
            } else {
                $('#bio_div').hide();
            }
            // remark
            $('#detail_remark').text(remark);
            // open modal
            $('#rejoinDetailsModal').modal('show');
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
    </script>
</body>

</html>