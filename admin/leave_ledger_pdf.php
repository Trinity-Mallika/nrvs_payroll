<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10
]);

$pageHeight = 297;
$headerHeight = 40;
$footerHeight = 40;
$crit = '';
$unit_imgpath = __DIR__ . '/../management/uploaded/emp_documents/';
$unit = $obj->select_record("unit_master", ["unit_id" => $unitid]);
$is_all_leave_add = $unit['add_leave'] ?? 0;
$unit_logo        = $unit['logo_image'] ?? '';
$unit_name        = $unit['unit_name'] ?? '';
$head_name        = $unit['unithead'] ?? '';
$unit_mobile      = $unit['mobile'] ?? '';
$unit_email       = $unit['email_id'] ?? '';
$unit_address     = $unit['address'] ?? '';


if (isset($_GET['emp_idd'])) {
    $emp_ids = $obj->test_input($_GET['emp_idd']);
    if ($emp_ids != '') {
        $crit .= " and emp_id='$emp_ids'";
    }
} else {
    $emp_ids = '';
}

if (isset($_GET['department_id'])) {
    $department_id = $obj->test_input($_GET['department_id']);
    if ($department_id != '') {
        $crit .= " and department_id='$department_id'";
    }
} else {
    $department_id = '';
}

$year = (isset($_GET['year'])) ? $obj->test_input($_GET['year']) :  date('Y');
$leave_type = isset($_GET['leave_type']) ? $obj->test_input($_GET['leave_type']) : 'earning';



$from_date = isset($_GET['from_date']) ? $obj->test_input($_GET['from_date']) : date('Y-m-01');
$to_date   = isset($_GET['to_date']) ? $obj->test_input($_GET['to_date']) :  date('Y-m-d');



$empRes = $obj->executequery("SELECT * FROM employee_master WHERE unit_id = '$unitid' AND (resign_status != '1' OR (resign_status = '1' AND last_working_date >= CURDATE())) $crit ORDER BY first_name ASC");

