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
<div class="header-right">Ref. No.: NRVS/HR/RL/JULY-2026/01<br>Date: 29-07-2026</div>
<h2>RELIEVING LETTER</h2>
<p><strong>TO WHOMSOEVER IT MAY CONCERN</strong></p>
<p>This is to certify that Mr. Shashikamal Miri, S/o Late Shri Radhelal Miri, was employed with NRVS STEELS LIMITED from 10-10-2023 to 31-07-2026 as an HR Officer in the Human Resource Department.</p>
<p>Mr. Shashikamal Miri has been relieved from the services of the Company with effect from the close of business hours on 31-07-2026, pursuant to the acceptance of his resignation. During his tenure, he discharged his duties with sincerity, dedication, and professionalism.</p>
<p>It is confirmed that he has completed the required exit formalities and handed over the responsibilities assigned to him to the satisfaction of the Management. This relieving letter is issued upon his separation from the Company.</p>
<p>We sincerely appreciate his contribution to the organization and wish him success, good health, and prosperity in all his future endeavours.</p>
<div class="signature-block">
    For, NRVS STEELS LIMITED<br><br><br>
    Authorized Signatory<br>
    HR Department<br>
    Name: ____________________________<br>
    Designation: ______________________
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Relieving_Letter.pdf', \Mpdf\Output\Destination::INLINE);
?>