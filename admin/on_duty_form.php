
<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10,
]);

$tblname = "on_duty_master";
$tblpkey = "on_duty_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$fields = [
    'emp_id',
    'application_date',
    'on_duty_type',
    'total_day',
    'doc_file',
    'unit_id',
    'anniversary_date',
    'createdby',
    'ipaddress',
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

    $emp_data = $obj->select_record("employee_master", ['emp_id' => $emp_id]);

    $first_name = $emp_data['first_name'] ?? '';
    $emp_code = $emp_data['emp_code'] ?? '';
    $designation_id = $emp_data['designation_id'] ?? '';
    $department_id = $emp_data['department_id'] ?? '';
    $unit_id = $emp_data['unit_id'] ?? '';

    $designation_name = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");
    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");

    $unit_data = $obj->select_record("unit_master", ['unit_id' => $unit_id]);
    $unit_logo = $unit_data['logo_image'] ?? '';
    $unit_name = $unit_data['unit_name'] ?? '';
    $head_name = $unit_data['unithead'] ?? '';
    $unit_mobile = $unit_data['mobile'] ?? '';
    $unit_email = $unit_data['email_id'] ?? '';
    $unit_address = $unit_data['address'] ?? '';
    $gst = $unit_data['gstin_no'] ?? '';

    $result_details = $obj->executequery("SELECT * FROM on_duty_details WHERE on_duty_id = '$keyvalue' ORDER BY date ASC");

    $first_date = '';
    $last_date = '';

    if (!empty($result_details)) {

        $first_date = $obj->dateformatindia($result_details[0]['date']);

        $last_index = count($result_details) - 1;
        $last_date = $obj->dateformatindia($result_details[$last_index]['date']);
    }
}

$unit_imgpath = 'uploaded/unit_logo/';
$logo_html = '';

if (!empty($unit_logo) && file_exists($unit_imgpath . $unit_logo)) {
    $logo_html = '<img src="' . __DIR__ . '/' . $unit_imgpath . $unit_logo . '" height="50">';
}

$html = '

<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header-table {
            width: 100%;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
        }

        .title {
            font-size: 12pt;
            font-weight: bold;
            margin-top:12px;
        }

        .info-table {
            width: 100%;
            margin-top: 10px;
        }

        .info-table td {
            padding: 3px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .main-table th {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-weight: bold;
        }

        .main-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .footer {
            margin-top: 20px;
        }

        .sign-table {
            width: 100%;
            margin-top: 30px;
        }

        .sign-table td {
            width: 50%;
        }

        .ack-table {
            width: 100%;
            margin-top: 20px;
        }

        .page-footer {
            font-size: 9pt;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="10%">
                 ' . $logo_html . '
            </td>

            <td width="90%" align="center">
                <h2 class="company-name">' . $unit_name . '</h2>
                <p style="padding: 12px 0px;">Site Name -' . $head_name . ' ' . $unit_mobile . ' ' . $unit_email . ' ' . $gst . '</p>
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="center">
                <h2 class="title">ON DUTY APPLICATION FORM</h2>
            </td>
        </tr>

    </table>

    <br>

    <table width="100%">
        <tr>
            <td width="70%"></td>
            <td width="30%" align="right"><b>Date :</b> ' . $obj->dateformatindia($application_date) . '</td>
        </tr>
    </table>

    <!-- EMPLOYEE INFO -->
    <table class="info-table">

        <tr>
            <td width="15%"><b>Name</b></td>
            <td width="35%">: ' . $first_name . '</td>

            <td width="20%"><b>Employee Code</b></td>
            <td width="30%">: ' . $emp_code . '</td>
        </tr>

        <tr>
            <td><b>Department</b></td>
            <td>: ' . $department_name . '</td>

            <td><b>Designation</b></td>
            <td>:   ' . $designation_name . '</td>
        </tr>

        <tr>
            <td><b>On Duty Type</b></td>
            <td>: ' . $on_duty_type . '</td>
            <td></td>
            <td></td>
        </tr>

    </table>

    <br>

    Dear Sir,<br>
    This is to inform you that my on duty details :

    <br><br>

    <!-- MAIN TABLE -->
    <table class="main-table">

        <tr>
            <th width="5%">SNo.</th>
            <th width="12%">Date</th>
            <th width="18%">In/Out Time</th>
            <th width="15%">Place With Emp.</th>
            <th width="10%">Status</th>
            <th width="15%">Updated By</th>          
            <th width="13%">Remark</th>
        </tr>

        ';

$i = 1;

foreach ($result_details as $row_details) {
    $date = $obj->dateformatindia($row_details['date']);
    $intime = date("h:i A", strtotime($row_details['intime']));
    $outtime = date("h:i A", strtotime($row_details['outtime']));
    $place = $row_details['place'];
    $with_emp = $row_details['with_employee'];
    $remark = $row_details['remark'];
    $status_val = $row_details['status'];
    $appr_remark = $row_details['appr_remark'];
    $approved_by = $obj->getvalfield("user", "username", "userid='$row_details[updatedby]'");
    // Status text
    if ($status_val == 0) {
        $status = 'Pending';
    } elseif ($status_val == 1) {
        $status = 'Approved';
    } elseif ($status_val == 2) {
        $status = 'Rejected';
    } else {
        $status = 'Unknown';
    }

    $html .= '
    <tr>
        <td>' . $i++ . '</td>
        <td>' . $date . '</td>
        <td>' . $intime . ' - ' . $outtime . '</td>
        <td>' . $place . ' (' . $with_emp . ')</td>
        <td>' . $status . '</td>
        <td>' . $approved_by . '</td>
       
        <td>' . $remark . '</td>
    </tr>
    ';
}
$html .= '
    </table>

<br>

<b>Total Days : ' . $total_day . '</b>
<br>

You are requested to kindly approve the above deviation.

<br>

Thanking you

<br><br>

<table class="sign-table">
<tr>
<td>Signature of Employee</td>
<td align="right">Signature of HOD</td>
</tr>
</table>

<br>

<hr>

<!-- ACKNOWLEDGEMENT -->

<b>Acknowledgement</b>

<br><br>

Received OD form From Emp. No. ' . $emp_code . ' from date ' . $first_date . ' to date ' . $last_date . '

<br><br>

<table class="ack-table">
<tr>
<td width="50%">Date</td>
<td width="50%" align="right">Signature</td>
</tr>
</table>

<br><br>

<div class="page-footer">
 
</div>

</body>
</html>

';
$mpdf->SetFooter('Print Date : {DATE j-m-Y h:i A} | Page {PAGENO} of {nbpg}');

$mpdf->WriteHTML($html);

$mpdf->Output("On_Duty_Form.pdf", "I");
