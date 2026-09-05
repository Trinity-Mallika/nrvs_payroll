<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';
$pagename = "employee_concern_pdf.php";
$title = "Employee List";
$tblname = "employee_master";
$tblpkey = "emp_id";
$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'margin_top' => 10,
    'margin_bottom' => 5,
    'margin_left' => 20,
    'margin_right' => 20,
]);
// $emp_id = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
// if($emp_id>0){
//     $emp_data = $obj->select_record("employee_master",['emp_id'=>$emp_id]);
//     $first_name        = $emp_data['first_name'] ?? '';
//     $emp_code          = $emp_data['emp_code'] ?? '';
//     $father_name       = $emp_data['father_name'] ?? '';
//     $department_id     = $emp_data['department_id'] ?? '';
//     $designation_id    = $emp_data['designation_id'] ?? '';
//     $basic_salary      = $emp_data['basic_salary'] ?? '';
//     $permanent_address = $emp_data['permanent_address'] ?? '';
// }else{
    $first_name        = $_POST['first_name'] ?? '';
    $emp_code          = $_POST['emp_code'] ?? '';
    $father_name       = $_POST['father_name'] ?? '';
    $department_id     = $_POST['department_id'] ?? '';
    $designation_id    = $_POST['designation_id'] ?? '';
    $basic_salary      = $_POST['basic_salary'] ?? '';
    $permanent_address = $_POST['permanent_address'] ?? '';
    $join_date = $_POST['join_date'] ?? date('Y-m-d');
 
$department_name = $obj->getvalfield("department_master","department_name","department_id='$department_id'");
$designation = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");
 
$unit = $obj->select_record("unit_master",["unit_id" => $unitid]);
    $is_all_leave_add = $unit['add_leave'] ?? 0;
    $unit_logo        = $unit['logo_image'] ?? '';
    $unit_name        = $unit['unit_name'] ?? '';
    $head_name        = $unit['unithead'] ?? '';
    $unit_mobile      = $unit['mobile'] ?? '';
    $unit_email       = $unit['email_id'] ?? '';
    $unit_address     = $unit['address'] ?? '';  
    $gstin_no     = $unit['gstin_no'] ?? '';  
    $cin_no     = $unit['cin_no'] ?? '';  
 
ob_start(); 

?>
<!-- $html = <<<HTML -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>To Whom It May Concern</title>

    <style>
    body {
        font-family: "Times New Roman", serif;
        margin: 40px;
        color: #000;
        font-size: 16px;
        line-height: 1.8;
    }

    .header {
        display: flex;
        align-items: center;
        position: relative;
        border-bottom: 1px solid #999;
        padding-bottom: 10px;
    }

    .logo-section {
        width: 150px;
    }

    .logo-section img {
        width: 120px;
    }

    .company-section {
        flex: 1;
        text-align: center;
    }

    .company-name {
        font-size: 34px;
        font-weight: bold;
        color: #666;
    }

    .company-info {
        position: relative;

        right: 0;
        font-size: 10px;
        text-align: right;
        line-height: 1.3;
    }


    .title {
        text-align: center;
        font-size: 25px;
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .para {
        text-align: justify;
        margin-bottom: 15px;
    }

    .line {
        display: inline-block;
        border-bottom: 1px dotted #000;
        min-width: 120px;
        padding: 0 5px;
        text-align: center;
    }

    .address-line {
        border-bottom: 1px dotted #000;
        height: 25px;
        margin-top: 10px;
    }

    .footer {
        margin-top: 80px;
        font-weight: bold;
    }
    </style>

</head>

<body>


    <table width="100%" style="border-bottom:1px solid #999;padding-bottom:10px;">
        <tr>
            <td width="15%" valign="top">
                <img src="img/logo1.png" width="100">
            </td>

            <td width="70%" align="center">
                <div style="font-size:28px;font-weight:bold;color:#666;">
                    <?= $unit_name ?>
                </div>

                <div style="font-size:13px;">
                    (<?=$head_name?>)
                </div>

                <div style="font-size:13px;">
                    <?=$unit_address?>
                </div>

                <div style="font-size:13px;">
                    E-mail: <?=$unit_email?> , Contact No.- <?=$unit_mobile?>
                </div>

                <div style="font-size:13px;">
                    MANUFACTURERS OF IRON AND STEEL
                </div>
            </td>

            <td width="15%" align="right">
                <div style="font-size:10px; ">
                    CIN: <?=$cin_no?><br>
                    GSTIN: <?=$gstin_no?>
                </div>
            </td>
        </tr>
    </table>
    <p style="text-align:right; margin-bottom:0;">
        Date - <?= $join_date ?>
    </p>
    <div class="title">
        TO WHOM IT MAY CONCERN
    </div>

    <p class="para">
        This is to certify that <strong>Mr./Mrs.
            <span class="line"><?=$first_name?></span></strong>
        <strong>Employee Code
            <span class="line"><?=$emp_code?></span></strong>
        is employed with our organization. He/She is S/o
        <strong><span class="line"><?=$father_name?></span></strong>
        and is working as Designation
        <strong><span class="line"><?=$designation?></span></strong>
        in the <strong>
            <span class="line"><?=$department_name?></span></strong>
        Department.
    </p>

    <p class="para">
        His/Her salary
        <span class="line"><?=$obj->formatAmount($basic_salary)?></span>
        in our organization which is addressed as
        <strong><?=$unit_name?>, <?=$unit_address?></strong>.
    </p>

    <p class="para">
        We request you to kindly open a salary account in his/her name.
        The permanent residential <strong>Address - </strong>
        <span class="line" style="min-width:350px;">
            <?=$permanent_address?>
        </span>.
    </p>


    <br>



    <p>
        This testimonial may be used for the purpose of
        <strong>New Bank Salary Account.</strong>
    </p>

    <p><strong>Thanks & Regards</strong></p>

    <div class="footer">
        HR & ADMIN<br>
        <?=$unit_name?>, <?=$head_name?>
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

$mpdf->Output('employee.pdf', 'I');
exit;
?>