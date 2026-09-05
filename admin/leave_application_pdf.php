<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 10,
    'margin_bottom' => 5,
    'margin_left' => 7,
    'margin_right' => 7,
]);
$pageHeight = 297;
$headerHeight = 40;
$footerHeight = 40;
$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$fields = [
    'type',
    'emp_id',
    'application_date',
    'on_duty_type',
    'total_day',
    'doc_file',
    'contact_no',
    'leave_address',
    'reason',
    'substitute_emp_id',
    'createdby',
    'createdate',
    'lastupdated',
    'unit_id',
    'sessionid'
];

if (isset($_GET[$tblpkey])) {

    $where = array($tblpkey => $keyvalue);
    $row = $obj->select_record($tblname, $where);
    foreach ($fields as $field) {
        $$field = isset($row[$field]) ? $row[$field] : '';
    }
    $emp_data = $obj->select_record("employee_master", ["emp_id" => $emp_id]);

    $first_name =   $emp_data["first_name"] ?? '';
    $emp_code =   $emp_data["emp_code"] ?? '';
    $date_of_joining =   $emp_data["date_of_joining"] ?? '';
    $salary =   $emp_data["basic_salary"] ?? '';
    $designation_id =   $emp_data["designation_id"] ?? '';
    $department_id =   $emp_data["department_id"] ?? '';
    $sub_first_name =   $obj->getvalfield("employee_master", "first_name", "emp_id='$substitute_emp_id'");
    $designation_name = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");
    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");
    $unit_data = $obj->select_record("unit_master", ["unit_id" => $unit_id]);

    $unit_logo = $unit_data["logo_image"] ?? "";
    $unit_name = $unit_data["unit_name"] ?? "";
    $head_name = $unit_data["unithead"] ?? "";
    $unit_mobile = $unit_data["mobile"] ?? "";
    $unit_email = $unit_data["email_id"] ?? "";
    $unit_address = $unit_data["address"] ?? "";
    $watermark = $unit_data["watermark"] ?? "";
    $gst = $unit_data["gstin_no"] ?? "";
    $currentYear  = date('Y', strtotime($application_date));
    $currentMonth = date('m', strtotime($application_date));

    $total_earning_leave = (float)$obj->getEarningLeave($emp_id, $sessionid, $currentMonth, $currentYear);

    $extra_off = $obj->getExtraOffBalance($emp_id, $currentMonth, $currentYear);
    $extra_off_balance = (float)($extra_off['balance'] ?? 0);

    $getEmpCoffLeave = (float)$obj->getEmpCoffLeave($emp_id, $sessionid, $currentMonth, $currentYear);

    $total_balance = $total_earning_leave + $extra_off_balance + $getEmpCoffLeave;
}
$total_days  = $obj->getvalfield(
    "leave_apply_detail",
    "SUM(
        CASE 
            WHEN leave_day = 'FD' THEN 1
            WHEN leave_day = 'SL' THEN 1
            WHEN leave_day IN ('FHD','SHD') THEN 0.5
            ELSE 0
        END
    )",
    "on_duty_id='$keyvalue' and unit_id='$unitid'"
);
$unit_imgpath = '/management/uploaded/emp_documents/';
$logoPath = dirname(__DIR__) . $unit_imgpath . $unit_logo;

$logo_html = '';
// $mpdf->SetWatermarkImage(__DIR__ . $imgpath1.$watermark, 0.2, "", [65, 40]);
// $mpdf->showWatermarkImage = true;

$watermarkPath = dirname(__DIR__) . '/management/uploaded/emp_documents/' . $watermark;


$mpdf->SetWatermarkImage($watermarkPath, 0.2, "", [55, 70]);
$mpdf->showWatermarkImage = true;

