<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10,
]);

// $watermark = __DIR__ . '/img/logo.jpg';

// $mpdf->SetWatermarkImage($watermark);

// $mpdf->showWatermarkImage = true;

// Opacity (0.1 = light, 1 = dark)
// $mpdf->watermarkImageAlpha = 0.1;

$tblname = "loan_advance";
$tblpkey = "loan_advance_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$fields = [
    'loan_date',
    'emp_id',
    'type',
    'reference_no',
    'loan_adv_amt',
    'interest_amount',
    'start_month',
    'start_year',
    'last_month',
    'last_year',
    'no_of_inst',
    'inst_amount',
    'total_amount',
    'remark',
    'appr_remark',
    'attach_file',
    'loan_type_id',
    'guarantor_name1',
    'guarantor_name2',
    'purpose_of_loan_adv',
    'print_loan_appl',
    'appr_status',
    'createdby',
    'ipaddress',
    'createdate',
    'lastupdated',
    'unit_id',
    'sessionid'
];
if (isset($_GET[$tblpkey])) {

    $details_result = $obj->executequery("SELECT * FROM loan_advance_details WHERE loan_advance_id = '$keyvalue' ORDER BY year ASC, month ASC");

    $where = array($tblpkey => $keyvalue);
    $row = $obj->select_record($tblname, $where);
    foreach ($fields as $field) {
        $$field = isset($row[$field]) ? $row[$field] : '';
    }

    $loan_type = $obj->getvalfield("loan_type", "loan_type", "loan_type_id='$loan_type_id'");
    $empData = $obj->select_record("employee_master", ['emp_id' => $emp_id]);

    $first_name      = $empData['first_name']??'';
    $emp_code        = $empData['emp_code']??'';
    $date_of_joining = $empData['date_of_joining']??'';
    $salary          = $empData['basic_salary']??'';
    $designation_id  = $empData['designation_id']??'';
    $department_id   = $empData['department_id']??'';
    
    $designation_name = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");
    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");

    $unitData = $obj->select_record("unit_master", ['unit_id' => $unit_id]);
    $unit_logo    = $unitData['logo_image']??'';
    $unit_name    = $unitData['unit_name']??'';
    $head_name    = $unitData['unithead']??'';
    $unit_mobile  = $unitData['mobile']??'';
    $unit_email   = $unitData['email_id']??'';
    $unit_address = $unitData['address']??'';
    $gst          = $unitData['gstin_no']??'';
 

    $bank_row = $obj->select_record(
        "emp_bank_details",
        array("emp_id" => $emp_id, "is_active" => 1)
    );

    $bank_id = isset($bank_row['bank_id']) ? $bank_row['bank_id'] : '';
    $acc_holder_name = isset($bank_row['acc_holder_name']) ? $bank_row['acc_holder_name'] : '';
    $account_no = isset($bank_row['account_no']) ? $bank_row['account_no'] : '';
    $ifsc_code = isset($bank_row['ifsc_code']) ? $bank_row['ifsc_code'] : '';

    $bank = $obj->getvalfield("bank_master", "bank_name", "bank_id='$bank_id'");

    $total_paid = $obj->getvalfield("loan_advance_details", "IFNULL(SUM(amount),0)", "loan_advance_id='$keyvalue' AND is_paid='1'");
    $outstanding = $total_amount - $total_paid;
}

$unit_imgpath = 'uploaded/unit_logo/';
$logo_html = '';

if (!empty($unit_logo) && file_exists($unit_imgpath . $unit_logo)) {
    $logo_html = '<img src="' . __DIR__ . '/' . $unit_imgpath . $unit_logo . '" height="50">';
}

$unit_imgpath = __DIR__ . '/uploaded/unit_logo/';

if (!empty($unit_logo) && file_exists($unit_imgpath . $unit_logo)) {

    $mpdf->SetWatermarkImage($unit_imgpath . $unit_logo);

    $mpdf->showWatermarkImage = true;

    $mpdf->watermarkImageAlpha = 0.1; // opacity
}
$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Advance Summary</title>
    <style>
    body {
    font-family: dejavusans;
}

        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 3px 0px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table th,
        table td {
            padding: 2px;
        }

        table.border th,
        table.border td {
            border: 1px solid #000;
            padding: 2px;
        }
            p {
            font-size: 12px;
            }
    </style>
</head>

