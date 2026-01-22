<?php
include("../adminsession.php");

require_once __DIR__ . '../mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 10,
    'margin_bottom' => 5,
    'margin_left' => 7,
    'margin_right' => 7,
]);

$pageHeight = 297;
$headerHeight = 40;
$footerHeight = 40;

$tblname = "employee_master";
$tblpkey = "emp_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$fields = [
    'emp_code',
    'first_name',
    'last_name',
    'father_name',
    'gender',
    'dob',
    'age',
    'blood_group',
    'marital_status',
    'nationality',
    'religion',
    'caste',
    'mobile_no',
    'alt_mobile_no',
    'email_id',
    'present_address',
    'permanent_address',
    'emer_contact_name',
    'emer_contact_relation',
    'emer_contact_no',
    'aadhar_no',
    'pan_no',
    'driving_license',
    'passport_no',
    'identification_masks',
    'department_id',
    'designation_id',
    'grade_id',
    'date_of_joining',
    'job_location',
    'shift_id',
    'employee_type',
    'employer_name',
    'employer_designation_id',
    'service_from',
    'service_to',
    'reason',
    'job_responsibility',
    'reporting_manager',
    'basic_salary',
    'hra',
    'da',
    'conveyance',
    'medical_allowance',
    'special_allowance',
    'is_pf',
    'is_esic',
    'is_pt',
    'is_lwf',
    'ctc',
    'gross_salary',
    'net_salary',
    'bank_name',
    'acc_holder_name',
    'account_no',
    'ifsc_code',
    'pf_uan',
    'esic_no',
    'pf_joining_date',
    'esic_joining_date',
    'status',
    'document_checked_ids',
    'last_salary',
    'createdby',
    'ipaddress',
    'createdate',
    'lastupdated',
    'unit_id',
    'sessionid'
];
if (isset($_GET[$tblpkey])) {
    $where = array($tblpkey => $keyvalue);
    $row = $obj->select_record($tblname, $where);
    foreach ($fields as $field) {
        $$field = isset($row[$field]) ? $row[$field] : '';
    }

    $designation = $obj->getvalfield("designation_master", "designation", "designation_id='$designation_id'");

    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");

    $grade_name = $obj->getvalfield("grade_master", "grade_name", "grade_id='$grade_id'");
    $shift_name = $obj->getvalfield("shift_master", "shift_name", "shift_id='$shift_id'");
}



ob_start();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }
    </style>

</head>

