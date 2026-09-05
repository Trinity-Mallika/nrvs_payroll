<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';

 $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
 $docType = isset($_GET['doc']) ? $_GET['doc'] : '';

// Common CSS for all documents
 $css = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; color: #000; }
    h2 { text-align: center; text-decoration: underline; margin-bottom: 20px; font-size: 16pt; }
    .header-right { text-align: right; margin-bottom: 20px; }
    .header-left { text-align: left; margin-bottom: 20px; }
    p { text-align: justify; margin-bottom: 10px; }
    .signature-block { margin-top: 40px; text-align: left; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11pt; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .center { text-align: center; }
    ul { margin-top: 0; }
</style>';

 $html = '';

switch ($docType) {
    case 'character':
        $html = '<div class="header-right">Ref. No.: NRVS/HR/CHA/JULY-2026/01<br>Date: 29-07-2026</div>
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
        $filename = 'Character_Certificate.pdf';
        break;

    case 'current_employment':
        $html = '<div class="header-right">Ref. No.: NRVS/HR/CEC/JULY-2026/01<br>Date: 29-07-2026</div>
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
        $filename = 'Current_Employment_Certificate.pdf';
        break;

    case 'experience':
        $html = '<div class="header-right">Ref. No.: NRVS/HR/EXP/2026/01<br>Date: 27-07-2026</div>
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
        $filename = 'Experience_Letter.pdf';
        break;

    case 'no_dues':
        $html = '<h2>NRVS STEELS LIMITED<br>NO DUES CLEARANCE FORM</h2>
        <p><em>(Employee Separation / Resignation / Final Settlement)</em></p>
        
        <h3>EMPLOYEE DETAILS</h3>
        <table>
            <tr><th>S.NO.</th><th>Employee Name</th><th>Details</th></tr>
            <tr><td>1</td><td>Employee Code</td><td></td></tr>
            <tr><td>2</td><td>Department</td><td></td></tr>
            <tr><td>3</td><td>Designation</td><td></td></tr>
            <tr><td>4</td><td>Date of Joining</td><td></td></tr>
            <tr><td>5</td><td>Last Working Date</td><td></td></tr>
            <tr><td>6</td><td>Reason for Leaving</td><td></td></tr>
            <tr><td>7</td><td>Mobile Number</td><td></td></tr>
        </table>

        <h3>DEPARTMENTAL NO DUES CLEARANCE</h3>
        <table>
            <tr><th>S.No.</th><th>Department</th><th>No Dues (Yes/No)</th><th>Remarks Pending/Cleared</th><th>Authorized Signature</th><th>Clearance Date</th></tr>
            <tr><td>1</td><td>HR Department</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>2</td><td>IT Department</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>3</td><td>Safety Department</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>4</td><td>Purchase & Store Department</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>6</td><td>Reporting/Department HEAD/HOD</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>7</td><td>Finance & Accounts Department</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>8</td><td>Dispatch Department</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>9</td><td>Canteen</td><td></td><td></td><td></td><td></td></tr>
            <tr><td>10</td><td>Security Department</td><td></td><td></td><td></td><td></td></tr>
        </table>

        <h3>COMPANY PROPERTY RETURN DETAILS</h3>
        <table>
            <tr><th>S.No.</th><th>Item</th><th>Issued (Yes/No)</th><th>Returned (Yes/No)</th><th>Remarks</th></tr>
            <tr><td>1</td><td>ID Card</td><td></td><td></td><td></td></tr>
            <tr><td>2</td><td>Laptop/Desktop</td><td></td><td></td><td></td></tr>
            <tr><td>3</td><td>Mobile Phone</td><td></td><td></td><td></td></tr>
            <tr><td>4</td><td>SIM Card</td><td></td><td></td><td></td></tr>
            <tr><td>5</td><td>Uniform</td><td></td><td></td><td></td></tr>
            <tr><td>6</td><td>Charger</td><td></td><td></td><td></td></tr>
            <tr><td>7</td><td>Goggles</td><td></td><td></td><td></td></tr>
            <tr><td>8</td><td>Tools / Equipment</td><td></td><td></td><td></td></tr>
            <tr><td>9</td><td>Safety Helmet</td><td></td><td></td><td></td></tr>
            <tr><td>10</td><td>Safety Shoes</td><td></td><td></td><td></td></tr>
            <tr><td>11</td><td>Hand Gloves</td><td></td><td></td><td></td></tr>
            <tr><td>12</td><td>Other</td><td></td><td></td><td></td></tr>
        </table>

        <h3>RECOVERY DETAILS</h3>
        <table>
            <tr><th>S.No.</th><th>Description</th><th>Amount (₹)</th><th>Remarks</th></tr>
            <tr><td>1</td><td>Salary Recovery</td><td></td><td></td></tr>
            <tr><td>2</td><td>Notice Period Recovery</td><td></td><td></td></tr>
            <tr><td>3</td><td>Loan / Advance</td><td></td><td></td></tr>
            <tr><td>4</td><td>Uniform Recovery</td><td></td><td></td></tr>
            <tr><td>5</td><td>Company Property Damage</td><td></td><td></td></tr>
            <tr><td>6</td><td>Other Recovery</td><td></td><td></td></tr>
            <tr><td>7</td><td><strong>Total Recovery</strong></td><td></td><td></td></tr>
        </table>

        <h3>EMPLOYEE DECLARATION</h3>
        <p>I hereby certify that I have returned all company assets, documents, tools, equipment, and other properties belonging to NRVS Steels Limited. I confirm that no company property is in my possession. I request the company to process my Full & Final Settlement after verification.</p>
        <p>Employee Signature: ___________________<br>Name: ___________________<br>Date: ___________________</p>

        <h3>HR VERIFICATION</h3>
        <p>☐ All departmental clearances have been obtained.<br>
        ☐ Company assets have been returned.<br>
        ☐ Recovery (if any) has been verified.<br>
        ☐ Employee is eligible for Full & Final Settlement.</p>
        <p>HR Executive Signature : ___________________<br>Name : ___________________<br>Date : ___________________</p>

        <h3>FINAL APPROVAL</h3>
        <table>
            <tr><th>S.NO.</th><th>Approved By</th><th>Signature</th><th>Date</th></tr>
            <tr><td>1</td><td>HOD</td><td></td><td></td></tr>
            <tr><td>2</td><td>HR Head</td><td></td><td></td></tr>
            <tr><td>3</td><td>Accounts Head</td><td></td><td></td></tr>
            <tr><td>4</td><td>Plant Head / Unit Head</td><td></td><td></td></tr>
        </table>

        <h3>OFFICE USE ONLY</h3>
        <p>Employee Status : □ Cleared □ Pending<br>
        Full & Final Settlement Date : ______________________<br>
        Relieving Letter Issued: □ Yes □ No<br>
        Experience Letter Issued: □ Yes □ No<br>
        Remarks: --------------------------------------------------------------------------------------------</p>
        
        <div class="signature-block">
            For, NRVS STEELS LIMITED<br><br>
            Authorized Signatory
        </div>';
        $filename = 'No_Dues_Form.pdf';
        break;

    case 'intent':
        $html = '<div class="header-right">Ref. No.: NRVS/HR/LI/JULY-2026/01<br>Date: 27-07-2026</div>
        <p>Mr. Sanju Dey S/o Swapan Dey<br>
        Vill-…………………..<br>
        Dist-………………………<br>
        Mob-……………………………<br>
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
        $filename = 'Intent_Letter.pdf';
        break;

    case 'relieving':
        $html = '<div class="header-right">Ref. No.: NRVS/HR/RL/JULY-2026/01<br>Date: 29-07-2026</div>
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
        $filename = 'Relieving_Letter.pdf';
        break;

    case 'resignation_acceptance':
        $html = '<h2>RESIGNATION ACCEPTANCE LETTER</h2>
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
        $filename = 'Resignation_Acceptance.pdf';
        break;

    default:
        // Display a selection menu if no document is requested
        echo '<h2>Select Document to Generate PDF</h2>';
        echo '<ul>
            <li><a href="?doc=character">Character Certificate</a></li>
            <li><a href="?doc=current_employment">Current Employment Certificate</a></li>
            <li><a href="?doc=experience">Experience Letter</a></li>
            <li><a href="?doc=no_dues">No Dues Form</a></li>
            <li><a href="?doc=intent">Intent Letter</a></li>
            <li><a href="?doc=relieving">Relieving Letter</a></li>
            <li><a href="?doc=resignation_acceptance">Resignation Acceptance Letter</a></li>
        </ul>';
        exit;
}

// Write HTML to mPDF and output
 $mpdf->WriteHTML($css . $html);
 $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
?>