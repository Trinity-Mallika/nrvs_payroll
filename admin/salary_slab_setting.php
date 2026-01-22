<?php include("../adminsession.php");
$pagename = "salary_slab_setting.php";
$title = "Salary Slab Master";
$tblname = "salary_slab_master";
$tblpkey = "salary_slab_id";
$module = "Salary Slab Master";
$submodule = "Salary Slab Master List";
$btn_name = "Save";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {

    $slab_id  = $obj->test_input($_POST['slab_id']);

    $basic_percent  = $obj->test_input($_POST['basic_percent']);
    $hra_percent  = $obj->test_input($_POST['hra_percent']);
    $medical_allow  = $obj->test_input($_POST['medical_allow']);
    $conve_allow  = $obj->test_input($_POST['conve_allow']);
    $pf_per  = $obj->test_input($_POST['pf_per']);
    $esic_per  = $obj->test_input($_POST['esic_per']);
    $pf_emp_per  = $obj->test_input($_POST['pf_emp_per']);
    $esic_emp_per  = $obj->test_input($_POST['esic_emp_per']);


    $form_data = array(
        "slab_id" => $slab_id,
        "basic_percent"    => $basic_percent,
        "hra_percent"      => $hra_percent,
        "medical_allow"    => $medical_allow,
        "conve_allow"      => $conve_allow,
        "pf_per"           => $pf_per,
        "esic_per"         => $esic_per,
        "pf_emp_per"       => $pf_emp_per,
        "esic_emp_per"     => $esic_emp_per,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress
    );
    $count = $obj->getvalfield($tblname, "count(*)", "slab_id='$slab_id' and $tblpkey!='$keyvalue'");
    if ($count > 0) {
        $action = 4;
        $process = "duplicate";
    } else {
        if ($keyvalue == 0) {
            $form_data["createdate"] = $createdate;
            $obj->insert_record($tblname, $form_data);
            $action = 1;
            $process = "insert";
        } else {
            $form_data["lastupdated"] = $createdate;
            $where = array($tblpkey => $keyvalue);
            $obj->update_record($tblname, $where, $form_data);
            $action = 2;
            $process = "updated";
        }
    }
    echo "<script>location='$pagename?action=$action'</script>";
}


