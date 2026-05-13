<?php
include("appsession.php");

$pagename = 'add-user.php';
$title = 'Add User';
$tblname = 'user';
$tblpkey = 'userid';

$btn_name = "Save";
$keyvalue = 0;
$duplicate_error = false;
$fullname = $user_id = $password = $usertype = "";

// Check if editing
if (isset($_GET[$tblpkey])) {
    $keyvalue = $_GET[$tblpkey];
    $btn_name = "Update";
    $where = array($tblpkey => $keyvalue);
    $user_row = $obj->select_record($tblname, $where);
    if ($user_row) {
        $fullname = $user_row['fullname'];
        $user_id = $user_row['user_id'];
        $password = $user_row['password'];
        $usertype = $user_row['usertype'];
    }
}

if (isset($_POST['save'])) {
    $keyvalue = $_POST[$tblpkey] ?? 0;
    $fullname = $_POST['fullname'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    $count = $obj->getvalfield($tblname, "count(*)", "user_id='$user_id' AND $tblpkey <> $keyvalue");
    if ($count > 0) {
        $duplicate_error = true;
    } else {
        $form_data = [
            'fullname' => $fullname,
            'user_id' => $user_id,
            'password' => $password,
            'usertype' => $usertype,
            'ipaddress' => $ipaddress,
            'createdate' => $createdate
        ];

        if ($keyvalue == 0) {
            // Insert
            $obj->insert_record($tblname, $form_data);
            $action = 1;
        } else {
            // Update
            $where = [$tblpkey => $keyvalue];
            unset($form_data['createdate']);
            $form_data['lastupdated'] = $createdate;
            $obj->update_record($tblname, $where, $form_data);
            $action = 2;
        }
        echo "<script>location='$pagename?action=$action'</script>";
    }
}

// Fetch all users
$sql = $obj->executequery("SELECT * FROM $tblname ORDER BY $tblpkey DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Gate Pass</title>
    <!-- css links  files -->
    <?php include("inc/css-file.php"); ?>

</head>

<body class="dashboard">
    <section class="top-sec ">
        <?php include("inc/header.php"); ?>

        <div class="container">
            <div class="card border-0 shadow-lg mb-3">
                <form method="POST">
                    <div class="row">
                        <div class="col-lg-3 mb-2">
                            <label for="" class="form-label"> Full Name</label>
                            <input type="text" class="form-control shadow-sm" id="fullname" name="fullname" placeholder="Enter Full Name" value="<?= $fullname ?>">
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="" class="form-label"> User Id</label>
                            <input type="text" class="form-control shadow-sm" id="user_id" name="user_id" placeholder="Enter User Id " value="<?= $user_id ?>">
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="" class="form-label"> Password</label>
                            <input type="text" class="form-control shadow-sm" id="password" name="password" placeholder="Enter password" value="<?= $password ?>">
                        </div>
                        <div class="col-lg-3 mb-2">
                            <label for="" class="form-label shadow-sm"> Role</label>
                            <select name="usertype" class="form-control">
                                <option value="">--Select--</option>
                                <option value="Admin" <?= $usertype == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Operator" <?= $usertype == 'Operator' ? 'selected' : '' ?>>Operator</option>
                                <option value="Security Guard" <?= $usertype == 'Security Guard' ? 'selected' : '' ?>>Security Guard</option>
                            </select>
                        </div>
                        <div class="d-grid mt-3">
                            <input type="hidden" name="<?= $tblpkey ?>" value="<?= $keyvalue ?> ">
                            <button type="submit" name="save" class="btn btn-primary" onclick="return checkinputmaster('fullname,user_id,password')"><?= $btn_name ?></button>


                        </div>
                    </div>
                </form>

            </div>
            <div class="card border-0 shadow-lg mb-3">
                <h6 class="mb-3 text-blue">User List</h6>
                <div class="row" id="kaarigarTable">
                    <?php
                    $i = 1;
                    foreach ($sql as $key) {
                    ?>
                        <div class="col-lg-12 mb-2">
                            <div class="card card-body p-2">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <td width="30%">Full Name</td>
                                        <td>: &nbsp; <?php echo $key['fullname']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>User Id</td>
                                        <td>: &nbsp; <?php echo $key['user_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Password</td>
                                        <td>: &nbsp; <?php echo $key['password']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>User Type</td>
                                        <td>: &nbsp; <?php echo $key['usertype']; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr class="m-0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="<?= $pagename . "?" . $tblpkey . "=" . $key[$tblpkey] ?>" class="btn btn-green w-100 btn-sm">Edit</a>
                                        </td>
                                        <td> <button type="button" class="btn btn-red btn-sm w-100" onclick="funDel(<?= $key[$tblpkey] ?>)">Delete</button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <tr class="table-info">
                            <th>S.No.</th>
                            <th>Full Name</th>
                            <th>User Id</th>
                            <th>Password</th>
                            <th>User Type</th>
                            <th>Action</th>

                        </tr>
                        <tbody id="kaarigarTable">
                            <?php
                            $i = 1;
                            foreach ($sql as $key) {
                            ?>

                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $key['fullname']; ?></td>
                                    <td><?php echo $key['user_id']; ?></td>
                                    <td><?php echo $key['password']; ?></td>
                                    <td><?php echo $key['usertype']; ?></td>
                                    <td>
                                        <?php if ($key['usertype'] != 'Admin') { ?>
                                            <a href="<?= $pagename . "?" . $tblpkey . "=" . $key[$tblpkey] ?>" class="btn btn-success btn-sm">Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="funDel(<?= $key[$tblpkey] ?>)">Delete</button>
                                        <?php } else { ?>
                                            <span class="text-muted mt-2">No Action</span>
                                        <?php } ?>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div> -->
            </div>

        </div>
    </section>

    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>

    <?php if ($duplicate_error) { ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate User ID',
                    text: 'This User ID already exists!',
                    confirmButtonColor: '#d33'
                });
            });
        </script>
    <?php } ?>
    <?php if (isset($_GET['action'])) { ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                let action = "<?= $_GET['action'] ?>";

                if (action == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'User Added Successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                if (action == 2) {
                    Swal.fire({
                        icon: 'success',
                        title: 'User Updated Successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

            });
        </script>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
        function funDel(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    jQuery.ajax({
                        type: "POST",
                        url: "delete_master.php",
                        data: {
                            id: id,
                            tblname: '<?= $tblname ?>',
                            tblpkey: '<?= $tblpkey ?>'
                        },
                        success: function() {
                            Swal.fire('Deleted!', 'User has been deleted.', 'success').then(() => {
                                location = '<?= $pagename ?>';
                            });
                        }
                    });
                }
            });
        }

        function showLoader() {

            let btn = document.getElementById("saveBtn");

            // button disable
            btn.disabled = true;

            // loader text
            btn.innerHTML = 'Please wait...';

            return true;
        }
    </script>
</body>

</html>