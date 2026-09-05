<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'format'        => [95, 148],
    'margin_left'   => 0,
    'margin_right'  => 0,
    'margin_top'    => 0,
    'margin_bottom' => 0,
]);

// =======================
// FETCH Employee DATA
// =======================
$emp_id = intval($_GET['emp_id']);

$sql = "
SELECT e.*, d.department_name, dg.designation
FROM employee_master e
LEFT JOIN department_master d   ON d.department_id   = e.department_id
LEFT JOIN designation_master dg ON dg.designation_id = e.designation_id
WHERE e.emp_id = '$emp_id'
";

$row = $obj->executequery($sql);
if (empty($row)) { die("Employee Not Found"); }
$emp = $row[0];

// =======================
// COMPANY DATA
// =======================
$unit = $obj->select_record("unit_master", ["unit_id" => $emp['unit_id']]);

$company_name    = htmlspecialchars($unit['unit_name'] ?? '');
$company_address = htmlspecialchars($unit['address']   ?? '');

// =======================
// EMPLOYEE DATA
// =======================
$name        = htmlspecialchars(strtoupper($emp['first_name']));
$father      = htmlspecialchars(strtoupper($emp['father_name']));
$ecode       = htmlspecialchars($emp['emp_code']);
$blood_group = htmlspecialchars($emp['blood_group']);
$designation = htmlspecialchars(strtoupper($emp['designation']));
$department  = htmlspecialchars(strtoupper($emp['department_name']));
$mobile      = htmlspecialchars($emp['mobile_no']);
$alt_mobile  = htmlspecialchars($emp['alt_mobile_no']);

// =======================
// IMAGE PATHS
// =======================
$logo      = 'img/logo1.png';
$photo     = !empty($emp['profile_image'])
    ? 'uploaded/emp_documents/' . $emp['profile_image']
    : 'img/user.jpg';
$signature = !empty($emp['emp_sign'])
    ? 'uploaded/emp_documents/' . $emp['emp_sign']
    : 'img/signature.jpg';

$icon_user     = 'img/name.png';
$icon_father   = 'img/f_name.png';
$icon_code     = 'img/code.png';
$icon_blood    = 'img/blood.png';
$icon_position = 'img/position.png';
$icon_dept     = 'img/suitcase.png';
$icon_call     = 'img/call.png';

// Helper function for icon img tag
function icon_img($path, $size = 13) {
    return '<img src="' . $path . '" width="' . $size . '" height="' . $size . '">';
}

// =======================
// HTML
// =======================
$html = '
<!DOCTYPE html>
<html>
<head>
<style>
* { margin:0; padding:0; }

body {
    width: 95mm;
    height: 148mm;
    overflow: hidden;
    position: relative;
    background: #ffffff;
}

.card {
    width: 95mm;
    height: 148mm;
    position: relative;
    background: #ffffff;
    overflow: hidden;
}

/* Orange top section */
.top-bg {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 60mm;
    background: #FF4500;
    border-radius: 0 0 50% 50% / 0 0 20mm 20mm;
    z-index: 0;
}

/* Lanyard hole */
.lanyard-hole {
    position: absolute;
    top: 2.5mm;
    left: 35mm;
    width: 25mm;
    height: 6mm;
    background: #ffffff;
    border-radius: 3mm;
    border: 0.4mm solid #cccccc;
    z-index: 10;
}

/* Left navy bar */
.deco-left {
    position: absolute;
    top: 14mm;
    left: -1mm;
    width: 16mm;
    height: 7mm;
    background: #072d67;
    z-index: 2;
    transform: skewY(-22deg);
}

/* Left orange bar */
.deco-left2 {
    position: absolute;
    top: 20mm;
    left: -1mm;
    width: 12mm;
    height: 5mm;
    background: #cc3700;
    z-index: 2;
    transform: skewY(-22deg);
}

/* Right navy bar */
.deco-right {
    position: absolute;
    top: 14mm;
    right: -1mm;
    width: 16mm;
    height: 7mm;
    background: #072d67;
    z-index: 2;
    transform: skewY(22deg);
}

/* Right orange bar */
.deco-right2 {
    position: absolute;
    top: 20mm;
    right: -1mm;
    width: 12mm;
    height: 5mm;
    background: #cc3700;
    z-index: 2;
    transform: skewY(22deg);
}

/* Header */
.header {
    position: absolute;
    top: 9mm;
    left: 14mm;
    right: 14mm;
    z-index: 5;
}

.company-name {
    font-size: 13px;
    font-weight: bold;
    color: #072d67;
    letter-spacing: 0.3px;
}

.company-divider {
    height: 0.6mm;
    background: #FF4500;
    margin: 1mm 0;
}

.company-address {
    font-size: 7px;
    color: #333;
    font-weight: 600;
    letter-spacing: 0.2px;
}

/* Photo */
.photo-wrap {
    position: absolute;
    top: 27mm;
    left: 28mm;
    width: 39mm;
    height: 39mm;
    border-radius: 50%;
    border: 2.5mm solid #FF4500;
    overflow: hidden;
    background: #eeeeee;
    z-index: 5;
}

/* Name banner */
.name-banner {
    position: absolute;
    top: 68.5mm;
    left: 0; right: 0;
    height: 9mm;
    background: #FF4500;
    z-index: 4;
    text-align: center;
    line-height: 9mm;
}

