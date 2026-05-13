<?php
include("appsession.php");
$pagename = 'manage-gatepass.php';
$title = 'Manage Gate Pass';
$tblname = 'create_gatepass';
$tblpkey = 'gatepass_id';
$crit = " where 1=1 ";

if (isset($_GET['gp_date']) && $_GET['gp_date'] != '') {
    $gp_date = $_GET['gp_date'];
} else {
    $gp_date = date('Y-m-d');
}

$crit .= " and gp_date = '$gp_date'";

// final query
$gatepasses = $obj->executequery("SELECT * FROM $tblname $crit ORDER BY $tblpkey ASC");
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
            <form method="get">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-lg mb-3 list-card">
                            <div class="row">
                                <div class="col-7">
                                    <input type="date" name="gp_date" id="gp_date" class="form-control" value="<?php echo $gp_date; ?>">
                                </div>
                                <div class="col-5">
                                    <button type="submit" class="btn-green btn w-100">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <?php if (!empty($gatepasses)) : ?>
                            <?php foreach ($gatepasses as $gp) : ?>
                                <div class="card border-0 shadow-lg mb-3 list-card" id="gpCard<?= $gp[$tblpkey] ?>">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td>Gate Pass No.</td>
                                            <td>: &nbsp; <?= htmlspecialchars($gp['gatepass_no']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Date & Time</td>
                                            <td>: &nbsp; <?= date('d-m-Y', strtotime($gp['gp_date'])) ?> || <?= date('h:ia', strtotime($gp['gp_time'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Vehicle No.</td>
                                            <td>: &nbsp; <?= htmlspecialchars($gp['vehicle_no']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Qty</td>
                                            <td>: &nbsp; <?= htmlspecialchars($gp['quantity']) ?></td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div class="d-flex w-100 gap-1">
                                        <a href="create-gatepass.php?<?= $tblpkey ?>=<?= $gp[$tblpkey] ?>" class="btn btn-sm w-100">Edit</a>
                                        <button type="button" class="btn-red btn-sm w-100" onclick="deleteGatePass(<?= $gp[$tblpkey] ?>)">Delete</button>
                                        <a href="print-gatepass.php?<?= $tblpkey ?>=<?= $gp[$tblpkey] ?>" class="btn-green btn-sm w-100">View</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="alert alert-info">No gate passes found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- js script files -->
    <?php include("inc/js-file.php"); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteGatePass(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this gate pass?",
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
                        success: function(response) {
                            Swal.fire('Deleted!', 'Gate pass has been deleted.', 'success').then(() => {
                                // Remove the deleted card from DOM
                                document.getElementById('gpCard' + id).remove();
                            });
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
                        }
                    });
                }
            });
        }

        function formatVehicle(input) {
            let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (val.length > 2) val = val.slice(0, 2) + '-' + val.slice(2);
            if (val.length > 5) val = val.slice(0, 5) + val.slice(5);
            input.value = val;
        }
    </script>
</body>


</html>