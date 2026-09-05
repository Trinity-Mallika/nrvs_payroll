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
<div class="header-right">Ref. No.: NRVS/HR/EXP/2026/01<br>Date: 27-07-2026</div>
<h2>EXPERIENCE LETTER</h2>
<p><strong>TO WHOMSOEVER IT MAY CONCERN</strong></p>
<p>This is to certify that Mr. Shashikamal Miri, S/o Late Shri Radhelal Miri Employee Code: 2213, was employed with NRVS STEELS LIMITED from 10-10-2023 to 27-07-2026 as an HR Officer in the Human Resource Department.</p>
<p>During his tenure with the Company, Mr. Shashikamal Miri performed his duties sincerely and professionally in accordance with the Company\'s policies and procedures.</p>
<p>This Experience Letter is issued at the employee\'s request for employment and official record purposes. It certifies only the employee\'s period of service, designation, and department.</p>
<p>Mr. Shashikamal Miri has been relieved from the services of the Company after completing the necessary exit formalities.</p>
<p>We appreciate his services and wish him every success in his future career.</p>
<div class="signature-block">
    For, NRVS STEELS LIMITED<br><br><br>
    Authorized Signatory<br>
    HR Department<br>
    Name: ____________________________<br>
    Designation: ______________________
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Experience_Letter.pdf', \Mpdf\Output\Destination::INLINE);
?>