.name-banner-text {
    font-size: 13.5px;
    font-weight: bold;
    color: #ffffff;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}

/* Navy triangle left on banner */
.banner-nav-left {
    position: absolute;
    top: 68.5mm;
    left: 0;
    width: 0; height: 0;
    border-top: 4.5mm solid transparent;
    border-bottom: 4.5mm solid transparent;
    border-left: 8mm solid #072d67;
    z-index: 6;
}

/* Navy triangle right on banner */
.banner-nav-right {
    position: absolute;
    top: 68.5mm;
    right: 0;
    width: 0; height: 0;
    border-top: 4.5mm solid transparent;
    border-bottom: 4.5mm solid transparent;
    border-right: 8mm solid #072d67;
    z-index: 6;
}

/* Details table */
.details-wrap {
    position: absolute;
    top: 79mm;
    left: 3mm;
    right: 3mm;
    z-index: 5;
}

.det {
    width: 100%;
    border-collapse: collapse;
}

.det td {
    font-size: 7.5px;
    padding: 1.6mm 1mm;
    vertical-align: middle;
    border-bottom: 0.3px solid #eeeeee;
}

.td-icon { width: 7mm; text-align: center; }
.td-label { width: 21mm; font-weight: bold; color: #222; }
.td-colon { width: 3mm; font-weight: bold; text-align: center; }
.td-val { font-weight: bold; color: #111; }

/* Signature */
.sig-wrap {
    position: absolute;
    top: 120mm;
    left: 0; right: 0;
    text-align: center;
    z-index: 5;
}

.sig-label {
    font-size: 6px;
    font-weight: bold;
    color: #444;
    letter-spacing: 0.5px;
    margin-top: 0.5mm;
}

/* Footer */
.footer {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 17mm;
    background: #FF4500;
    z-index: 5;
}

.footer table { height: 17mm; }

.ft-label {
    font-size: 7px;
    color: rgba(255,255,255,0.85);
    letter-spacing: 0.4px;
}

.ft-num {
    font-size: 11.5px;
    font-weight: bold;
    color: #ffffff;
    letter-spacing: 0.2px;
}

.ft-divider {
    height: 10mm;
    border-left: 0.4mm solid rgba(255,255,255,0.5);
}
</style>
</head>
<body>
<div class="card">

    <div class="top-bg"></div>
    <div class="lanyard-hole"></div>

    <div class="deco-left"></div>
    <div class="deco-left2"></div>
    <div class="deco-right"></div>
    <div class="deco-right2"></div>

    <!-- HEADER -->
    <div class="header">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="19mm" style="vertical-align:middle; text-align:left;">
                    <img src="' . $logo . '" width="17mm">
                </td>
                <td style="vertical-align:middle; padding-left:2mm;">
                    <div class="company-name">' . $company_name . '</div>
                    <div class="company-divider"></div>
                    <div class="company-address">' . $company_address . '</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- PHOTO -->
    <div class="photo-wrap">
        <img src="' . $photo . '" width="34mm" height="34mm">
    </div>

    <!-- NAME BANNER -->
    <div class="banner-nav-left"></div>
    <div class="banner-nav-right"></div>
    <div class="name-banner">
        <span class="name-banner-text">' . $name . '</span>
    </div>

    <!-- DETAILS -->
    <div class="details-wrap">
        <table class="det" cellpadding="0" cellspacing="0">
            <tr>
                <td class="td-icon">' . icon_img($icon_user) . '</td>
                <td class="td-label">NAME</td>
                <td class="td-colon">:</td>
                <td class="td-val">' . $name . '</td>
            </tr>
            <tr>
                <td class="td-icon">' . icon_img($icon_father) . '</td>
                <td class="td-label">F/H NAME</td>
                <td class="td-colon">:</td>
                <td class="td-val">' . $father . '</td>
            </tr>
            <tr>
                <td class="td-icon">' . icon_img($icon_code) . '</td>
                <td class="td-label">E.CODE</td>
                <td class="td-colon">:</td>
                <td class="td-val">' . $ecode . '</td>
            </tr>
            <tr>
                <td class="td-icon">' . icon_img($icon_blood) . '</td>
                <td class="td-label">BLOOD GROUP</td>
                <td class="td-colon">:</td>
                <td class="td-val">' . $blood_group . '</td>
            </tr>
            <tr>
                <td class="td-icon">' . icon_img($icon_position) . '</td>
                <td class="td-label">DESIGNATION</td>
                <td class="td-colon">:</td>
                <td class="td-val">' . $designation . '</td>
            </tr>
            <tr>
                <td class="td-icon">' . icon_img($icon_dept) . '</td>
                <td class="td-label">DEPARTMENT</td>
                <td class="td-colon">:</td>
                <td class="td-val">' . $department . '</td>
            </tr>
        </table>
    </div>

    <!-- SIGNATURE -->
    <div class="sig-wrap">
        <img src="' . $signature . '" height="8mm">
        <div class="sig-label">EMPLOYEE SIGNATURE</div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <table width="100%" cellpadding="0" cellspacing="0" style="height:17mm;">
            <tr>
                <!-- MOBILE -->
                <td width="49%" align="center" valign="middle">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding-right:2mm; vertical-align:middle;">
                                <img src="' . $icon_call . '" width="14" height="14">
                            </td>
                            <td style="vertical-align:middle; text-align:left;">
                                <div class="ft-label">MOBILE</div>
                                <div class="ft-num">' . $mobile . '</div>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- DIVIDER -->
                <td width="2%" align="center" valign="middle">
                    <div class="ft-divider"></div>
                </td>

                <!-- ALT MOBILE -->
                <td width="49%" align="center" valign="middle">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="padding-right:2mm; vertical-align:middle;">
                                <img src="' . $icon_call . '" width="14" height="14">
                            </td>
                            <td style="vertical-align:middle; text-align:left;">
                                <div class="ft-label">ALTER./HOME</div>
                                <div class="ft-num">' . $alt_mobile . '</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
';
print_r($html);die;
$mpdf->WriteHTML($html);
$mpdf->Output('IDCARD.pdf', 'I');
?>