<?php include("../adminsession.php");

require_once __DIR__ . '/mpdf/vendor/autoload.php';
$unit_data = $obj->select_record("unit_master", ['unit_id' => $unitid]);
if(!empty($unit_data)){
    $unit_name = $unit_data['unit_name'];
     $address = $unit_data['address'];
    $work_address = $unit_data['work_address'];
    $gstin_no = $unit_data['gstin_no'];
    $cin_no = $unit_data['cin_no'];
    $logo_image = $unit_data['logo_image'];
    $email_id = $unit_data['email_id'];
     
}else{
    $unit_name = '';
    $address = "";
    $work_address = '';
    $gstin_no = '';
    $cin_no = '';
    $logo_image = '';
    $email_id = '';
    }
    $logoPath = __DIR__ . '/img/logo1.png';
 $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

 $css = '
<style>
    body { font-family: times; font-size: 11pt; line-height: 1.5; color: #000; }

       .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px;
    }
    
    .header-table td {
        vertical-align: top;
        padding: 0;
    }

    .logo-td {
        width: 15%;
    }

    .company-title-td {
        width: 63%;
        text-align: center;
    }

    .company-title {
        font-size: 22pt;
        font-weight: bold;
        color: #0b1a40;
        margin: 0;
        padding: 0;
        letter-spacing: 1px;
    }

    .sub-title {
        font-size: 9pt;
        color: #333;
        margin-top: 2px;
    }

    .address-info {
        font-size: 8.5pt;
        color: #333;
        margin-top: 3px;
        line-height: 1.2;
    }

    .meta-td {
        width: 22%;
        text-align: right;
        font-size: 6.5pt;
        color: #333;
        line-height: 1.3;
        font-weight: bold;
    }

    .banner-bar {
        border-top: 1px solid #0b1a40;
        border-bottom: 1px solid #0b1a40;
        text-align: center;
        padding: 2px 0;
        margin-top: 5px;
        margin-bottom: 25px;
        font-size: 9pt;
        font-weight: bold;
        color: #0b1a40;
        letter-spacing: 1px;
    }

    .header-left { text-align: left; margin-bottom: 15px; }
    p { text-align: justify; margin-bottom: 10px; }
    .signature-block { margin-top: 40px; text-align: left; }
    ul { margin-top: 0; }
</style>';

 $html = '
<table class="header-table">
    <tr>
        <!-- Left Logo -->
        <td class="logo-td">
            <img src="' . $logoPath . '" width="80">
        </td>

        <!-- Middle Header Details -->
        <td class="company-title-td">
            <div class="company-title">' . $unit_name . '</div>
            <div class="sub-title">(Formerly Known as Seleno Steels Limited)</div>
            <div class="address-info">
                Regd. Office : ' . $address . '<br>
                Works : ' . $work_address . ',<br> E-mail : ' . $email_id . '
            </div>
        </td>

        <!-- Right Side CIN & GSTIN -->
        <td class="meta-td">
            CIN : ' . $cin_no . '<br>
            GSTIN : ' . $gstin_no . '
        </td>
    </tr>
</table>
<div class="banner-bar">
    MANUFACTURERS OF IRON AND STEEL
</div>
<div class="header-left"><strong>Ref. No.: NRVS/HR/CEC/JULY-2026/01</strong><br><strong>Date: 29-07-2026</strong></div>
<h2><strong>CURRENT EMPLOYMENT CERTIFICATE</strong></h2>
<p class="sub-title"><strong>TO WHOMSOEVER IT MAY CONCERN</strong></p>
<p>This is to certify that <strong>Mr. Shashikamal Miri, S/o Late Shri Radhelal Miri</strong>, Employee Code <strong>2213</strong>, is currently employed with <strong>NRVS STEELS LIMITED</strong> since <strong>10-10-2023</strong>.</p>
<p>He is presently working as <strong>HR Officer</strong> in the <strong>Human Resource Department</strong>.</p>
<p>During his employment, he has demonstrated sincerity, dedication, discipline, and professionalism in the discharge of his duties. His performance and conduct have been found satisfactory, and he has maintained good professional relationships with colleagues and management.</p>
<p>This certificate is issued at the request of the employee for whatever purpose it may serve.</p>
<div class="signature-block">
    <strong>For, NRVS STEELS LIMITED</strong><br><br><br>
    <strong>Authorized Signatory</strong><br>
    <strong>HR Department</strong><br>
    <strong>Name:</strong> ____________________________<br>
    <strong>Designation:</strong> ______________________
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Current_Employment_Certificate.pdf', \Mpdf\Output\Destination::INLINE);
?>