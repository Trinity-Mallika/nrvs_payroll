<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'format' => [95, 148], // ID Card Size
    'margin_left'   => 0,
    'margin_right'  => 0,
    'margin_top'    => 0,
    'margin_bottom' => 0
]);


// =======================
// FETCH Employee DATA
// =======================

$emp_id = intval($_GET['emp_id']);

$sql = "
SELECT
e.*,
d.department_name,
dg.designation
FROM employee_master e
LEFT JOIN department_master d
ON d.department_id = e.department_id
LEFT JOIN designation_master dg
ON dg.designation_id = e.designation_id
WHERE e.emp_id='$emp_id'
";

$row = $obj->executequery($sql);

if (empty($row)) {
    die("Employee Not Found");
}

$emp = $row[0];

// =======================
// Company DATA
// =======================

$unit = $obj->select_record(
    "unit_master",
    array("unit_id" => $emp['unit_id'])
); 

$company_name    = $unit['unit_name'] ?? '';
$company_address = $unit['address'] ?? ''; 

$name         = strtoupper($emp['first_name']);
$father       = strtoupper($emp['father_name']);
$ecode        = $emp['emp_code'];
$blood_group  = $emp['blood_group'];
$designation  = strtoupper($emp['designation']);
$department   = strtoupper($emp['department_name']);
$mobile       = $emp['mobile_no'];
$alt_mobile       = $emp['emer_contact_no'];
$dob   = $emp['dob'];
$address      = strtoupper($emp['present_address']);

