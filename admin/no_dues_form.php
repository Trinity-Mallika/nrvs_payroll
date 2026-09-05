

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
<p style="text-align:center;">
    <strong>(Employee Separation / Resignation / Final Settlement)</strong>
</p>
<p><strong>(Employee Separation / Resignation / Final Settlement)</strong></p>

<h3><strong>EMPLOYEE DETAILS</strong></h3>
<table>
    <tr><th><strong>S.NO.</strong></th><th><strong>Employee Name</strong></th><th><strong>Details</strong></th></tr>
    <tr><td>1</td><td>Employee Code</td><td></td></tr>
    <tr><td>2</td><td>Department</td><td></td></tr>
    <tr><td>3</td><td>Designation</td><td></td></tr>
    <tr><td>4</td><td>Date of Joining</td><td></td></tr>
    <tr><td>5</td><td>Last Working Date</td><td></td></tr>
    <tr><td>6</td><td>Reason for Leaving</td><td></td></tr>
    <tr><td>7</td><td>Mobile Number</td><td></td></tr>
</table>

<h3><strong>DEPARTMENTAL NO DUES CLEARANCE</strong></h3>
<table>
    <tr><th><strong>S.No.</strong></th><th><strong>Department</strong></th><th><strong>No Dues (Yes/No)</strong></th><th><strong>Remarks Pending/Cleared</strong></th><th><strong>Authorized Signature</strong></th><th><strong>Clearance Date</strong></th></tr>
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

<h3><strong>COMPANY PROPERTY RETURN DETAILS</strong></h3>
<table>
    <tr><th><strong>S.No.</strong></th><th><strong>Item</strong></th><th><strong>Issued (Yes/No)</strong></th><th><strong>Returned (Yes/No)</strong></th><th><strong>Remarks</strong></th></tr>
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

<h3><strong>RECOVERY DETAILS</strong></h3>
<table>
    <tr><th><strong>S.No.</strong></th><th><strong>Description</strong></th><th><strong>Amount (₹)</strong></th><th><strong>Remarks</strong></th></tr>
    <tr><td>1</td><td>Salary Recovery</td><td></td><td></td></tr>
    <tr><td>2</td><td>Notice Period Recovery</td><td></td><td></td></tr>
    <tr><td>3</td><td>Loan / Advance</td><td></td><td></td></tr>
    <tr><td>4</td><td>Uniform Recovery</td><td></td><td></td></tr>
    <tr><td>5</td><td>Company Property Damage</td><td></td><td></td></tr>
    <tr><td>6</td><td>Other Recovery</td><td></td><td></td></tr>
    <tr><td>7</td><td><strong>Total Recovery</strong></td><td></td><td></td></tr>
</table>

<h3><strong>EMPLOYEE DECLARATION</strong></h3>
<p>I hereby certify that I have returned all company assets, documents, tools, equipment, and other properties belonging to <strong>NRVS Steels Limited</strong>. I confirm that no company property is in my possession. I request the company to process my Full & Final Settlement after verification.</p>
<p>Employee Signature: ___________________<br>Name: ___________________<br>Date: ___________________</p>

<h3><strong>HR VERIFICATION</strong></h3>
<p>☐ All departmental clearances have been obtained.<br>
☐ Company assets have been returned.<br>
☐ Recovery (if any) has been verified.<br>
☐ Employee is eligible for Full & Final Settlement.</p>
<p>HR Executive Signature : ___________________<br>Name : ___________________<br>Date : ___________________</p>

<h3><strong>FINAL APPROVAL</strong></h3>
<table>
    <tr><th><strong>S.NO.</strong></th><th><strong>Approved By</strong></th><th><strong>Signature</strong></th><th><strong>Date</strong></th></tr>
    <tr><td>1</td><td>HOD</td><td></td><td></td></tr>
    <tr><td>2</td><td>HR Head</td><td></td><td></td></tr>
    <tr><td>3</td><td>Accounts Head</td><td></td><td></td></tr>
    <tr><td>4</td><td>Plant Head / Unit Head</td><td></td><td></td></tr>
</table>

<h3><strong>OFFICE USE ONLY</strong></h3>
<p><strong>Employee Status :</strong> □ Cleared □ Pending<br>
<strong>Full & Final Settlement Date :</strong> ______________________<br>
<strong>Relieving Letter Issued:</strong> □ Yes □ No<br>
<strong>Experience Letter Issued:</strong> □ Yes □ No<br>
<strong>Remarks: --------------------------------------------------------------------------------------------</strong></p>

<div class="signature-block">
    <strong>For, NRVS STEELS LIMITED</strong><br><br>
    <strong>Authorized Signatory</strong>
</div>';

 $mpdf->WriteHTML($css . $html);
 $mpdf->Output('No_Dues_Form.pdf', \Mpdf\Output\Destination::INLINE);
?>