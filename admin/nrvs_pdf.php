<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';


$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_left'   => 8,
    'margin_right'  => 8,
    'margin_top'    => 8,
    'margin_bottom' => 8
]);


$unit_imgpath = 'uploaded/emp_documents/';
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
    'loan_amt',
    'advance_amt',
    'additional_payment',
    'tds_deduction',
    'other_deduction',
    'unit_id',
    'createdby',
    'ipaddress',
    'createdate',
    'lastupdated',
    'sessionid'
];
if (isset($_GET[$tblpkey])) {
    $where = array($tblpkey => $keyvalue);
    $row = $obj->select_record($tblname, $where);
    foreach ($fields as $field) {
        $$field = isset($row[$field]) ? $row[$field] : '';
    }
    $unit = $obj->select_record("unit_master", ["unit_id" => $unit_id]);
    $unit_logo        = $unit['logo_image'] ?? '';
    $unit_name        = $unit['unit_name'] ?? '';
    $unit_head        = $unit['unithead'] ?? '';
    $unit_mobile      = $unit['mobile'] ?? '';
    $unit_email       = $unit['email_id'] ?? '';
    $unit_address     = $unit['address'] ?? '';
    $unit_city     = $unit['city'] ?? '';

    $header_line_1 = !empty($unit_head) ? strtoupper($unit_head) : 'SPONGE, STEEL & POWER DIVISION';
    $header_line_2 = !empty($unit_address) ? strtoupper(trim($unit_address . (!empty($unit_city) ? ', ' . $unit_city : ''))) : 'TARAIMAL, RAIGARH-496001 (C.G.)';

    $header_month_name = strtoupper(date('F', mktime(0, 0, 0, $month, 1)));
    $header_month_year = $header_month_name . '-' . $year;

    // existing total month days and LOP calculations
    $total_month_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $lop_day = max(0, $total_month_days - $total_working_days);
    $total_deduction = $loan_amt + $advance_amt + $other_deduction + $tds_deduction + $pf_emp + $esic_emp;
    $total_earning = $total_salary + $additional_payment;

    $net_pay = $total_earning - $total_deduction;
    $total_salary_words = ucwords($obj->getIndianCurrency($net_pay));
}

$leave = $obj->getEmployeeEarningLeave($emp_id, $month, $year, $sessionid);
$earning_leave = $leave['balance_leave'] ?? 0;

$extra_off =
    $obj->getExtraOffBalance(
        $emp_id,
        $month,
        $year
    );

$coff_leave =
    $obj->getEmpCoffLeave(
        $emp_id,
        $sessionid,
        $month,
        $year
    );

$total_balance =
    $earning_leave +
    ($extra_off['balance'] ?? 0) +
    $coff_leave;



