<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'format' => [95,148], // ID Card Size
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

if(empty($row)){
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

// =======================
// DEMO DATA
// =======================

// $company_name    = "NRVS STEELS LIMITED";
// $company_address = "TARAIMAL, RAIGARH (C.G.)";

$company_name    = $unit['unit_name'] ?? '';
$company_address = $unit['address'] ?? '';

// $name         = "SHASHIKAMAL MIRI";
// $father       = "LATE RADHELAL MIRI";
// $ecode        = "2213";
// $blood_group  = "HR (PF + ESI)";
// $designation  = "ADMIN";
// $department   = "ADMIN";
// $mobile       = "952782335";
// $alt_mobile   = "9755248462";
// $address      = "RAIPUR, CHHATTISGARH";

$name         = strtoupper($emp['first_name']);
$father       = strtoupper($emp['father_name']);
$ecode        = $emp['emp_code'];
$blood_group  = $emp['blood_group'];
$designation  = strtoupper($emp['designation']);
$department   = strtoupper($emp['department_name']);
$mobile       = $emp['mobile_no'];
$alt_mobile   = $emp['alt_mobile_no'];
$address      = strtoupper($emp['present_address']);

$bg    = __DIR__ . '/img/idcard.png';

$logo  = __DIR__ . '/img/logo1.png';
// $default_logo = __DIR__ . '/img/logo.png';

// $logo = $default_logo;

// if (!empty($unit['logo_image'])) {

//     $unitLogoPath = __DIR__ . '/../management/uploaded/emp_documents/' . $unit['logo_image'];

//     if (file_exists($unitLogoPath)) {
//         $logo = $unitLogoPath;
//     }
// }

// $photo = __DIR__ . '/img/user.jpg';
$photo = !empty($emp['profile_image'])
    ? __DIR__ . '/uploaded/emp_documents/' . $emp['profile_image']
    : __DIR__ . '/img/user.jpg';

// $signature = __DIR__ . '/img/signature.jpg';
$signature = !empty($emp['emp_sign'])
    ? __DIR__ . '/uploaded/emp_documents/' . $emp['emp_sign']
    :"";
 

$call = __DIR__ . '/img/call.png';
$user_pic = __DIR__ . '/img/name.png';
$f_pic = __DIR__ . '/img/f_name.png';
$code_pic = __DIR__ . '/img/code.png';
$blood_pic = __DIR__ . '/img/blood.png';
$position_pic = __DIR__ . '/img/position.png';
$suitcase_pic = __DIR__ . '/img/suitcase.png';
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
    height:148mm;
    background:url('.$bg.');
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
    margin-top:30px;
    margin-left:50px;
    
}
    

.details td{
    font-size:11px;
    padding:2px 0;
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
        <td colspan="3" height="40"></td>
    </tr>

    <!-- COMPANY -->
    <tr>
    <td colspan="3" align="center">

        <table width="100%" style="text-align:center;">
            <tr>

                <td width="20%" style="text-align:center;">
                    <img src="'.$logo.'" width="70">
                </td>

                <td  >
                    <div class="company">
                        '.$company_name.'
                    </div>

                    <div class="address">
                        '.$company_address.'
                    </div>
                </td>

            </tr>
        </table>

    </td>
</tr>

    <!-- SPACE BEFORE PHOTO -->
      <tr>
        <td colspan="3" height="0"></td>
    </tr>

    <!-- PHOTO -->
    <tr>
    <td colspan="3" align="center">

        <table style="
            width:40mm;
            height:40mm;
            border-collapse:collapse;
            margin-right:-1.5mm;
             
        ">
            <tr>
                <td style="
                    width:45mm;
                    height:45mm;
                    text-align:center;
                    vertical-align:middle;
                ">
                     '.(!empty($photo) ? '<img src="'.$photo.'" width="40mm" height="38mm">' : '').'
                </td>
            </tr>
        </table>

    </td>
</tr>

    <!-- NAME STRIP SPACE -->
    <tr>
        <td colspan="3" height="6"></td>
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
            '.$name.'
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
                  
                    <td class="label"> <img src="'.$user_pic.'" width="12" height="12">
        &nbsp;NAME</td>
                    <td class="colon">:</td>
                    <td class="value">'.$name.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$f_pic.'" width="12" height="12">
        &nbsp;F/H NAME</td>
                    <td class="colon">:</td>
                    <td class="value">'.$father.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$code_pic.'" width="12" height="12">
        &nbsp;E.CODE</td>
                    <td class="colon">:</td>
                    <td class="value">'.$ecode.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$blood_pic.'"width="10" height="10"> &nbsp;BLOOD GROUP</td>
                    <td class="colon">:</td>
                    <td class="value">'.$blood_group.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$position_pic.'" width="12" height="12">
        &nbsp;DESIGNATION</td>
                    <td class="colon">:</td>
                    <td class="value">'.$designation.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$suitcase_pic.'" width="12" height="12">
        &nbsp;DEPARTMENT</td>
                    <td class="colon">:</td>
                    <td class="value">'.$department.'</td>
                </tr>

                 

            </table>

        </td>

        <td width="10"></td>

    </tr>

    <!-- SIGNATURE SPACE -->
    <tr>
        <td colspan="3" height="6"></td>
    </tr>
    

    <!-- SIGNATURE -->
    <tr>
    <td colspan="3" align="center">

      '.(!empty($signature) ? '<img src="'.$signature.'" width="32mm">' : '').'

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
    bottom:1mm;
    left:0;
    width:100%;
    color:#FFFFFF;
">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>

            <!-- MOBILE -->
            <td width="50%" align="center">

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <img src="'.$call.'" width="16" style="vertical-align:middle;">
                        </td>

                        <td style="padding-left:4px; text-align:left;">

                            <div style="font-size:10px; line-height:8px;color:white;">
                                MOBILE
                            </div>

                            <div style="font-size:12px; font-weight:bold; line-height:12px;color:white;">
                                '.$mobile.'
                            </div>

                        </td>
                    </tr>
                </table>

            </td>

            <!-- DIVIDER -->
            <td width="4%" align="center">
                <div style="height:10mm; border-left:1px solid #FFFFFF;"></div>
            </td>

            <!-- ALT -->
            <td width="20%" align="center">

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <img src="'.$call.'" width="16" style="vertical-align:middle;">
                        </td>

                        <td style="padding-left:4px; text-align:left;">

                            <div style="font-size:8px; line-height:10px;color:white;">
                                EMERGENCY/HOME
                            </div>

                            <div style="font-size:12px; font-weight:bold; line-height:12px; color:white;">
                                '.$alt_mobile.'
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

$mpdf->WriteHTML($html);
$mpdf->Output('IDCARD.pdf', 'I');
?>