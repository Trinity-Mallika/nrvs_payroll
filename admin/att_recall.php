<?php include("../adminsession.php");
$pagename = "att_recall.php";
$title = "Employee Attendence Re-call";
$tblname = "";
$tblpkey = "";
$module = "Employee Attendence Re-call";
$submodule = "Employee Attendence Re-call";
$btn_name = "Save";
$imgpath = "uploaded/emp_documents/";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$url = $obj->getvalfield("unit_master", "recall_api", "unit_id='$unitid'") ?? '';


$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date  = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

if (!empty($_GET['emp_ids'])) {

    $encrypted = $_GET['emp_ids'];

    $decrypted_ids = $obj->my_simple_crypt($encrypted, 'd');

    $emp_ids = array_filter(explode(',', $decrypted_ids), function ($id) {
        return intval($id) > 0; // remove invalid ids
    });
    $employeeIds = !empty($emp_ids) ? implode(',', $emp_ids) : '';
    // if (!empty($emp_ids)) {
    //     $crit .= " AND emp_id IN (" . implode(',', array_map('intval', $emp_ids)) . ")";
    // }
} else {
    $emp_ids = [];
    $employeeIds = '';
}

$responseData = [];


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

                    <div class="col-lg-12">
                        <div class="card card-height-100" id="customerList">
                            <div class="card-header border-bottom-dashed">
                                <div class="row g-4 align-items-center">
                                    <div class="col-sm">
                                        <div>
                                            <h5 class="card-title mb-0"> <?= $module; ?> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                <form method="get" id="getform">
                                    <div class="row">

                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Department Name<span class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="department_id" id="department_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from department_master where unit_id='$unitid' order by department_id asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['department_id']; ?>">
                                                        <?= $key['department_name']; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label for="department_id" class="form-label">Employee<span class="text-danger fw-bold"> </span></label>
                                            <select class="form-select form-select-sm chosen-select" name="emp_id" id="emp_id" multiple>
                                                <option value="">All</option>
                                                <?php
                                                // $from_date = '2026-04-04';
                                                // $to_date   = '2026-04-18';
                                                $selected_emp = $emp_ids ?? [];
                                                $res = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) ORDER BY first_name ASC");

                                                //                                                 $res = $obj->executequery("
                                                //     SELECT em.*
                                                //     FROM employee_master em
                                                //     WHERE em.unit_id = '$unitid'

                                                //     AND (em.resign_status != '1' 
                                                //         OR (em.resign_status = '1' AND em.last_working_date >= CURDATE())
                                                //     )
                                                //     AND NOT EXISTS (
                                                //         SELECT 1 
                                                //         FROM attendance_entry ae
                                                //         WHERE ae.emp_id = em.emp_id
                                                //         AND ae.attendance_date BETWEEN '$from_date' AND '$to_date'
                                                //     )

                                                //     ORDER BY em.emp_code ASC
                                                // ");
                                                foreach ($res as $row) {
                                                ?>
                                                    <option value="<?= $row['emp_code'] ?>"
                                                        <?= in_array($row['emp_code'], $selected_emp) ? 'selected' : '' ?>>
                                                        <?= $row['emp_code'] ?> - <?= $row['first_name'] ?>
                                                    </option>
                                                <?php } ?>
                                                <input type="hidden" name="emp_ids" id="emp_ids">
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">From Date</label>
                                            <input type="date" name="from_date" class="form-control form-control-sm"
                                                value="<?= $from_date ?>">
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">To Date</label>
                                            <input type="date" name="to_date" class="form-control form-control-sm"
                                                value="<?= $to_date ?>">
                                        </div>
                                        <div class="col-lg-3 mt-4">
                                            <button type="button" id="deleteBtn" class="btn btn-danger btn-sm">
                                                Delete Attendance
                                            </button>
                                            <input type="submit" name="submit_btn" class="btn btn-primary add-btn btn-sm" value="Re-call">
                                            <a href="<?php echo $pagename ?>" class="btn btn-danger add-btn btn-sm">Reset</a>
                                        </div>
                                    </div>
                                </form>


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
            $(".chosen-select").select2({
                width: '100%',
                search_contains: true
            });

            $('#show_field').select2({
                width: '100%'
            });
        });


        $("#deleteBtn").click(function() {

            let empIds = $('#emp_id').val();
            let from_date = $("input[name='from_date']").val();
            let to_date = $("input[name='to_date']").val();
            let department_id = $("#department_id").val();

            // if (!empIds || empIds.length === 0) {
            //     Swal.fire('Warning', 'Please select employee', 'warning');
            //     return;
            // }

            if (!from_date || !to_date) {
                Swal.fire('Warning', 'Please select date range', 'warning');
                return;
            }

            let emp_ids = empIds.join(',');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete attendance & logs!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete'
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Deleting...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: "delete_attendance.php",
                        type: "POST",
                        data: {
                            emp_ids: emp_ids,
                            department_id: department_id,
                            from_date: from_date,
                            to_date: to_date
                        },
                        success: function(res) {

                            let response = JSON.parse(res);

                            Swal.close();

                            if (response.status === "success") {
                                Swal.fire('Deleted!', response.msg, 'success');
                            } else {
                                Swal.fire('Error', response.msg, 'error');
                            }
                        }
                    });
                }
            });
        });


        $("#getform").on("submit", function(e) {
            e.preventDefault();

            let empIds = $('#emp_id').val();
            let from_date = $("input[name='from_date']").val();
            let to_date = $("input[name='to_date']").val();
            let department_id = $("#department_id").val();


            if (!empIds || empIds.length === 0 || empIds.includes("")) {
                emp_ids = "";
            } else {
                emp_ids = empIds.join(',');
            }

            // 🔥 Show Loader
            Swal.fire({
                title: 'Processing Attendance...',
                html: '<b id="swalProgress">0 / 0</b> records processed',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: "attendance_recall_api.php",
                type: "POST",
                data: {
                    from_date: from_date,
                    department_id: department_id,
                    to_date: to_date,
                    emp_ids: emp_ids,
                    url: "<?= $url ?>"
                },
                success: function(res) {
                    let response = JSON.parse(res);
                    // console.log('response', response);
                    let total_record = response?.data?.sqlsrvResponse?.totalRecords;

                    if (response.status === "exists") {
                        Swal.close();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Attendance Exists',
                            text: response.msg
                        });
                        return;
                    }

                    if (response.status === "success") {
                        startProgress(from_date, to_date, emp_ids, total_record, department_id);
                    } else {
                        Swal.close();
                        Swal.fire('Error', response.msg, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });
        });

        function startProgress(from_date, to_date, emp_ids, total_record, department_id) {
            if (total_record == 0) {
                Swal.close();
                Swal.fire({
                    icon: 'info',
                    title: 'No Records',
                    text: 'No records found to process',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            let interval = setInterval(function() {
                $.ajax({
                    url: "get_recall_progress.php",
                    type: "POST",
                    data: {
                        from_date: from_date,
                        department_id: department_id,
                        to_date: to_date,
                        emp_ids: emp_ids,
                        total: total_record
                    },
                    success: function(res) {
                        let data = JSON.parse(res);
                        // console.log('data', data);
                        document.getElementById("swalProgress").innerHTML =
                            data.processed + " / " + data.total;

                        if (data.processed >= data.total && data.total > 0) {
                            clearInterval(interval);
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Completed!',
                                html: `<b>${data.processed}</b> records processed successfully`,
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });

            }, 1000);
        }
    </script>


</body>

</html>