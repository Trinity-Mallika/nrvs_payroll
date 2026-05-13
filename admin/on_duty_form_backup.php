
<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10,
]);

$logo = "uploaded/unit_logo/logo.png"; // change path

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
            <td width="20%">
                <img src="' . $logo . '" height="50">
            </td>

            <td width="80%" align="center">
                <div class="company-name">AGRAWAL INFRABUILD PRIVATE LIMITED</div>
                <div>Site Name - CHANDIKHOLE-PARADIP (PKG-1) NH-53</div>
                <div class="title">ON DUTY APPLICATION FORM</div>
            </td>
        </tr>
    </table>

    <br>

    <table width="100%">
        <tr>
            <td width="70%"></td>
            <td width="30%" align="right"><b>Date :</b> 04-Jun-2025</td>
        </tr>
    </table>

    <!-- EMPLOYEE INFO -->
    <table class="info-table">

        <tr>
            <td width="15%"><b>Name</b></td>
            <td width="35%">: VIKAS</td>

            <td width="20%"><b>Employee Code</b></td>
            <td width="30%">: 8454</td>
        </tr>

        <tr>
            <td><b>Department</b></td>
            <td>: PEM</td>

            <td><b>Designation</b></td>
            <td>: DRIVER (HMV)</td>
        </tr>

        <tr>
            <td><b>On Duty Type</b></td>
            <td>: On Duty</td>
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
            <th width="15%">Approve By</th>
            <th width="12%">Approve Date</th>
            <th width="13%">Remark</th>
        </tr>

        ';

// Example loop (replace with database data)
for ($i = 1; $i <= 31; $i++) {

    $status = ($i == 28) ? 'Rejected' : 'Approved';

    $html .= '
<tr>
<td>' . $i . '</td>
<td>' . date("d-M-Y", strtotime("2025-05-$i")) . '</td>
<td>08:00 AM-08:00 PM</td>
<td></td>
<td>' . $status . '</td>
<td>SMRUTI RANJAN DAS</td>
<td>05-Jun-2025<br>13:34:53</td>
<td>On Duty To Roxy Site</td>
</tr>
';
}

$html .= '
</table>

<br>

<b>Total Days : 31.00</b>
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

Received OD form From Emp. No. 8454 from date 01-May-2025 to date 31-May-2025

<br><br>

<table class="ack-table">
<tr>
<td width="50%">Date</td>
<td width="50%" align="right">Signature</td>
</tr>
</table>

<br><br>

<div class="page-footer">
Print Date : ' . date("d-M-y h:i:s A") . ' &nbsp;&nbsp; Page No. {PAGENO} of {nbpg}
</div>

</body>
</html>

';
$mpdf->SetFooter('Print Date : {DATE j-m-Y h:i A} | Page {PAGENO} of {nbpg}');

$mpdf->WriteHTML($html);

$mpdf->Output("On_Duty_Form.pdf", "I");