ob_start();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            line-height: 1.5;
        }

        .empinfo {
            margin-top: 10px;
            margin-bottom: 8px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        thead th {
            background: #d9ead3;
            font-weight: bold;
        }

        tfoot th {
            background: #d9ead3;
        }

        .logo {
            width: 120px;
        }

        .ledger-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ledger-table th,
        .ledger-table td {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table width="100%" style="border:none;border-collapse:collapse;">
            <tr>
                <!-- Logo -->
                <td width="20%" style="border:0px;">
                    <?php if (!empty($unit_logo)) { ?>
                        <img src="<?php echo $unit_imgpath . $unit_logo; ?>" class="logo">
                    <?php } ?>
                </td>
                <!-- Company Details -->
                <td width="60%" align="center" style="border:0px;">
                    <div style="font-size:11px;font-weight:bold;">
                        Date From :
                        <?= date('d-M-Y', strtotime($from_date)); ?>
                        &nbsp;&nbsp;&nbsp;
                        To :
                        <?= date('d-M-Y', strtotime($to_date)); ?>
                    </div>

                    <div style="font-size:18px;font-weight:bold;margin-top:4px;">
                        <?= strtoupper($head_name); ?>
                    </div>

                    <div style="font-size:15px;font-weight:bold;">
                        <?= strtoupper($unit_name); ?>
                    </div> 
                    <div style="font-size:11px;">
                        <?= $unit_address; ?>
                    </div>

                    <div style="font-size:11px;">
                        <?php if ($unit_mobile != '') { ?>
                            Mobile : <?= $unit_mobile; ?>
                        <?php } ?>

                        <?php if ($unit_mobile != '' && $unit_email != '') { ?>
                            &nbsp;&nbsp; | &nbsp;&nbsp;
                        <?php } ?>

                        <?php if ($unit_email != '') { ?>
                            Email : <?= $unit_email; ?>
                        <?php } ?>
                    </div>

                </td>
                <!-- Blank -->
                <td width="20%" style="border:0px;"></td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <td colspan="3" style="border:0px;">
                    <div style="font-size:14px;font-weight:bold;margin-top:6px;">
                        LEAVE LEDGER
                    </div>
                    <hr>
                </td>
            </tr>
        </table>
        <?php foreach ($empRes as $emp) {
            $emp_id = $emp['emp_id'];
            $whereCredit = "emp_id='$emp_id' AND unit_id='$unitid'";
            $whereAtt = "emp_id='$emp_id'";

            if ($from_date != '' && $to_date != '') {

                $whereCredit .= "
    AND createdate
    BETWEEN '$from_date' AND '$to_date'";

                $whereAtt .= "
    AND attendance_date
    BETWEEN '$from_date' AND '$to_date'";
            }

            if ($leave_type != '') {
                if ($leave_type == 'earning') {
                    $whereCredit .= " AND leave_type IN('earning','earning_ded')";
                } else {
                    $whereCredit .= " AND leave_type='$leave_type'";
                }
            }
        ?>
            <table width="100%" style="border-collapse:collapse;margin-top:8px;">

                <tr>

                    <td style="border:0px;">
                        <b>Employee Code :</b> <?= $emp['emp_code']; ?>
                    </td>

                    <td style="border:0px;">
                        <b>Employee Name :</b>
                        <?= $emp['first_name'] . ' ' . $emp['last_name']; ?>
                    </td>

                    <td style="border:0px;">
                        <b>Department :</b>
                        <?= $obj->getvalfield(
                            "department_master",
                            "department_name",
                            "department_id='" . $emp['department_id'] . "'"
                        ); ?>
                    </td>

                </tr>

            </table>

            <br>
            <div class="table-responsive">
                <?php
                $opening_balance = $obj->getOpeningBalance(
                    $emp_id,
                    $leave_type,
                    $from_date,
                    $to_date,
                    $sessionid
                );

                $closing_balance =  $obj->getClosingBalance(
                    $emp_id,
                    $leave_type,
                    $from_date,
                    $to_date,
                    $sessionid
                );
                $ledger = [];
                $openingDate = date('d-m-Y', strtotime($from_date));
                $sortDate    = date('Y-m-d', strtotime($from_date));

                $ledger[] = [
                    'sort_date' => $sortDate,
                    'date' => $openingDate,
                    'remark' => '',
                    'leave_type' => $leave_type,
                    'particular' => 'Opening Balance',
                    'credit' => $opening_balance,
                    'debit' => 0
                ];
                $credit_res = $obj->executequery("
                    SELECT 
                        createdate,
                        month,
                        year,
                        leave_type,
                        is_opb,
                        remark,
                        total_leave
                    FROM emp_monthly_leave
                    WHERE $whereCredit
                    ORDER BY year,month
                ");

                foreach ($credit_res as $row) {
                    if ($row['is_opb'] == 2) {
                        $txt = 'Public Holiday';
                    } else {
                        $txt = 'Opening';
                    }
                    $transDate = ($row['is_opb'] == 1)
                        ? $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT) . '-01'
                        : (!empty($row['createdate'])
                            ? $row['createdate']
                            : $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT) . '-01');


                    if ($row['leave_type'] == 'earning_ded') {
                        $ledger[] = [
                            'sort_date' => $row['createdate'],
                            'date' => date('M Y', mktime(0, 0, 0, $row['month'], 1, $row['year'])),
                            'leave_type' => 'earning',
                            'particular' => 'Leave Deduction',
                            'remark' => $row['remark'] ?? '',
                            'credit' => 0,
                            'debit' => $row['total_leave']
                        ];
                    } else {
                        $ledger[] = [
                            'sort_date' => $row['createdate'],
                            'date' => date('M Y', mktime(0, 0, 0, $row['month'], 1, $row['year'])),
                            'leave_type' => $row['leave_type'],
                            'remark' => $row['remark'] ?? '',
                            'particular' => ($row['is_opb'] == 1 ? 'By Entry Opening Balance' : 'Allotment'),
                            'credit' => $row['total_leave'],
                            'debit' => 0
                        ];
                    }
                }

                $used_res = $obj->executequery("
                    SELECT
                        attendance_date,
                        month,
                        year,
                        in_remark,
                        attendance_status
                    FROM attendance_entry
                    WHERE $whereAtt
                    AND attendance_status IN (
                        'Extra Off',
                        'Half Extra Off',
                        'C Off',
                        'Half C Off',
                        'Leave',
                        'Half Leave',
                        'Earning Leave',
                        'Half Earning Leave'
                    )
                    ORDER BY attendance_date
                ");

                foreach ($used_res as $row) {

                    $type = '';
                    $debit = 1;
                    switch ($row['attendance_status']) {
                        case 'Extra Off':
                            $type = 'eoff';
                            $debit = 1;
                            break;
                        case 'Half Extra Off':
                            $type = 'eoff';
                            $debit = 0.5;
                            break;
                        case 'C Off':
                            $type = 'weekly';
                            $debit = 1;
                            break;
                        case 'Half C Off':
                            $type = 'weekly';
                            $debit = 0.5;
                            break;
                        case 'Leave':
                        case 'Earning Leave':
                            $type = 'earning';
                            $debit = 1;
                            break;
                        case 'Half Leave':
                        case 'Half Earning Leave':
                            $type = 'earning';
                            $debit = 0.5;
                            break;
                    }

                    if ($leave_type != '' && $leave_type != $type) {
                        continue;
                    }
                    $particular = 'Application';
                    $ledger[] = [
                        'sort_date' => $row['attendance_date'],
                        'date' => date(
                            'M Y',
                            mktime(0, 0, 0, $row['month'], 1, $row['year'])
                        ),
                        'leave_type' => $type,
                        'remark' => $row['in_remark'],
                        'particular' => $particular,
                        'credit'     => 0,
                        'debit'      => $debit
                    ];
                }
                usort($ledger, function ($a, $b) {

                    return strtotime($a['sort_date']) <=> strtotime($b['sort_date']);
                });

                $balances = [
                    'earning' => 0,
                    'weekly'  => 0,
                    'eoff'    => 0
                ];
                ?>

                <table class="ledger-table" style="margin-bottom: 10px;">

                    <thead class="table-primary">
                        <tr>
                            <th>Date</th>
                            <th>Leave Type</th>
                            <th>Particular</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Balance</th>
                            <th>Remark</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        foreach ($ledger as $row) {

                            $balances[$row['leave_type']] += $row['credit'];
                            $balances[$row['leave_type']] -= $row['debit'];

                            switch ($row['leave_type']) {

                                case 'earning':
                                    $leave_name = 'Earning Leave';
                                    break;

                                case 'weekly':
                                    $leave_name = 'C Off';
                                    break;

                                case 'eoff':
                                    $leave_name = 'Extra Off';
                                    break;

                                default:
                                    $leave_name = ucfirst($row['leave_type']);
                            }

                        ?>

                            <tr>

                                <td>
                                    <?= $obj->dateformatindia($row['sort_date']) ?>
                                </td>

                                <td>
                                    <?= $leave_name ?>
                                </td>

                                <td>
                                    <?= $row['particular'] ?>
                                </td>
                                <td class="text-success fw-bold">
                                    <?= $row['credit'] ?>
                                </td>
                                <td class="text-danger fw-bold">
                                    <?= $row['debit'] ?>
                                </td>
                                <td>
                                    <?= $balances[$row['leave_type']] ?>
                                </td>
                                <td>
                                    <?= $row['remark'] ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <th colspan="5" class="text-end">
                                Closing Balance
                            </th>
                            <th>
                                <?= number_format($closing_balance, 2) ?>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>

                </table>
            </div>

        <?php } ?>
    </div>
</body>

</html>

<?php

$html = ob_get_clean();


error_reporting(0);
ini_set('display_errors', 0);

$mpdf->WriteHTML($html);

while (ob_get_level()) {
    ob_end_clean();
}

$mpdf->Output('leave_ledger.pdf', 'I');
exit;
?>