$department = html_entity_decode($department, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$designation = html_entity_decode($designation, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$address    = html_entity_decode($address, ENT_QUOTES | ENT_HTML5, 'UTF-8');

$bg    = __DIR__ . '/img/idcard2.png';

$logo  = __DIR__ . '/img/logo1.png';
 
$photo = !empty($emp['profile_image'])
    ? __DIR__ . '/uploaded/emp_documents/' . $emp['profile_image']
    : __DIR__ . '/img/user.jpg';

// $signature = __DIR__ . '/img/signature.jpg';
$signature = !empty($emp['emp_sign'])
    ? __DIR__ . '/uploaded/emp_documents/' . $emp['emp_sign']
    : "";


$call = __DIR__ . '/img/call.png';
$user_pic = __DIR__ . '/img/name.png';
$f_pic = __DIR__ . '/img/f_name.png';
$code_pic = __DIR__ . '/img/code.png';
$dob_pic = __DIR__ . '/img/dob.png';
$blood_pic = __DIR__ . '/img/blood.png';
$position_pic = __DIR__ . '/img/position.png';
$suitcase_pic = __DIR__ . '/img/suitcase.png';
$location_pic = __DIR__ . '/img/location.png';
// =======================
// HTML
// =======================

$html = '
<!DOCTYPE html>
<html>
<head>
<style>

body{
    margin:0;
    padding:0;
    font-family:Arial, Helvetica, sans-serif;
}

.card{
    width:95mm;
    height:155mm;
    background:url(' . $bg . ');
    background-image-resize:6;
    position:relative;
}

.company{
    font-size:24px;
    font-weight:bold;
    color:#072d67;
}

.address{
    font-size:12px;
    color:#333;
}

.details{
    width:100%;
    border-collapse:collapse;
    margin:20px;
}
    

.details td{
    font-size:13px;
    padding:3px 0;
    vertical-align:top;
    
}

.label{
    width:38%;
    font-weight:bold;
}

.colon{
    width:2%;
    text-align:center;
}
 
.value{
    width:60%;
}
    

</style>
</head>

<body>

<div class="card">

<table width="100%" cellpadding="0" cellspacing="0">

    <!-- TOP SPACE -->
    <tr>
        <td colspan="3" height="10"></td>
    </tr>

    <!-- COMPANY -->
    <tr>
    <td colspan="3" align="center">

        <table width="100%" style="text-align:center;">
            <tr>

                <td width="20%" style="text-align:center;">
                    <img src="' . $logo . '" width="70">
                </td>

                <td  >
                    <div class="company">
                        ' . $company_name . '
                    </div>
                      <hr style="color:#ff3c00; background-color:#f22d00; height:1px; border:none; margin:2px 0;">

                    <div class="address">
                        ' . $company_address . '
                    </div>
                </td>

            </tr>
        </table>

    </td>
</tr>

    <!-- SPACE BEFORE PHOTO -->
      <tr>
        <td colspan="3" height="8"></td>
    </tr>

    <!-- PHOTO -->
    <tr>
    <td colspan="3" align="center">

        <table style="
            width:40mm;
            height:45mm;
            border-collapse:collapse;
            margin-right:-1.1mm;
            margin-top:-1.3mm;
             
        ">
            <tr>
                <td style="
                    width:45mm;
                    height:45mm;
                    text-align:center;
                    vertical-align:middle;
                ">
                     ' . (!empty($photo) ? '<img src="' . $photo . '" width="42mm" height="42mm">' : '') . '
                </td>
            </tr>
        </table>

    </td>
</tr>

    <!-- NAME STRIP SPACE -->
    <tr>
        <td colspan="3" height="15"></td>
    </tr>

    <!-- NAME -->
  <tr>
    <td colspan="3" align="center" style=" padding-left:12px;">

        <div style="
            color:#ffffff;
            font-size:15px;
            font-weight:bold;
            text-transform:uppercase;
            letter-spacing:.3px;
        ">
            ' . $name . '
        </div>

    </td>
</tr>

    <!-- SPACE AFTER NAME -->
    

    <!-- DETAILS -->
    <tr>

        <td width="10"></td>

        <td>

            <table class="details">

                <tr>
                    <td class="label"><img src="' . $f_pic . '" width="12" height="12">
        &nbsp;F/H NAME</td>
                    <td class="colon">:</td>
                    <td class="value">' . $father . '</td>
                </tr>

                <tr>
                    <td class="label"><img src="' . $code_pic . '" width="12" height="12">
        &nbsp;E.CODE</td>
                    <td class="colon">:</td>
                    <td class="value">' . $ecode . '</td>
                </tr>

                <tr>
                    <td class="label"><img src="' . $dob_pic . '"width="10" height="10"> &nbsp;D.O.B.</td>
                    <td class="colon">:</td>
                    <td class="value">'.$obj->dateformatindia($dob).'</td>
                </tr>

                <tr>
                    <td class="label"><img src="' . $blood_pic . '"width="10" height="10"> &nbsp;BLOOD GROUP</td>
                    <td class="colon">:</td>
                    <td class="value">' . $blood_group . '</td>
                </tr>

                <tr>
                    <td class="label"><img src="' . $position_pic . '" width="12" height="12">
        &nbsp;DESIGNATION</td>
                    <td class="colon">:</td>
                    <td class="value">' . $designation . '</td>
                </tr>

                <tr>
                    <td class="label"><img src="' . $suitcase_pic . '" width="12" height="12">
        &nbsp;DEPARTMENT</td>
                    <td class="colon">:</td>
                    <td class="value">' . $department . '</td>
                </tr>

                <tr>
                    <td class="label"><img src="' . $location_pic . '" width="12" height="12">
        &nbsp;ADDRESS</td>
                    <td class="colon">:</td>
                    <td class="value">'.$address.'</td>
                </tr>

            </table>

        </td>

        <td width="10"></td>

    </tr>

    <!-- SIGNATURE SPACE -->
    <tr>
        <td colspan="3" height="1"></td>
    </tr>
    

    <!-- SIGNATURE -->
    <tr>
    <td colspan="3" align="center" style="padding:0px 35px;">

      ' . (!empty($signature) ? '<img src="' . $signature . '" width="32mm">' : '') . '

        <div style="
            font-size:8px;
            font-weight:bold;
             
        ">
            EMPLOYEE SIGNATURE
        </div>

    </td>
</tr>


</table>


</div>
<div style="
    position:absolute;
    bottom:2.2mm;
    left:0;
    width:100%;
    color:#FFFFFF;
    padding:0px 20px;
">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>

            <!-- MOBILE -->
            <td width="50%" align="left">

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <img src="' . $call . '" width="16" style="vertical-align:middle;">
                        </td>

                        <td style="padding-left:4px; text-align:left;">

                            <div style="font-size:10px; line-height:8px;color:white;">
                                MOBILE
                            </div>

                            <div style="font-size:12px; font-weight:bold; line-height:12px;color:white;">
                                ' . $mobile . '
                            </div>

                        </td>
                    </tr>
                </table>

            </td>

           

            <!-- ALT -->
            <td width="50%" align="center">

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <img src="' . $call . '" width="16" style="vertical-align:middle;">
                        </td>

                        <td style="padding-left:4px; text-align:left;">

                            <div style="font-size:8px; line-height:10px;color:white;">
                                EMERGENCY/HOME
                            </div>

                            <div style="font-size:12px; font-weight:bold; line-height:12px; color:white;">
                                ' . $alt_mobile . '
                            </div>

                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>

</div>
</body>
</html>
';
// print($html);
// die;

$mpdf->WriteHTML($html);
$mpdf->Output('IDCARD.pdf', 'I');
