<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';

 $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

 $css = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; color: #000; }
    .header-right { text-align: right; margin-bottom: 20px; }
    p { text-align: justify; margin-bottom: 10px; }
    .signature-block { margin-top: 40px; text-align: left; }
    ul { margin-top: 0; }
</style>';

 $html = '
<div class="header-right">Ref. No.: NRVS/HR/LI/JULY-2026/01<br>Date: 27-07-2026</div>
<p>Mr. Sanju Dey S/o Swapan Dey<br>
Vill-………………..<br>
Dist-……………………<br>
Mob-………………………<br>
Email: sanjudey786@gmail.com</p>
<p><strong>Subject: Letter of Intent.</strong></p>
<p>Dear Sir,</p>
<p>With reference to your application and the subsequent interview held with us, we are pleased to inform you that you have been selected for the post of Manager – Production in our organisation. Accordingly, you will join duty on 09th September 2024 at our company:</p>
<p><strong>NRVS STEELS LIMITED, Raigarh (C.G.).</strong></p>
<p>Please note that your joining will be treated as cancelled if you do not join duty on 09th September 2024.</p>
<p>Please bring the following documents at the time of your joining and submit the same to the HR Department:</p>
<ul>
    <li>Relieving letter of the present employment</li>
    <li>Salary slips of the present employment</li>
    <li>Photocopy of educational certificates and experience certificate</li>
    <li>Photocopy of Aadhaar, PAN and Voter ID card along with 2 colour passport-size photographs</li>
</ul>
<p>With best wishes,</p>
<div class="signature-block">
    For, NRVS STEELS LIMITED<br><br><br>
    Managing Director
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('Intent_Letter.pdf', \Mpdf\Output\Destination::INLINE);
?>