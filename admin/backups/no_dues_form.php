<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';

 $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

 $css = '
<style>
    body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.5; color: #000; }
    h2 { text-align: center; text-decoration: underline; margin-bottom: 10px; font-size: 14pt; }
    h3 { font-size: 12pt; margin-bottom: 5px; margin-top: 15px; }
    p { text-align: justify; margin-bottom: 10px; }
    .signature-block { margin-top: 30px; text-align: left; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10pt; }
    th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>';

 $html = '
<h2>NRVS STEELS LIMITED<br>NO DUES CLEARANCE FORM</h2>
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

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('No_Dues_Form.pdf', \Mpdf\Output\Destination::INLINE);
?>