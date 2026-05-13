<?php include("appsession.php");
$title = 'Attendence Details';
$pagename = 'attendance_details.php';
$emp_id = $_SESSION['emp_id'];
$currentYear = date('Y');
$currentMonth =  (int)date('m');
$emp_data =  $obj->select_record("employee_master", array("emp_id" => $emp_id));
$doj = $emp_data['date_of_joining'] ?? '';
$dateforas = date('Y-m-d', strtotime("$currentYear-$currentMonth")) ?? '';

if (isset($_GET['currentYear']) && isset($_GET['currentMonth'])) {
    $currentYear = $_GET['currentYear'];
    $currentMonth = $_GET['currentMonth'];
}

if (isset($_GET['prev'])) {
    $currentMonth--;
    if ($currentMonth < 1) {
        $currentMonth = 12;
        $currentYear--;
    }
}
if (isset($_GET['next'])) {
    $currentMonth++;
    if ($currentMonth > 12) {
        $currentMonth = 1;
        $currentYear++;
    }
}
if (isset($_GET['date'])) {
    $date_as_new = $_GET['date'];
} else {
    $date_as_new = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Attendance History</title>
    <?php include("inc/css-file.php"); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .header {
            padding: 15px;
            text-align: center;
            font-weight: 600;
        }

        /* MONTH SELECT */
        .month-box {
            background: white;
            margin: 10px;
            padding: 10px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
        }

        /* DAY CARD */
        .day-card {
            background: white;
            margin: 10px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* TOP BAR */
        .day-header {
            background: #065f46;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        /* ROW */
        .row-box {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .row-box div {
            font-size: 13px;
        }

        /* ICON */
        .icon {
            font-size: 18px;
            color: #065f46;
        }

        /* LABEL */
        .label {
            color: #6b7280;
            font-size: 12px;
        }
    </style>

</head>

<body>
    <?php include("inc/header.php"); ?>
    <!-- <div class="header">
        Attendance History
    </div> -->

    <!-- MONTH -->

    <!-- <div class="month-box">
        <span>&laquo;</span>
        <span>April 2026</span>
        <span>&raquo;</span>
    </div> -->
    <div id="show_att_data">

    </div>

    <!-- DAY 1 -->
    <!-- <div class="day-card">
        <div class="day-header">
            <span>Date: 10/Apr/2026</span>
            <span>Total Hours: 09:00</span>
        </div>

        <div class="row-box">
            <div>
                <div class="icon">⏰</div>
                <div>09:00</div>
                <div class="label">Check In</div>
            </div>

            <div>
                <div class="icon">⏰</div>
                <div>18:00</div>
                <div class="label">Check Out</div>
            </div>

            <div>
                <div class="icon">✔</div>
                <div>09:00</div>
                <div class="label">Total Hrs</div>
            </div>
        </div>
    </div> -->

    <!-- DAY 2 -->
    <!-- <div class="day-card">
        <div class="day-header">
            <span>Date: 11/Apr/2026</span>
            <span>Total Hours: 11:20</span>
        </div>

        <div class="row-box">
            <div>
                <div class="icon">⏰</div>
                <div>03:36</div>
                <div class="label">Check In</div>
            </div>

            <div>
                <div class="icon">⏰</div>
                <div>14:48</div>
                <div class="label">Check Out</div>
            </div>

            <div>
                <div class="icon">✔</div>
                <div>11:12</div>
                <div class="label">Total Hrs</div>
            </div>
        </div>

        <div class="row-box">
            <div>
                <div class="icon">⏰</div>
                <div>03:45</div>
                <div class="label">Check In</div>
            </div>

            <div>
                <div class="icon">⏰</div>
                <div>03:53</div>
                <div class="label">Check Out</div>
            </div>

            <div>
                <div class="icon">✔</div>
                <div>00:08</div>
                <div class="label">Total Hrs</div>
            </div>
        </div>
    </div> -->

    <?php include('inc/js-file.php') ?>

    <script>
        $(document).ready(function() {
            showatttype();
        });

        function showatttype() {
            var emp_id = '<?php echo $emp_id ?>';
            var currentMonth = '<?php echo $currentMonth ?>';
            var currentYear = '<?php echo $currentYear ?>';
            var date_as_new = '<?php echo $date_as_new ?>';
            if (emp_id > 0) {
                jQuery.ajax({
                    type: 'POST',
                    url: 'view_emp_att_details.php',
                    data: 'currentMonth=' + currentMonth + '&currentYear=' + currentYear + '&emp_id=' + emp_id + '&date_as_new=' + date_as_new,
                    dataType: 'html',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Loading attendance details...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(data) {

                        Swal.close();
                        document.getElementById('show_att_data').innerHTML = data;
                        //total(emp_id, currentMonth, currentYear);
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Unable to load attendance data', 'error');
                    }

                }); //ajax close
            }
        }

        // function changeMonth(type) {
        //     var currentMonth = parseInt('<?php echo $currentMonth ?>');
        //     var currentYear = parseInt('<?php echo $currentYear ?>');
        //     var date_as_new = '<?php echo $date_as_new ?>';
        //     var emp_id = '<?php echo $emp_id ?>';

        //     if (type === 'next') {
        //         currentMonth++;
        //         if (currentMonth > 12) {
        //             currentMonth = 1;
        //             currentYear++;
        //         }
        //     } else {
        //         currentMonth--;
        //         if (currentMonth < 1) {
        //             currentMonth = 12;
        //             currentYear--;
        //         }
        //     }

        //     // call same ajax
        //     jQuery.ajax({
        //         type: 'POST',
        //         url: 'view_emp_att_details.php',
        //         data: {
        //             currentMonth: currentMonth,
        //             currentYear: currentYear,
        //             emp_id: emp_id,
        //             date_as_new: date_as_new

        //         },
        //         success: function(data) {
        //             document.getElementById('show_att_data').innerHTML = data;
        //         }
        //     });
        // }
    </script>

</body>

</html>