<body>
    <!-- page 2 -->

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="20%" style="border: 0px;" align="left">
                <b>NR<br>GROUP</b>
            </td>
            <td width="80%" style="border: 0px;" align="right">
                <b>Candidate Joining Form</b>
            </td>
        </tr>
    </table>

    <br>

    <b>To be Filled in by own handwriting of the applicant in Block Letters</b>

    <br><br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%">
                <b>Employee Code Allotted</b> : <?= $emp_code ?>

            </td>
            <td width="30%" rowspan="6" align="center" valign="middle" border="1">
                <br><br>
                Paste recent<br>
                passport size<br>
                Photograph
                <br><br><br>
            </td>
        </tr>

        <tr>
            <td>1. Position Applied For :
                ..............................................................</td>
        </tr>

        <tr>
            <td>2. Full Name of Candidate (As per Aadhaar Card)</td>
        </tr>

        <tr>
            <td>..............................................................</td>
        </tr>

        <tr>
            <td>3. Father’s Name :
                ..............................................................</td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%"><b>4. Present Address</b></td>
            <td width="50%"><b>Permanent Address</b></td>
        </tr>

        <tr>
            <td>..............................................................</td>
            <td>..............................................................</td>
        </tr>
        <tr>
            <td>..............................................................</td>
            <td>..............................................................</td>
        </tr>
        <tr>
            <td>..............................................................</td>
            <td>..............................................................</td>
        </tr>

        <tr>
            <td>Police Station ..................... Pin ..............</td>
            <td>Police Station ..................... Pin ..............</td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                5. Contact Number : +91 ............................
            </td>
            <td width="50%">
                +91 ............................
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Email ID :
                ..................................................................................
            </td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td>
                6. Gender :
                [ ] Male &nbsp;&nbsp;
                [ ] Female &nbsp;&nbsp;
                [ ] Trans gender
            </td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="33%">7. Date of Birth : ....../....../........</td>
            <td width="33%">8. Age (in Years) : ............</td>
            <td width="34%">9. Blood Group : ............</td>
        </tr>

        <tr>
            <td>10. Place of Birth : .........................</td>
            <td>11. Height : .........................</td>
            <td>12. Weight : .........................</td>
        </tr>

        <tr>
            <td>13. Identification Marks : .........................</td>
            <td colspan="2">14. Any illness in past years : .........................</td>
        </tr>

        <tr>
            <td colspan="3">
                15. Details of last Medical Checkup if any :
                ..................................................................................
            </td>
        </tr>

        <tr>
            <td colspan="3">
                16. Details of Major Operation, undergone :
                ..................................................................................
            </td>
        </tr>

        <tr>
            <td>17. Marital Status : .........................</td>
            <td>18. Nationality : .........................</td>
            <td></td>
        </tr>

        <tr>
            <td>19. Religion : .........................</td>
            <td colspan="2">20. Caste : ST / SC / OBC / General</td>
        </tr>

        <tr>
            <td>21. Driving License No. : .........................</td>
            <td>22. Passport No. : .........................</td>
            <td></td>
        </tr>

        <tr>
            <td>23. PAN Card No. : .........................</td>
            <td colspan="2">24. Aadhar Number : .........................</td>
        </tr>
    </table>

    <br>

    <b>25. Language Known :</b>

    <table width="100%" cellpadding="8" cellspacing="0" border="1">
        <tr align="center">
            <td>Languages</td>
            <td>Speak</td>
            <td>Read</td>
            <td>Write</td>
        </tr>
        <tr height="30">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="30">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>


    <pagebreak />

    <!-- Page 3 -->
    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%" style="border: 0px;"></td>
            <td width="30%" align="right" style="border: 0px;"><b>Candidate Joining Form</b></td>
        </tr>
    </table>

    <br>

    <b>26. Family Details (Details required for ESIC Registration and Insurances)</b>

    <br><br>

    <table width="100%" cellpadding="6" cellspacing="0" border="1">
        <tr align="center">
            <td width="5%"><b>Sl.</b></td>
            <td width="30%"><b>Family Member Name<br>(As per Aadhaar Card)</b></td>
            <td width="8%"><b>Sex</b></td>
            <td width="15%"><b>Date of Birth</b></td>
            <td width="12%"><b>Relationship</b></td>
            <td width="30%"><b>Address</b></td>
        </tr>

        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <br><br>

    <b>
        27. Education Details : Please note that with any proper witness any mentioned qualification details are invalid
        in our record so submit your mark sheet with this form.
    </b>

    <br><br>

    <table width="100%" cellpadding="6" cellspacing="0" border="1">
        <tr align="center">
            <td width="14%"><b>Examination<br>Passed</b></td>
            <td width="18%"><b>Name of<br>College or<br>School</b></td>
            <td width="18%"><b>University or<br>Board Name</b></td>
            <td colspan="2" width="16%"><b>Date / Year</b></td>
            <td width="18%"><b>Subject /<br>Specialization</b></td>
            <td width="16%"><b>Percentage /<br>Grade</b></td>
        </tr>

        <tr align="center">
            <td></td>
            <td></td>
            <td></td>
            <td><b>From</b></td>
            <td><b>To</b></td>
            <td></td>
            <td></td>
        </tr>

        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <br><br>

    <b>28. Extra curricular activities : (State in brief your present hobbies, sports or other you like)</b>

    <br><br>
    ......................................................................................................................................................<br><br>
    ......................................................................................................................................................

    <br><br>

    <b>29. Have you been involved in any proceedings in the court of law ? If yes, give details</b>

    <br><br>
    ......................................................................................................................................................<br><br>
    ......................................................................................................................................................

    <br><br>

    <b>30. Please give your special achievement, you done with previous employers</b>

    <br><br>
    ......................................................................................................................................................<br><br>
    ......................................................................................................................................................

    <br><br>

    <b>31. Any other employees you know in NR Group :</b>
    <br>
    <br>
    ..................................................................................................................................

    <!-- page-4 -->
    <pagebreak />

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%" style="border: 0px;"></td>
            <td width="30%" align="right" style="border: 0px;"><b>Candidate Joining Form</b></td>
        </tr>
    </table>

    <br>

    <b>
        32. Previous Experience : Please note that, this record will be verified from employers so give it true.
    </b>

    <br><br>

    <table width="100%" cellpadding="6" cellspacing="0" border="1">
        <tr align="center">
            <td rowspan="2" width="5%"><b>Sl.</b></td>
            <td colspan="2" width="20%"><b>Service Period</b></td>
            <td rowspan="2" width="28%"><b>Name of Employer<br>with Address</b></td>
            <td rowspan="2" width="15%"><b>Designation</b></td>
            <td rowspan="2" width="15%"><b>Last Drawn<br>Salary</b></td>
            <td rowspan="2" width="17%"><b>Reason for<br>Change</b></td>
        </tr>
        <tr align="center">
            <td width="10%"><b>From</b></td>
            <td width="10%"><b>To</b></td>
        </tr>

        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="35">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="8" cellspacing="0" border="1">
        <tr>
            <td height="120" valign="top">
                <b>33. Brief your Job responsibilities in Previous Employer</b>
            </td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td>
                <b>34. How much manpower is working under you in Previous Employer :</b>
                ............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>35. How much manpower, you can manage individual in this organization :</b>
                ............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>36. Who is your reporting officer in Previous employer :</b>
                ............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>37. Have you submitted your Medical Checkup report in Form-21 format ?</b>
                &nbsp;&nbsp; Yes &nbsp;/&nbsp; No
            </td>
        </tr>
    </table>

    <br><br>

    <center><b>DECLARATION</b></center>

    <br>

    I hereby declare that, the above mentioned details are true and complete in my knowledge.
    I also authorize that, if you are your management found any mistake in my information
    then you can terminate my services immediately.

    <br><br><br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                <b>Date :</b> ....../....../..............
            </td>
            <td width="50%" align="right">
                <b>Signature of Candidate</b>
            </td>
        </tr>
    </table>

    <!-- page 5 -->
    <pagebreak />


    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%" style="border: 0px;"></td>
            <td width="30%" align="right" style="border: 0px;"><b>Document Checklist for Joining</b></td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                <b>Name</b> : ..............................................................
            </td>
            <td width="50%">
                <b>Date of Joining</b> : ....../....../202....
            </td>
        </tr>

        <tr>
            <td>
                <b>Designation</b> : ..............................................................
            </td>
            <td>
                <b>Grade</b> : ..............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>Department</b> : ..............................................................
            </td>
            <td>
                <b>Employee Code</b> : ..............................................................
            </td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="6" cellspacing="0" border="1">
        <tr align="center">
            <td width="6%"><b>Sl.</b></td>
            <td width="74%"><b>Description</b></td>
            <td width="20%"><b>Status</b></td>
        </tr>

        <tr>
            <td rowspan="6" align="center">1</td>
            <td><b>Academic Qualification Certificate</b></td>
            <td></td>
        </tr>
        <tr>
            <td>Master Degree : </td>
            <td></td>
        </tr>
        <tr>
            <td>Bachelor Degree : </td>
            <td></td>
        </tr>
        <tr>
            <td>Diploma Degree : </td>
            <td></td>
        </tr>
        <tr>
            <td>Academic Qualification : </td>
            <td></td>
        </tr>
        <tr>
            <td>School Documents : </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Additional Documents : </td>
            <td></td>
        </tr>

        <tr>
            <td align="center">2</td>
            <td>Document Submitted for Age Proof</td>
            <td></td>
        </tr>

        <tr>
            <td align="center">3</td>
            <td>Salary Proof Submitted (Salary Certificate / Salary Slip / Bank Statement)</td>
            <td></td>
        </tr>

        <tr>
            <td align="center">4</td>
            <td>Experience Certificate (Past and Present)</td>
            <td></td>
        </tr>

        <tr>
            <td align="center">5</td>
            <td>
                Photo Identity Proof (Driving License, Voter ID, Pan Card, Passport,
                Aadhar Card, Electricity Bill)
            </td>
            <td></td>
        </tr>

        <tr>
            <td align="center">6</td>
            <td>Old EPFO UAN Number</td>
            <td></td>
        </tr>

        <tr>
            <td align="center">7</td>
            <td>Old ESIC Number</td>
            <td></td>
        </tr>

        <tr>
            <td rowspan="3" align="center">8</td>
            <td><b>At Present Bank Details</b></td>
            <td></td>
        </tr>
        <tr>
            <td>Name in Account : </td>
            <td></td>
        </tr>
        <tr>
            <td>Bank Account Number : </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>IFSC Code and Branch Name : </td>
            <td></td>
        </tr>

        <tr>
            <td align="center">9</td>
            <td>Resignation Letter or Reliving Letters (of Previous Employer)</td>
            <td></td>
        </tr>

        <tr>
            <td align="center">10</td>
            <td>Photographs : 4 Passport size and 2 B2 size (2&quot; X 3&quot;) for ESIC with family</td>
            <td></td>
        </tr>
    </table>

    <br>

    <b>Undertaking :</b><br>
    I hereby declare that the above submitted documents are self verified by me and pending
    documents will be submit with .......... days in HR department. The HR department has full
    right to hold/keep pending my monthly salary till I comply with the same.

    <br><br><br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                <b>HR Name &amp; Signature</b>
            </td>
            <td width="50%" align="right">
                <b>Candidate Signature</b>
            </td>
        </tr>
    </table>


    <pagebreak />
    <!-- page 6 -->

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%" style="border: 0px;"></td>
            <td width="30%" align="right" style="border: 0px;"> <b>Reporting and Emergency Reporting Sheet</b><br>
                (After Joining of Candidate)</td>
        </tr>
    </table>


    <br><br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                <b>Name</b> : ..............................................................
            </td>
            <td width="50%">
                <b>Date of Joining</b> : ....../....../202....
            </td>
        </tr>

        <tr>
            <td>
                <b>Designation</b> : ..............................................................
            </td>
            <td>
                <b>Department</b> : ..............................................................
            </td>
        </tr>
    </table>

    <br><br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%">
                <b>1. Department Head</b> :
                ..............................................................
            </td>
            <td width="30%">
                <b>Signature</b> :
                ....................................
            </td>
        </tr>

        <tr>
            <td>
                <b>2. Department 2</b> :
                ..............................................................
            </td>
            <td>
                <b>Signature</b> :
                ....................................
            </td>
        </tr>

        <tr>
            <td>
                <b>3. Department 3</b> :
                ..............................................................
            </td>
            <td>
                <b>Signature</b> :
                ....................................
            </td>
        </tr>

        <tr>
            <td>
                <b>4. Department 4</b> :
                ..............................................................
            </td>
            <td>
                <b>Signature</b> :
                ....................................
            </td>
        </tr>
    </table>

    <br><br><br><br>

    <b>In case of Emergency, Contact Details like accidents and other:</b>

    <br><br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                <b>Name</b> : ..............................................................
            </td>
            <td width="50%">
                <b>Name</b> : ..............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>Address</b> : ..............................................................
            </td>
            <td>
                <b>Address</b> : ..............................................................
            </td>
        </tr>

        <tr>
            <td>
                ..............................................................
            </td>
            <td>
                ..............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>Contact Number</b> : ..............................................................
            </td>
            <td>
                <b>Contact Number</b> : ..............................................................
            </td>
        </tr>

        <tr>
            <td>
                <b>Relation with You</b> : ..............................................................
            </td>
            <td>
                <b>Relation with You</b> : ..............................................................
            </td>
        </tr>
    </table>


</body>

</html>


<?php
$html = ob_get_clean();
// print_r($html);
// die;
$mpdf->WriteHTML($html);
$mpdf->Output();
