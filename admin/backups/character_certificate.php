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
<div class="header-right">Ref. No.: NRVS/HR/CHA/JULY-2026/01<br>Date: 29-07-2026</div>
<h2>CHARACTER CERTIFICATE</h2>
<p><strong>TO WHOMSOEVER IT MAY CONCERN</strong></p>
<p>This is to certify that Mr. Shashikamal Miri, S/o Late Shri Radhelal Miri, Employee Code 2213, was employed with NRVS STEELS LIMITED from 10-11-2023 to 31-07-2026 as HR Officer in the Human Resource Department.</p>
<p>During the period of employment, the employee maintained good conduct, discipline, honesty, sincerity, and professional behaviour. The employee performed assigned duties responsibly and maintained cordial relations with colleagues and management.</p>
<p>To the best of our knowledge, his/her character and moral conduct were found to be Good, and no adverse record regarding integrity or behaviour was observed during the tenure of employment.</p>
<p>This certificate is being issued on the request of the employee for whatever purpose it may serve.</p>
<p>We wish him/her every success and prosperity in future.</p>
<div class="signature-block">
    For, NRVS STEELS LIMITED<br><br><br>
    Authorized Signatory<br>
    HR Department<br>
    Name: ____________________________<br>
    Designation: ______________________
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Character_Certificate.pdf', \Mpdf\Output\Destination::INLINE);
?>