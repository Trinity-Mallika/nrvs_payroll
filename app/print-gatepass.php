<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';
include("appsession.php");

$company = $obj->select_record("company_setting", ["company_id" => 1]);

$gatepass_id = $_GET['gatepass_id'];

$gp = $obj->select_record("create_gatepass", ["gatepass_id" => $gatepass_id]);

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => [80, 500],
    'margin_top' => 35,
    'margin_bottom' => 10,
    'margin_left' => 5,
    'margin_right' => 5,
]);

$mpdf->SetHTMLHeader('
<div style="text-align:center;">
    <h2 style="margin:0;">' . $company['company_name'] . '</h2>
    <p style="margin:0;font-size:12px;">' . $company['address'] . '</p>
    <p style="margin:0;font-size:12px;">
        Mobile : ' . $company['mobile'] . '
        ' . (!empty($company['contact_no']) ? ' | Landline : ' . $company['contact_no'] : '') . '
    </p>
    <p style="margin:0;font-size:12px;">
        ' . (!empty($company['email']) ? 'Email : ' . $company['email'] : '') . '
    </p>
</div>
');

ob_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            font-size: 12px;
        }

        td:first-child {
            width: 45%;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <hr>
    <table>
        <tr>
            <td>Gate Pass Number</td>
            <td>: <?= $gp['gatepass_no'] ?></td>
        </tr>
        <tr>
            <td>Date</td>
            <td>: <?= date('d-m-Y', strtotime($gp['gp_date'])) ?></td>
        </tr>
        <tr>
            <td>Time</td>
            <td>: <?= date('h:i A', strtotime($gp['gp_time'])) ?></td>
        </tr>
        <tr>
            <td>Vehicle Number</td>
            <td>: <?= $gp['vehicle_no'] ?></td>
        </tr>
        <tr>
            <td>Driver Name</td>
            <td>: <?= $gp['driver_name'] ?></td>
        </tr>
        <tr>
            <td>Mobile Number</td>
            <td>: <?= $gp['mobile'] ?></td>
        </tr>
        <tr>
            <td>ID Proof Number</td>
            <td>: <?= $gp['id_proof'] ?></td>
        </tr>
        <tr>
            <td>Cargo Details</td>
            <td>: <?= $gp['cargo_details'] ?></td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td>: <?= $gp['quantity'] ?></td>
        </tr>
        <tr>
            <td>Remark</td>
            <td>: <?= $gp['remark'] ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <hr>
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" width="120">
                <hr>
            </td>
        </tr>
    </table>

</body>

</html>

<?php
$html = ob_get_clean();

$mpdf->WriteHTML($html);
$mpdf->Output("gatepass.pdf", "I");
