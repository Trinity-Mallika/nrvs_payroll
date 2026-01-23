<?php

include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 25,         // No margin at top
    'margin_bottom' => 20,      // No margin at bottom
    'margin_left' => 7,
    'margin_right' => 7,
]);

// Get page height in mm for absolute positioning (default A4 = 297mm)
$pageHeight = 200;
$headerHeight = 40; // in mm
$unit_imgpath = 'uploaded/unit_logo/';
$tblname = "salary_structure";
$tblpkey = "salary_struc_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$fields = [
    'salary_struc_id',
    'emp_id',
    'department_id',
    'month',
    'year',
    'basic_salary',
    'increment',
    'revised_salary',
    'basic_pf_rate',
    'pf_esic_basic',
    'total_working_days',
    'basic_da',
    'hra',
    'medical',
    'conveyance',
    'special_allow',
    'total_salary',
    'total_net_salary',
    'pf_emp',
    'esic_emp',
    'pf_employer',
    'esic_employer',
    'total_c_off',
    'c_off_leave',
    'unit_id',
    'createdby',
    'ipaddress',
    'createdate',
    'lastupdated',
    'sessionid'
];
$dt = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
$current_date_time = $dt->format('D, d M Y h:i:s A');


if (isset($_GET[$tblpkey])) {
    $where = array($tblpkey => $keyvalue);
    $row = $obj->select_record($tblname, $where);
    foreach ($fields as $field) {
        $$field = isset($row[$field]) ? $row[$field] : '';
    }
    $total_salary_words = $obj->getIndianCurrency($total_salary);

    $lpg_ded = $obj->getvalfield("emp_deduction", "lpg_ded", "emp_id='$emp_id' and month='$month' and year='$year'");
    $shoes_ded = $obj->getvalfield("emp_deduction", "shoes_ded", "emp_id='$emp_id' and month='$month' and year='$year'");
    $other = $obj->getvalfield("emp_deduction", "other", "emp_id='$emp_id' and month='$month' and year='$year'");

    $loan = $obj->getvalfield("emi_setting_details", "amount_detail", "emp_id='$emp_id' and month_detail='$month' and year_detail='$year'") ?? 0;
    $unit_logo = $obj->getvalfield("unit_master", "logo_image", "unit_id='$unit_id'");


    $total_deduction = $lpg_ded + $shoes_ded + $other + $pf_emp + $esic_emp;

    // If $month is like "January", "Feb", etc.
    if (!is_numeric($month)) {
        $month_number = date('n', strtotime($month . ' 1')); // 'January' → 1
    } else {
        $month_number = intval($month); // Already numeric
    }

    // Convert year to integer
    $year_number = intval($year);

    // Now calculate total days in month
    $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $year_number);

    $lop_day = max(0, $total_month_days - $total_working_days);

    $checkedDocs = [];
    if (!empty($document_checked_ids)) {
        $checkedDocs = explode(',', $document_checked_ids);
    }
}
if (!empty($emp_id)) {
    $emp_data = $obj->select_record("employee_master", array("emp_id" => $emp_id));
    $first_name       = $emp_data['first_name'];
    $last_name       = $emp_data['last_name'];
    $father_name       = $emp_data['father_name'];
    $pan_no       = $emp_data['pan_no'];
    $aadhar_no       = $emp_data['aadhar_no'];
    $pf_uan       = $emp_data['pf_uan'];
    $uan_no       = $emp_data['uan_no'];
    $ifsc_code       = $emp_data['ifsc_code'];
    $esic_no       = $emp_data['esic_no'];
    $account_no       = $emp_data['account_no'];
    $emp_code       = $emp_data['emp_code'];
    $date_of_joining       = $emp_data['date_of_joining'];
    $designation_id       = $emp_data['designation_id'];
    $department_id       = $emp_data['department_id'];
    $bank_id       = $emp_data['bank_id'];
    $unit_id       = $emp_data['unit_id'];
    $designation = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");
    $bank_name = $obj->getvalfield("bank_master", "bank_name", "bank_id='$bank_id'");
    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");
    $unit_name = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
}
// echo $first_name;
// die;