$res_att = $obj->executequery("
    SELECT  
        SUM(CASE 
            WHEN attendance_status IN ('Weekly Leave','Earning Leave','Extra Off','C Off') THEN 1 
            ELSE 0 
        END) AS total_full_leave,

        SUM(CASE 
            WHEN attendance_status IN ('Half Weekly Leave','Half Earning Leave','Half Extra Off','Half C Off') THEN 0.5 
            ELSE 0 
        END) AS total_half 
    FROM attendance_entry
    WHERE emp_id = '$emp_id' 
    AND month = '$month' 
    AND year = '$year'
");
$row = $res_att[0] ?? [];

$total_full_leave = $row['total_full_leave'] ?? 0;
$total_half    = $row['total_half'] ?? 0;
$taken_leave = $total_full_leave + $total_half;
//$opening = $total_balance + $taken_leave;
$opening_leave = $total_balance + $taken_leave;
if (!empty($emp_id)) {
    $emp_data = $obj->select_record("employee_master", array("emp_id" => $emp_id));
    $first_name       = $emp_data['first_name'];
    $last_name       = $emp_data['last_name'];
    $father_name       = $emp_data['father_name'];
    $pan_no       = $emp_data['pan_no'];
    $aadhar_no       = $emp_data['aadhar_no'];
    $pf_uan       = $emp_data['pf_uan'];
    $uan_no       = $emp_data['uan_no'];
    $esic_no       = $emp_data['esic_no'];
    $emp_code       = $emp_data['emp_code'];
    $date_of_joining       = $emp_data['date_of_joining'];
    $date_of_birth = $emp_data['dob'] ?? '';
    $designation_id       = $emp_data['designation_id'];
    $department_id       = $emp_data['department_id'];
    $unit_id       = $emp_data['unit_id'];
    $designation = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");
    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");
    $month_name = strtoupper(date('M', mktime(0, 0, 0, $month, 1)));

    // Active Bank Details
    $bank_data = $obj->select_record(
        "emp_bank_details",
        array(
            "emp_id" => $emp_id,
            "is_active" => 1
        )
    );

    $bank_id      = $bank_data['bank_id'] ?? 0;
    $account_no   = $bank_data['account_no'] ?? '';
    $ifsc_code    = $bank_data['ifsc_code'] ?? '';
    $branc_name    = $bank_data['branch_name'] ?? '';
    $acc_holder_name = $bank_data['acc_holder_name'] ?? '';

    $bank_name = '';
    if ($bank_id > 0) {
        $bank_name = $obj->getvalfield(
            "bank_master",
            "bank_name",
            "bank_id='$bank_id'"
        );
    }
    $pay_mode = "A/C TRANSFER";
    $branch_name = "";
}

$default_logo = __DIR__ . '/img/logo1.png';
$logo = $default_logo;
if (!empty($unit_logo)) {
    $candidate_paths = [
        __DIR__ . '/../management/' . $unit_imgpath . $unit_logo,
        __DIR__ . '/' . $unit_imgpath . $unit_logo
    ];
    foreach ($candidate_paths as $candidate) {
        if (file_exists($candidate)) {
            $logo = $candidate;
            break;
        }
    }
}

$rowspan = 1;
if ($hra > 0) $rowspan++;
if ($medical > 0) $rowspan++;
if ($conveyance > 0) $rowspan++;
if ($special_allow > 0) $rowspan++;
if ($additional_payment > 0) $rowspan++;

$html = '
<!DOCTYPE  >
<html>
<head>
<style>
body{
    width: 60%;
    padding-left: 250px;
    font-family: sans-serif;
    font-size:12px;
}
table{
    border-collapse:collapse;
    width:100%;
}
td{
    border:1px solid #405189;
    padding:4px;
}
.heading{
    background:#405189;
    color:#fff;
    text-align:center;
    font-weight:bold;
    font-size:16px;
}
.subhead{
    background:#405189;
    color:#fff;
    text-align:center;
    font-weight:bold;
}
.label{
    width:28%;
}
.value{
    width:22%;
}
.center{
    text-align:center;
}
.right{
    text-align:right;
}
.bold{
    font-weight:bold;
}
.colon{
    width:8px;       /* जितना कम चाहिए */
    text-align:center;
    padding:-500px;
} 

</style>

<table border="1">
<tr>
    <td width="15%" rowspan="4" align="center">
      <img src="' . $logo . '" width="100" hight="100">
    </td>
    <td class="heading">' . htmlspecialchars(strtoupper($unit_name)) . '</td>
</tr>
<tr>
    <td align="center"><b>' . htmlspecialchars($header_line_1) . '</b></td>
</tr>
<tr>
    <td align="center"><b>' . htmlspecialchars($header_line_2) . '</b></td>
</tr>
<tr>
    <td align="center"><b>PAY SLIP FOR THE MONTH OF ' . htmlspecialchars($header_month_year) . '</b></td>
</tr>
</table>

<table>
<tr>
    <td width="50%" class="subhead">EMPLOYEE INFORMATION</td>
    <td width="50%" class="subhead">STATUTORY DETAILS</td>
</tr>

<tr>
<td valign="top">

<table>
<tr><td>EMPLOYEE NAME</td><td  class="colon">:</td><td>' . strtoupper(trim($first_name . ' ' . $last_name)) . '</td></tr>
<tr><td>FATHER/HUS. NAME</td><td class="colon">:</td><td>' . strtoupper(trim($father_name)) . '</td></tr>
<tr><td>EMPLOYEE CODE</td><td class="colon">:</td><td>' . $emp_code . '</td></tr>
<tr><td>DATE OF JOINING</td><td class="colon">:</td><td>' . $obj->dateformatindia($date_of_joining) . '</td></tr>
<tr><td>DESIGNATION</td><td class="colon">:</td><td>' . $designation . '</td></tr>
<tr><td>DEPARTMENT</td><td class="colon">:</td><td>' . $department_name . '</td></tr>
<tr><td>DATE OF BIRTH</td><td class="colon">:</td><td>' . $obj->dateformatindia($date_of_birth)  . '</td></tr>
<tr><td>PAN NO.</td><td class="colon">:</td><td>' . $pan_no . '</td></tr>
<tr><td>AADHAR NO.</td><td class="colon">:</ td><td>' . $aadhar_no . '</ td></tr>
</table>

</td>

<td valign="top">

<table>
<tr><td>PF NO.</td><td class="colon">:</td><td>' . $pf_uan . '</td></tr>
<tr><td>UAN NO.</td><td class="colon">:</td><td>' . $uan_no . '</td></tr>
<tr><td>ESIC NO.</td><td class="colon">:</td><td>' . $esic_no . '</td></tr>

<tr><td colspan="3" class="subhead">BANK DETAILS</td></tr>

<tr><td>PAY MODE</td><td class="colon">:</td><td>' . $pay_mode . '</td></tr>
<tr><td>ACCOUNT NO.</td><td class="colon">:</td><td>' . $account_no . '</td></tr>
<tr><td>IFSC CODE</td><td class="colon">:</td><td>' . $ifsc_code . '</td></tr>
<tr><td>BANK NAME</td><td class="colon">:</td><td>' . $bank_name . '</td></tr>
<tr><td>BRANCH NAME</td><td class="colon">:</td><td>' . $branc_name . '</td></tr>
</table>

</td>
</tr>
</table>

<table>
<tr align="center">
<td><b>MONTH DAYS</b></td>
<td>' . number_format($total_month_days, 2) . '</td>
<td><b>LOP DAYS</b></td>
<td>' . number_format($lop_day, 2) . '</td>
<td><b>PAY DAYS</b></td>
<td>' . number_format($total_working_days, 2) . '</td>
</tr>
</table>

<table>
<tr>
<td width="50%" class="subhead">EARNINGS HEADS</td>
<td width="50%" class="subhead">DEDUCTION HEADS</td>
</tr>

<tr>
<td valign="top">

<table>
<tr >
    <td >BASIC + DA</td>
    <td class="colon">:</td>
    <td class="right">' . number_format($basic_da, 2) . '</td>
</tr>
' .
    ($hra > 0 ? '
<tr>
    <td>HRA</td>
    <td class="colon">:</td>
    <td class="right">' . number_format($hra, 2) . '</td>
</tr>' : '') .

    ($medical > 0 ? '
<tr>
    <td>MEDICAL ALLOWANCE</td>
    <td class="colon">:</td>
    <td class="right">' . number_format($medical, 2) . '</td>
</tr>' : '') .

    ($conveyance > 0 ? '
<tr>
    <td>CONVEYANCE ALLOWANCE</td>
    <td class="colon">:</td>
    <td class="right">' . number_format($conveyance, 2) . '</td>
</tr>' : '') .

    ($special_allow > 0 ? '
<tr>
    <td>SPECIAL ALLOWANCE</td>
    <td class="colon">:</td>
    <td class="right">' . number_format($special_allow, 2) . '</td>
</tr>' : '') .

    ($additional_payment > 0 ? '
<tr>
    <td>ADDITION</td>
    <td class="colon">:</td>
    <td class="right">' . number_format($additional_payment, 2) . '</td>
</tr>' : '') .
    '
</table>

</td>

<td valign="top">

<table>
<tr><td>PROVIDENT FUND</td><td class="colon">:</td><td class="right">' . $pf_emp . '</td></tr>
<tr><td>ESIC</td><td class="colon">:</td><td class="right">' . $esic_emp . '</td></tr>
<tr><td>ADVANCE</td ><td class="colon">:</td><td class="right">' . $advance_amt . '</td></tr>
<tr><td>LOAN</td><td class="colon">:</td><td class="right">' . $loan_amt . '</td></tr>
<tr><td>TDS</td><td class="colon">:</td><td class="right">' . $tds_deduction . '</td></tr>
<tr><td>OTHER DED</td><td class="colon">:</td><td class="right">' . $other_deduction . '</td></tr>
</table>

</td>
</tr>
</table>

<table>
<tr>
<td width="25%" class="bold">TOTAL EARNINGS</td>
<td width="25%" class="right bold">' . number_format($total_earning, 2) . '</td>
<td width="25%" class="bold">TOTAL DEDUCTION</td>
<td width="25%" class="right bold">' . number_format($total_deduction, 2) . '</td>
</tr>

<tr>
<td class="bold">GROSS SALARY</td>
<td class="right bold">' . number_format($total_salary, 2) . '</td>
<td class="bold">NET SALARY</td>
<td class="right bold">' . number_format($net_pay, 2) . '</td>
</tr>

<tr>
<td colspan="4"><b>IN WORD:- ' . $total_salary_words . '</b></td>
</tr>
</table>
<table>
<tr align="center">
<td><b>LEAVE OPENING</b></td>
<td>' . $opening_leave . '</td>
<td><b>LEAVE TAKEN</b></td>
<td>' . $taken_leave . '</td>
<td><b>LEAVE CLOSING</b></td>
<td>' . $total_balance . '</td>
</tr>
</table>
<p style="text-weight:700;text-align:center;margin:10px 0px 5px;">This is computer generated pay-slip and do not require any Signature. ' . date('D, d M Y h:i:s A') . '</p>
<hr style="margin:5px 0px;" />
<p style="text-align:right;"><b>"Safety First & Must"</b></p> 
</head>
</html>
';

$mpdf->WriteHTML($html);
$mpdf->Output('salary-slip.pdf', 'I');