if (isset($_GET[$tblpkey])) {
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $slab_id =  $sqledit['slab_id'];
    $basic_percent =  $sqledit['basic_percent'];
    $hra_percent =  $sqledit['hra_percent'];
    $medical_allow =  $sqledit['medical_allow'];
    $conve_allow =  $sqledit['conve_allow'];
    $pf_per =  $sqledit['pf_per'];
    $esic_per =  $sqledit['esic_per'];
    $pf_emp_per =  $sqledit['pf_emp_per'];
    $esic_emp_per =  $sqledit['esic_emp_per'];
} else {
    $slab_id = "";
    $basic_percent = "";
    $hra_percent = "";
    $medical_allow = "";
    $conve_allow = "";
    $pf_per = "";
    $esic_per = "";
    $pf_emp_per = "";
    $esic_emp_per = "";
}
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
                                                <h5 class="card-title mb-0"> <?= $module; ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label for="slab_id" class="form-label">Salary Slab<span
                                                    class="text-danger fw-bold"></span></label>
                                            <select class="form-select form-select-sm chosen-select"
                                                name="slab_id" id="slab_id">
                                                <option value="">Select</option>
                                                <?php $res = $obj->executequery("Select * from salary_slab order by slab_id asc");
                                                foreach ($res as $key) { ?>
                                                    <option value="<?= $key['slab_id']; ?>">
                                                        <?= $key['heading']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                document.getElementById('slab_id').value = '<?= $slab_id; ?>';
                                            </script>
                                        </div>


                                        <div class="col-lg-4 mb-3">
                                            <label for="basic_percent" class="form-label">Basic Percent<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="basic_percent" name="basic_percent" class="form-control form-control-sm" placeholder="Enter basic percent" value="<?php echo $basic_percent ?>" autocomplete="off" onkeypress="numberOnly(event);" maxlength="3" onkeyup="check_percent(this.value)" />
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label for="hra_percent" class="form-label">HRA Percent<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="hra_percent" name="hra_percent" class="form-control form-control-sm" placeholder="Enter HRA Percent" value="<?php echo $hra_percent ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="medical_allow" class="form-label">Medical Allowance Amount<span class="text-danger fw-bold"></span></label>
                                            <input type="text" id="medical_allow" name="medical_allow" class="form-control form-control-sm" placeholder="Enter Medical Allowance Amount" value="<?php echo $medical_allow ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="conve_allow" class="form-label">Conveyance Allowance Amount<span class="text-danger fw-bold"></span></label>
                                            <input type="text" id="conve_allow" name="conve_allow" class="form-control form-control-sm" placeholder="Enter Conveyance Allowance Amount" value="<?php echo $conve_allow ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>

                                        <div class="col-lg-4 mb-3">
                                            <label for="pf_per" class="form-label">PF Percent<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="pf_per" name="pf_per" class="form-control form-control-sm" placeholder="Enter PF Percent" value="<?php echo $pf_per ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="esic_per" class="form-label">ESIC Percent<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="esic_per" name="esic_per" class="form-control form-control-sm" placeholder="Enter ESIC Percent" value="<?php echo $esic_per ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="pf_emp_per" class="form-label">PF Employer Share Percent<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="pf_emp_per" name="pf_emp_per" class="form-control form-control-sm" placeholder="Enter PF Employer Share Percent" value="<?php echo $pf_emp_per ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label for="esic_emp_per" class="form-label">ESIC Employer Share Percent<span class="text-danger fw-bold">*</span></label>
                                            <input type="text" id="esic_emp_per" name="esic_emp_per" class="form-control form-control-sm" placeholder="Enter ESIC Employer Share Percent" value="<?php echo $esic_emp_per ?>" autocomplete="off" onkeypress="numberOnly(event);" />
                                        </div>

                                        <div class="col-lg-4 mb-3 mt-2">
                                            <br>
                                            <input type="hidden" name="<?php echo $tblpkey ?>" value="<?php echo $keyvalue ?>">
                                            <input type="submit" name="submit" class="btn btn-sm btn-primary add-btn" value="<?php echo $btn_name ?> " onClick="return checkinputmaster('slab_id,basic_percent,hra_percent,pf_per,esic_per,pf_emp_per,esic_emp_per')">
                                            <a href=" <?php echo $pagename ?>" type="button" class="btn btn-sm btn-danger add-btn">Reset</a>
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
                                            <h5 class="card-title mb-0"><?php echo $submodule; ?> <span class="text-danger"></span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="buttons-datatables" class="display table table-sm table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Sr No.</th>
                                                <th>Salary Slab</th>
                                                <th>Basic Percent</th>
                                                <th>HRA Percent</th>
                                                <th>Medical Allowance Amount</th>
                                                <th>Conveyance Allowance Amount</th>
                                                <th>PF Percent</th>
                                                <th>ESIC Percent</th>
                                                <th>PF Employer Share Percent</th>
                                                <th>ESIC Employer Share Percent</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php
                                                $slno = 1;
                                                $res = $obj->executequery("select * from $tblname order by $tblpkey desc");
                                                foreach ($res as $row) {
                                                    $slab_name = $obj->getvalfield("salary_slab", "heading", "slab_id='$row[slab_id]'");
                                                ?>
                                                <tr>
                                                    <td><?php echo $slno++; ?></td>
                                                    <td><?php echo $slab_name; ?></td>
                                                    <td><?php echo $row['basic_percent']; ?></td>
                                                    <td><?php echo $row['hra_percent']; ?></td>
                                                    <td><?php echo $row['medical_allow']; ?></td>
                                                    <td><?php echo $row['conve_allow']; ?></td>
                                                    <td><?php echo $row['pf_per']; ?></td>
                                                    <td><?php echo $row['esic_per']; ?></td>
                                                    <td><?php echo $row['pf_emp_per']; ?></td>
                                                    <td><?php echo $row['esic_emp_per']; ?></td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item " data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="<?php echo $pagename ?>?<?php echo $tblpkey ?>=<?php echo $row[$tblpkey]; ?>" class="edit-item-btn"><i class="ri-pencil-fill align-bottom text-success"></i></a>
                                                            </li>
                                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                                <a class="remove-item-btn" type="button" onclick="funDel(<?php echo $row[$tblpkey]; ?>);">
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
        };

        function check_percent(val) {
            if (parseFloat(val) > 100) {
                Swal.fire({
                    title: "Invalid Value",
                    text: "Value can't be greater than 100%",
                    icon: "warning",
                    button: "OK"
                }).then(() => {
                    $('#basic_percent').val('');
                });
            }
        }
    </script>
</body>

</html>