$mpdf->SetHTMLHeader('
<table>
        <tr>
            <td width="20%">
                <img src="assets/images/logo.jpg" width="120px">
            </td>
            <td style="text-align: center; ">
                <h2>NRVS STEELS LIMITED</h2>
                <h3>SALARY SLIP FOR THE MONTH: OCT-2020</h3>
            </td>
            <td width="20%" style="text-align: right;">
                <img src="assets/images/nrvs-logo.png" alt="" width="50px">
            </td>
        </tr>
    </table>
', 'O'); // 'O' = apply on all pages

// IMAGE watermark
$mpdf->SetWatermarkImage(__DIR__ . '/assets/images/water-mark.png', 0.2, "", [50, 70]);
$mpdf->showWatermarkImage = true;

$html = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip</title>
    <style>
        table {
            width: 100% !important;
            border: 1px solid black;
            padding: 5px;
            border-collapse: collapse;
        }

        table tr td {
            font-size: 12px;
            padding: 5px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 2px 0px;
        }
            
    </style>
</head>

<body>

    <table>
        <tr>
            <td width="25%">
                EMP NAME
            </td>
            <td width="25%">
                : ' . ucwords(strtolower(trim($first_name . ' ' . $last_name))) . '
            </td>
            <td width="25%">
                EMP CODE
            </td>
            <td width="25%">
                :  ' . $emp_code . '
            </td>
        </tr>
        <tr>
            <td>FAR./HUS. NAME</td>
            <td> : ' . ucwords(strtolower(trim($father_name))) . '</td>
            <td>DOJ</td>
            <td> : ' . $date_of_joining . '</td>
        </tr>
        <tr>
            <td>DESIGNATION</td>
            <td> : ' . $designation . '</td>
            <td>DEPARTMENT</td>
            <td> : ' . $department_name . '</td>
        </tr>
        <tr>
            <td>BRANCH/SITE</td>
            <td> : ' . $unit_name . '</td>
            <td>PAY MODE</td>
            <td> : BANK</td>
        </tr>
        <tr>
            <td>BANK NAME</td>
            <td> : ' . $bank_name . '</td>
            <td>A/C NO</td>
            <td> : ' . $account_no . '</td>
        </tr>
        <tr>
            <td>PF NO</td>
            <td> :' . $pf_uan . '</td>
            <td>IFSC CODE</td>
            <td> : ' . $ifsc_code . '</td>
        </tr>
        <tr>
            <td>ADHAAR No</td>
            <td> :' . $aadhar_no . '</td>
            <td>UAN NO</td>
            <td> : ' . $uan_no . '</td>
        </tr>
        <tr>
            <td>PAN NO</td>
            <td> : ' . $pan_no . '</td>
            <td>ESIC NO</td>
            <td> : ' . $esic_no . '</td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <td width="17%">MONTH DAYS: ' . $total_month_days . '</td>
            <td style="border-left:none;" width="17%">LOP DAYS: ' . $lop_day . '</td>
            <td  width="17%">PAY DAYS: ' . $total_working_days . '</td>
            <td width="28%" style="border-left:1px solid #000;"></td>
            <td width="21%"></td>
        </tr>
        <tr>
            <td style="border:1px solid black;text-align:center;" colspan="3">
                <h4>EARNINGS</h4>
            </td>
            <td style="border:1px solid black;text-align:center;" colspan="2">
                <h4>DEDUCTIONS</h4>
            </td>
        </tr>
        <tr>
            <td colspan="2">BASIC + VDA</td>
            <td >' . $basic_da . '</td>
            <td style="border-left:1px solid #000;">PROVIDENT FUND</td>
            <td >' . $pf_emp . '</td>
        </tr>
        <tr>
            <td colspan="2">HRA</td>
            <td >' . $hra . '</td>
            <td style="border-left:1px solid #000;">EMPLOYEE STATE INSURANCE</td>
            <td >' . $esic_emp . '</td>
        </tr>
        <tr>
            <td colspan="2">CONVEYANCE ALLOWANCE</td>
            <td >' . $conveyance . '</td>
            <td style="border-left:1px solid #000;">LOAN & ADVANCE</td>
            <td >' . $loan . '</td>
        </tr>
        <tr>
            <td colspan="2">MEDICAL ALLOWANCE</td>
            <td >' . $medical . '</td>
            <td style="border-left:1px solid #000;"></td>
            <td ></td>
        </tr>
        <tr>
            <td colspan="2">SPECIAL ALLOWANCE</td>
            <td >' . $special_allow . '</td>
            <td style="border-left:1px solid #000;"></td>
            <td ></td>
        </tr>
        <tr>
            <td colspan="2"><b>TOTAL EARNINGS</b></td>
            <td ><b>' . $total_salary . '</b></td>
            <td style="border-left:1px solid #000;"><b>TOTAL DEDUCTIONS</b></td>
            <td ><b>' . $total_deduction . '</b></td>
        </tr>
        <tr>
            <td colspan="2"><b>GROSS SALARY</b></td>
            <td ><b>' . $revised_salary . '</b></td>
            <td style="border-left:1px solid #000;"><b>NET SALARY</b></td>
            <td ><b>' . $total_net_salary . '</b></td>
        </tr>
        <tr>
            <td colspan="5" style="border: 1px solid black;"><b>Rs. ' . $total_salary_words . '</b></td>
        </tr>
        <tr>
            <td colspan="2">LEAVE OPB: ' . $total_c_off . '</td>
            <td colspan="2" >LEAVE TAKEN: ' . $c_off_leave . '</td>
            <td colspan="2" >LEAVE CLS: ' . $total_c_off - $c_off_leave . '</td>
        </tr>
    </table>
    <p style="text-align: center;">This is computer generated pay-slip and do not require any Signature. ' . $current_date_time . '</p>

    
</body>

</html>';

// print($html);
// die;

$mpdf->WriteHTML($html);

$mpdf->Output();