<body>
    <div>
        <table>
            <tr>
                <td width="18%">
                    ' . $logo_html . '
                </td>
                <td style="text-align: center;">
                    <h2>' . $unit_name . ' <br> ' . $head_name . ' </h2>
                    <p style="font-size:11px;font-weight: bold;">' . $unit_address . ' </p><br>
                   <h2 style="margin-top:30px;"> Loan & Advance Reciept </h2>
                </td>
                <td width="18%"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td> Date</td>
                <td> : &nbsp; ' . $obj->dateformatindia($loan_date) . '</td>
                <td> Reference No. </td>
                <td> : &nbsp; ' . $reference_no . '</td>
            </tr>
            <tr>
                <td> Employee Name</td>
                <td> : &nbsp; ' . $first_name . '(' . $emp_code . ')' . '</td>
                <td> Type</td>
                <td> : &nbsp; ' . $type . '</td>
            </tr>
            <tr>
                <td> Date Of Joining</td>
                <td> : &nbsp; ' . $obj->dateformatindia($date_of_joining) . '</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td> Department Name</td>
                <td>: &nbsp; ' . $department_name . '</td>
                <td>Designation Name</td>
                <td>: &nbsp; ' . $designation_name . '</td>
            </tr>
            <tr>
                <td>Loan/Advance Amount</td>
                <td>: &nbsp; ' . $loan_adv_amt . '</td>
                <td> Interest Amt</td>
                <td> : &nbsp; ' . $interest_amount . '</td>
            </tr>
            <tr>
                <td> Installment</td>
                <td> : &nbsp; ' . $no_of_inst . '</td>
                <td>Start Month</td>
                <td> : &nbsp; ' . $start_month . '</td>
            </tr>
            <tr>
                <td>Loan Type</td>
                <td>: &nbsp; ' . $loan_type . '</td>
                <td> Guarantor (I)</td>
                <td>: &nbsp; ' . $guarantor_name1 . '</td>
            </tr>
            <tr>
                <td> Purpose of Loan</td>
                <td>: &nbsp; ' . $purpose_of_loan_adv . ' </td>
                <td>Guarantor (II)</td>
                <td>: &nbsp; ' . $guarantor_name2 . '</td>
            </tr>
        </table>
        <table class="border">
            <tr>
                <th >Sr.no.</th>
                <th>Month Year</th>
                <th>Amount</th>
                <th>Paid Amt</th>
                <th>Remarks</th>
            </tr>
           ';

$i = 1;

foreach ($details_result as $row_details) {

    $month_year = date("F Y", strtotime($row_details['year'] . '-' . $row_details['month'] . '-01'));

    $amount = number_format($row_details['amount'], 2);

    $paid_amt = ($row_details['is_paid'] == 1) ? number_format($row_details['amount'], 2) : '0.00';

    $remarks = $row_details['remark'];


    $html .= '
             <tr>
             <td>' . $i++ . '</td>
             <td>' . $month_year . '</td>
             <td>' . $amount . '</td>
             <td>' . $paid_amt . '</td>
             <td>' . $remarks . '</td>
             </tr>
         ';
}

$html .= '
</table>
';
$html .= '
        <table>
            <tr>
                <td rowspan="6">
                    <p style="font-weight: bold;">Total Amount: ' . $total_amount . '</p>
                    <p style="font-weight: bold;"> Total Paid Amount: ' . number_format($total_paid, 2) . '</p>
                    <p style="font-weight: bold;">Total Outstanding Balance Amount: ' . number_format($outstanding, 2) . '</p>
                </td>
                <td style="text-align: right;">Gross Salary</td>
                <td>&nbsp; : &nbsp; ' . $salary . '</td>
            </tr>
            <tr>
                <td style="text-align: right;"> Bank Account Holder Name</td>
                <td>&nbsp; : &nbsp; ' . $acc_holder_name . '</td>
            </tr>
            <tr>
                <td style="text-align: right;">Account Number</td>
                <td>&nbsp; : &nbsp; ' . $account_no . '</td>
            </tr>
            <tr>
                <td style="text-align: right;">IFSC Code</td>
                <td>&nbsp; : &nbsp; ' . $ifsc_code . '</td>
            </tr>

            <tr>
                <td style="text-align: right;">Employee Bank Name</td>
                <td>&nbsp; : &nbsp; ' . $bank . '</td>
            </tr>
        </table>
        <p style="margin:5px 0px 20px 0px;">Remarks: ' . $remark . '</p>
        <table>
            <tr>

                <td width="70%">
                    <b>Approved By</b>
                    <br> <br>
                </td>
            </tr>
            <tr>
                <td><b>Signature Of Employee</b></td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <td width="70%"><b>Account Signature</b> </td>
                <td>
                    <b>PM Signature</b>
                    <br><br><br>
                    <b>Signature Of Authorized</b>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>';
$mpdf->SetFooter('Print Date : {DATE j-m-Y h:i A} | Page {PAGENO} of {nbpg}');

$mpdf->WriteHTML($html);

$mpdf->Output("On_Duty_Form.pdf", "I");
