<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';

 $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

 $css = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; color: #000; }
    h2 { text-align: center; text-decoration: underline; margin-bottom: 20px; font-size: 16pt; }
    .header-right { text-align: right; margin-bottom: 20px; }
    p { text-align: justify; margin-bottom: 10px; }
    .signature-block { margin-top: 40px; text-align: left; }
</style>';

 $html = '
<div class="header-right">Ref. No.: NRVS/HR/CEC/JULY-2026/01<br>Date: 29-07-2026</div>
<h2>CURRENT EMPLOYMENT CERTIFICATE</h2>
<p><strong>TO WHOMSOEVER IT MAY CONCERN</strong></p>
<p>This is to certify that Mr. Shashikamal Miri, S/o Late Shri Radhelal Miri, Employee Code 2213, is currently employed with NRVS STEELS LIMITED since 10-10-2023.</p>
<p>He is presently working as HR Officer in the Human Resource Department.</p>
<p>During his employment, he has demonstrated sincerity, dedication, discipline, and professionalism in the discharge of his duties. His performance and conduct have been found satisfactory, and he has maintained good professional relationships with colleagues and management.</p>
<p>This certificate is issued at the request of the employee for whatever purpose it may serve.</p>
<div class="signature-block">
    For, NRVS STEELS LIMITED<br><br><br>
    Authorized Signatory<br>
    HR Department<br>
    Name: ____________________________<br>
    Designation: ______________________
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Current_Employment_Certificate.pdf', \Mpdf\Output\Destination::INLINE);
?>