ob_start();
?>
<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    .header {
        text-align: center;
        font-weight: bold;
    }

    .title {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        margin-top: 25px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table th,
    .table td {
        border: 1px solid #000;
        padding: 5px;
        font-size: 11px;
    }

    .no-border td {
        border: none;
    }

    .section {
        margin-top: 10px;
    }

    .sign {
        margin-top: 40px;
    }

    .sign-row {
        width: 100%;
        overflow: hidden;
        margin-bottom: 40px;
    }

    .sign-col {
        width: 48%;
        float: left;
        font-weight: bold;
    }

    .sign-col.right {
        float: right;
        text-align: right;
    }
</style>

<div class="header">
    <table width="100%" cellpadding="0" cellspacing="0" style="border:none;">
    <tr>
        <!-- Logo -->
        <td width="20%" style="border:none; text-align:left; vertical-align:middle;">
            <?php if (!empty($unit_logo) && file_exists($logoPath)) { ?>
                <img src="<?= $logoPath ?>" style="height:80px;">
            <?php } ?>
        </td>

        <!-- Company Details -->
        <td width="60%" style="border:none; text-align:center; vertical-align:middle;">
            <h2 style="margin:0;"><?= $unit_name ?></h2>

            <div style="margin-top:5px;">
                <strong>Head Name:</strong> <?= $head_name ?>
            </div>

            <div style="margin-top:3px;">
                <strong>Mobile:</strong> <?= $unit_mobile ?>
            </div>

            <div style="margin-top:3px;">
                <strong>Email:</strong> <?= $unit_email ?>
            </div>

            <div style="margin-top:3px;">
                <?= $unit_address ?>
            </div>
        </td>

        <!-- Empty column for balance -->
        <td width="20%" style="border:none;"></td>
    </tr>
</table>
</div>

<div class="title">LEAVE APPLICATION FORM</div>

<table class="table no-border">
    <tr>
        <td><b>Name:</b> <?= $first_name ?></td>
        <td><b>Date:</b> <?= $obj->dateformatindia($application_date) ?></td>
    </tr>
    <tr>
        <td><b>Joining Date:</b> <?= $date_of_joining ?></td>
        <td><b>Department:</b> <?= $department_name ?></td>
    </tr>
    <tr>
        <td><b>Designation:</b> <?= $designation_name ?></td>
        <td><b>Substitute Employee:</b> <?= $sub_first_name ?></td>
    </tr>
    <tr>

    </tr>
</table>

<div class="section">
    <b>Dear Sir/Ma'am,</b><br>
    This is to inform you that my leave details:
</div>

<table class="table">
    <tr>
        <th>SNo</th>
        <th>Date</th>
        <th>Day</th>
        <th>Leave Type</th>
        <th>Leave Day</th>
        <th>Final Status</th>
        <th>Approve By</th>
        <th>Approve Date</th>
    </tr>

    <?php $i = 1;
    $leave_details = $obj->executequery("select * from leave_apply_detail where on_duty_id='$keyvalue' and unit_id='$unitid'");
    foreach ($leave_details as $row) {
        $approve_by = $obj->getvalfield("user", "username", "userid='$row[approve_by]'");
    ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $obj->dateformatindia($row['date']) ?></td>
            <td><?= date('l', strtotime($row['date'])) ?></td>
            <td><?= $row['leave_type'] ?></td>
            <td><?= $row['leave_day'] ?></td>
            <td> <?php
                    if ($row['status'] == 0) {
                        echo 'Pending';
                    } elseif ($row['status'] == 1) {
                        echo 'Approved';
                    } elseif ($row['status'] == 2) {
                        echo 'Rejected';
                    }
                    ?></td>
            <td><?= $approve_by ?></td>
            <td><?= $obj->dateformatindia($row['approve_date']) ?></td>
        </tr>
    <?php } ?>
</table>

<table class="table no-border">
    <tr>
        <td><b>Bal Leave As Per (<?= $application_date ?>):</b> <?= $total_balance  ?>
        </td>
        <td><b>Contact No:</b> <?= $contact_no ?></td>
    </tr>
    <tr>
        <td><b>Address:</b> <?= $leave_address ?></td>
        <td> </td>
    </tr>
</table>

<div class="section">
    <b>Reason:</b> <?= $reason ?>
</div>

<div class="section">
    <b> You are requested to kindly approve the above deviation.<br>
        Thanking you</b>
</div>

<div class="sign">

    <div class="sign-row">
        <div class="sign-col">Sign of Employee</div>
        <div class="sign-col right">Sign of Substitute Employee</div>
    </div>

    <div class="sign-row">
        <div class="sign-col">Sign of Dept. Head</div>
        <div class="sign-col right">Sign of HR</div>
    </div>

    <div class="sign-row">
        <div class="sign-col">Sign of PM</div>
        <div class="sign-col right">Sign of Authorized</div>
    </div>

</div>
<?php
$html = ob_get_clean();
// print_r($html);
// die;

$mpdf->WriteHTML($html);
$mpdf->Output(); ?>