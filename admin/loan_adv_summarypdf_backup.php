<?php
include("../adminsession.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 10,
    'margin_right' => 10,
]);

$watermark = __DIR__ . '/img/logo.jpg';

$mpdf->SetWatermarkImage($watermark);

$mpdf->showWatermarkImage = true;

// Opacity (0.1 = light, 1 = dark)
$mpdf->watermarkImageAlpha = 0.1;

$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Advance Summary</title>
    <style>
    body {
    font-family: dejavusans;
}

        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 3px 0px;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        table th,
        table td {
            padding: 2px;
        }

        table.border th,
        table.border td {
            border: 1px solid #000;
            padding: 2px;
        }
            p {
            font-size: 12px;
            }
    </style>
</head>

<body>
    <div>
        <table>
            <tr>
                <td width="18%">
                    <img src="img/logo.jpg" alt="" style="width: 120px;">
                </td>
                <td style="text-align: center;">
                    <h2>AGRAWAL INFRABUILD PRIVATE LIMITED
                        HEAD OFFICE</h2>
                    <p style="font-size:11px;font-weight: bold;">2nd Floor, AIPL Tower, Link Road, C.M.D Chowk, Bilaspur (C.G.) 495001, Loan & Advance Reciept</p>
                </td>
                <td width="18%"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td> Date</td>
                <td> : &nbsp; 14-02-2026</td>
                <td> Voucher No. </td>
                <td> : &nbsp; 9070</td>
            </tr>
            <tr>
                <td> Employee Name</td>
                <td> : &nbsp; MOHIT BEHERA (6070)</td>
                <td> Type</td>
                <td> : &nbsp; Loan</td>
            </tr>
            <tr>
                <td> Date Of Joining</td>
                <td> : &nbsp; 04-04-2023</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td> Department Name</td>
                <td>: &nbsp; ADMINISTRATIVE</td>
                <td>Designation Name</td>
                <td>: &nbsp; EXECUTIVE</td>
            </tr>
            <tr>
                <td>Loan/Advance Amount</td>
                <td>: &nbsp; 200,000.00</td>
                <td> Interest Amt</td>
                <td> : &nbsp; 0</td>
            </tr>
            <tr>
                <td> Installment</td>
                <td> : &nbsp; 21</td>
                <td>Start Month</td>
                <td> : &nbsp; Feb-2026</td>
            </tr>
            <tr>
                <td>Loan Type</td>
                <td>: &nbsp; </td>
                <td> Guarantor (I)</td>
                <td>: &nbsp; </td>
            </tr>
            <tr>
                <td> Purpose of Loan</td>
                <td>: &nbsp; </td>
                <td>Guarantor (II)</td>
                <td>: &nbsp; </td>
            </tr>
        </table>
        <table class="border">
            <tr>
                <th >Sr.no.</th>
                <th>Month Year</th>
                <th>Amount</th>
                <th>Paid Amt</th>
                <th>Set off Amt</th>
                <th>Remarks</th>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>1.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table>
            <tr>
                <td rowspan="6">
                    <p style="font-weight: bold;">Total Amount: 200,000.00</p>
                    <p style="font-weight: bold;"> Total Paid Amount: 0.00</p>
                    <p style="font-weight: bold;">Total Outstanding Balance Amount: 200,000.00</p>
                </td>
                <td style="text-align: right;">Gross Salary</td>
                <td>&nbsp; : &nbsp; 21000</td>
            </tr>
            <tr>
                <td style="text-align: right;"> Bank Account Holder Name</td>
                <td>&nbsp; : &nbsp; MOHIT BEHERA</td>
            </tr>
            <tr>
                <td style="text-align: right;">Account Number</td>
                <td>&nbsp; : &nbsp; 87210100013314</td>
            </tr>
            <tr>
                <td style="text-align: right;">IFSC Code</td>
                <td>&nbsp; : &nbsp; BARB0DBGARH</td>
            </tr>
            <tr>
                <td style="text-align: right;">Bank Branch</td>
                <td>&nbsp; : &nbsp; PHULJHAR</td>
            </tr>
            <tr>
                <td style="text-align: right;">Employee Bank Name</td>
                <td>&nbsp; : &nbsp; BANK OF BARODA</td>
            </tr>
        </table>
        <p style="margin:5px 0px 20px 0px;">Remarks:</p>
        <table>
            <tr>
                <td width="70%">
                    <b> Prepare By</b>
                    <p>PRATIKSHA SONWANI</p>
                    <p>14-Feb-2026 13:56:55</p>

                </td>
                <td>
                    <b>Approved By</b>
                    <br> <br>
                </td>
            </tr>
            <tr>
                <td><b>HR Signature</b></td>
                <td><b>Signature Of Employee</b></td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <td width="70%"><b>Account Signature</b> </td>
                <td>
                    <b>PM Signature</b>
                    <br><br><br>
                    <b>Signature Of Authorized</b>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>';
$mpdf->SetFooter('Print Date : {DATE j-m-Y h:i A} | Page {PAGENO} of {nbpg}');

$mpdf->WriteHTML($html);

$mpdf->Output("On_Duty_Form.pdf", "I");
