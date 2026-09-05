<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';

 $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

 $css = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; color: #000; }
    h2 { text-align: center; text-decoration: underline; margin-bottom: 20px; font-size: 16pt; }
    p { text-align: justify; margin-bottom: 10px; }
    .signature-block { margin-top: 40px; text-align: left; }
</style>';

 $html = '
<h2>RESIGNATION ACCEPTANCE LETTER</h2>
<p>To,<br>
Mr. Shashikamal Miri<br>
Employee Code: 2213<br>
HR Officer<br>
Human Resource Department</p>
<p><strong>Subject: Acceptance of Resignation.</strong></p>
<p>Dear Mr. Shashikamal Miri,</p>
<p>This is with reference to your resignation submitted to NRVS STEELS LIMITED. We hereby accept your resignation, and your last working day will be 31-07-2026.</p>
<p>You have served the Company from 10-10-2023 to 31-07-2026 as HR Officer in the Human Resource Department. You are requested to complete the necessary handover and exit formalities as per Company policy.</p>
<p>The Management appreciates your valuable contribution and services rendered during your tenure with the Company.</p>
<p>We wish you good health, success, and prosperity in your future endeavours.</p>
<div class="signature-block">
    For, NRVS STEELS LIMITED<br><br><br>
    Authorized Signatory<br>
    HR Department<br>
    Name: ____________________________<br>
    Designation: ______________________
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Resignation_Acceptance.pdf', \Mpdf\Output\Destination::INLINE);
?>