<?php
include("../adminsession.php");

require_once __DIR__ . '/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 10,
    'margin_bottom' => 5,
    'margin_left' => 7,
    'margin_right' => 7,
]);

$pageHeight = 297;
$headerHeight = 40;
$footerHeight = 40;
$imgpath1 = 'uploaded/emp_documents/';
$unit_imgpath = 'uploaded/unit_logo/';

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
    'profile_image',
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
    'bank_id',
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
    $employer_designation = $obj->getvalfield("designation_master", "designation", "designation_id='$employer_designation_id'");

    $department_name = $obj->getvalfield("department_master", "department_name", "department_id='$department_id'");

    $grade_name = $obj->getvalfield("grade_master", "grade_name", "grade_id='$grade_id'");
    $bank_name = $obj->getvalfield("bank_master", "bank_name", "bank_id='$bank_id'");
    $shift_name = $obj->getvalfield("shift_master", "shift_name", "shift_id='$shift_id'");
    $unit_logo = $obj->getvalfield("unit_master", "logo_image", "unit_id='$unit_id'");

    $checkedDocs = [];
    if (!empty($document_checked_ids)) {
        $checkedDocs = explode(',', $document_checked_ids);
    }
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
                <img src="<?= $unit_imgpath . '/' . $unit_logo ?>" alt="" width="50px">
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
                <?php if ($profile_image != "") {
                ?>
                    <img src="<?php echo $imgpath1 . $profile_image;  ?>" style="height:20%; width:20%;" alt="">
                <?php
                } else { ?> <br><br>
                    Paste recent<br>
                    passport size<br>
                    Photograph
                    <br><br><br> <?php
                                } ?>

            </td>
        </tr>

        <tr>
            <td>1. Position Applied For : <?= $designation; ?></td>
        </tr>

        <tr>
            <td>2. Full Name of Candidate (As per Aadhaar Card)</td>
        </tr>

        <tr>
            <td><?= htmlspecialchars(ucfirst(trim($first_name)) . ' ' . ucfirst(trim($last_name))); ?></td>

        </tr>

        <tr>
            <td>3. Father’s Name : <?= htmlspecialchars(ucfirst(trim($father_name))) ?></td>

        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%"><b>4. Present Address</b></td>
            <td width="50%"><b>Permanent Address</b></td>
        </tr>

        <tr>
            <td><?= ucfirst($present_address ?? ''); ?></td>
            <td><?= ucfirst($permanent_address ?? '');  ?></td>
        </tr>

    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="50%">
                5. Contact Number : +91 <?= $mobile_no; ?>
            </td>
            <td width="50%">
                +91 <?= $alt_mobile_no; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Email ID :
                <?= $email_id; ?>
            </td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td>
                6. Gender : <?= $gender; ?>
            </td>
        </tr>
    </table>

    <br>

    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="33%">7. Date of Birth : <?= $obj->dateformatindia($dob);  ?></td>
            <td width="33%">8. Age (in Years) : <?= $age; ?></td>
            <td width="34%">9. Blood Group : <?= $blood_group; ?></td>
        </tr>

        <tr>
            <td>10. Identification Marks : <?= $identification_masks ?></td>

        </tr>





        <tr>
            <td>11. Marital Status : <?= $marital_status; ?></td>
            <td>12. Nationality : <?= ucfirst($nationality ?? '');  ?></td>
            <td></td>
        </tr>

        <tr>
            <td>13. Religion : <?= ucfirst($religion ?? ''); ?></td>
            <td colspan="2">20. Caste : <?= $caste; ?></td>
        </tr>

        <tr>
            <td>14. Driving License No. : <?= $driving_license; ?></td>
            <td>15. Passport No. : <?= $passport_no; ?></td>
            <td></td>
        </tr>

        <tr>
            <td>16. PAN Card No. : <?= $pan_no; ?></td>
            <td colspan="2">17. Aadhar Number : <?= $aadhar_no; ?></td>
        </tr>
    </table>

    <br>

    <b>18. Language Known :</b>

    <table width="100%" cellpadding="8" cellspacing="0" border="1">
        <tr align="center">
            <td>Languages</td>
            <td>Speak</td>
            <td>Read</td>
            <td>Write</td>
        </tr>

        <?php
        $details = $obj->executequery("Select * from emp_language where emp_id='$keyvalue' and unit_id='$unitid' order by emp_language_id desc");

        foreach ($details as $row) {
        ?> <tr height="30">
                <td><?= $row['language_name']; ?> </td>
                <td><?= $row['is_speak'] == '1' ? 'Yes' : 'No'; ?> </td>
                <td><?= $row['is_read'] == '1' ? 'Yes' : 'No'; ?> </td>
                <td><?= $row['is_write'] == '1' ? 'Yes' : 'No'; ?> </td>
            </tr>
        <?php }
        ?>


    </table>


    <!-- <pagebreak /> -->

    <!-- Page 3 -->
    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%" style="border: 0px;"></td>
            <td width="30%" align="right" style="border: 0px;"><b>Candidate Joining Form</b></td>
        </tr>
    </table>

    <br>

    <b>19. Family Details (Details required for ESIC Registration and Insurances)</b>

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
        <?php
        $sno = 1;
        $details = $obj->executequery("Select * from emp_family_details where emp_id='$keyvalue' and unit_id='$unitid' order by family_detail_id desc");
        foreach ($details as $row) {

        ?>
            <tr height="35">
                <td><?= $sno++; ?> </td>
                <td><?= ucfirst($row['member_name'] ?? '');   ?> </td>
                <td><?= ucfirst($row['gender'] ?? ''); ?> </td>
                <td><?= $obj->dateformatindia($row['dob']); ?> </td>
                <td><?= ucfirst($row['relation'] ?? ''); ?> </td>
                <td><?= ucfirst($row['address'] ?? '');  ?> </td>
            </tr> <?php } ?>
    </table>

    <br><br>

    <b>
        20. Education Details : Please note that with any proper witness any mentioned qualification details are invalid
        in our record so submit your mark sheet with this form.
    </b>

    <br><br>

    <table width="100%" cellpadding="6" cellspacing="0" border="1">
        <tr align="center">
            <td width="14%"><b>Examination<br>Passed</b></td>
            <td width="18%"><b>Name of<br>College or<br>School</b></td>
            <td width="18%"><b>University or<br>Board Name</b></td>
            <td width="16%"><b>Date / Year</b></td>
            <td width="18%"><b>Subject /<br>Specialization</b></td>
            <td width="16%"><b>Percentage /<br>Grade</b></td>
        </tr>
        <?php
        $edu_details = $obj->executequery("Select * from emp_education where emp_id='$keyvalue' and unit_id='$unitid' order by education_id desc");

        foreach ($edu_details as $row) {
        ?>
            <tr align="center">
                <td><?= ucfirst($row['examination'] ?? '');   ?> </td>
                <td><?= ucfirst($row['college'] ?? '');  ?> </td>
                <td><?= ucfirst($row['university'] ?? '');  ?> </td>
                <td><?= $row['pass_year']; ?> </td>
                <td><?= $row['percentage']; ?> </td>
                <td><?= ucfirst($row['subject'] ?? '');   ?> </td>

            </tr>
        <?php } ?>

    </table>


    <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
            <td width="70%" style="border: 0px;"></td>
            <td width="30%" align="right" style="border: 0px;"><b>Candidate Joining Form</b></td>
        </tr>
    </table>

    <br>

    <b>
        21. Previous Experience : Please note that, this record will be verified from employers so give it true.
    </b>

    <br><br>

    <table width="100%" cellpadding="6" cellspacing="0" border="1">
        <tr align="center">
            <td rowspan="2" width="5%"><b>Sl.</b></td>
            <td colspan="2" width="30%"><b>Service Period</b></td>
            <td rowspan="2" width="28%"><b>Name of Employer<br>with Address</b></td>
            <td rowspan="2" width="15%"><b>Designation</b></td>
            <td rowspan="2" width="15%"><b>Last Drawn<br>Salary</b></td>
            <td rowspan="2" width="17%"><b>Reason for<br>Change</b></td>
        </tr>
        <tr align="center">
            <td width="15%"><b>From</b></td>
            <td width="15%"><b>To</b></td>
        </tr>

        <tr height="35">
            <td>1.</td>
            <td><?= $obj->dateformatindia($service_from); ?> </td>
            <td><?= $obj->dateformatindia($service_to); ?> </td>
            <td><?= ucfirst($employer_name ?? ''); ?></td>
            <td><?= ucfirst($employer_designation ?? ''); ?></td>
            <td><?= $last_salary; ?></td>
            <td><?= ucfirst($reason ?? ''); ?></td>
        </tr>

    </table>

    <br>

    <table width="100%" cellpadding="8" cellspacing="0" border="1">
        <tr>
            <td height="120" valign="top">
                <b>22. Brief your Job responsibilities in Previous Employer</b>
                <br><br>
                <?= ucfirst($job_responsibility ?? ''); ?>
            </td>

        </tr>

    </table>

    <br>


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
                <b>Name</b> : <?= htmlspecialchars(ucfirst(trim($first_name)) . ' ' . ucfirst(trim($last_name))); ?>
            </td>
            <td width="50%">
                <b>Date of Joining</b> : <?= $obj->dateformatindia($date_of_joining); ?>
            </td>
        </tr>

        <tr>
            <td>
                <b>Designation</b> : <?= $designation ?>
            </td>
            <td>
                <b>Grade</b> : <?= $grade_name ?>
            </td>
        </tr>

        <tr>
            <td>
                <b>Department</b> : <?= $department_name ?>
            </td>
            <td>
                <b>Employee Code</b> : <?= $emp_code ?>
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
            <td align="center">1</td>
            <td><b>Academic Qualification Certificate</b></td>
            <td></td>
        </tr>
        <?php
        $sn = 1;
        $doc_res = $obj->executequery("SELECT * FROM document_master ORDER BY doc_id ASC");
        foreach ($doc_res as $row) {
            if (isset($checkedDocs)) {
                $isChecked = in_array($row['doc_id'], $checkedDocs) ? 'Checked' : '';
            } else {
                $isChecked = '';
            }

        ?>
            <tr>
                <td><?= $sn++; ?></td>
                <td><?= $row['document_name']; ?> : </td>
                <td><?= $isChecked ?></td>
            </tr>
        <?php } ?>

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
                <b>Name</b> : <?= htmlspecialchars(ucfirst(trim($first_name)) . ' ' . ucfirst(trim($last_name))); ?>
            </td>
            <td width="50%">
                <b>Date of Joining</b> : <?= $obj->dateformatindia($date_of_joining); ?>
            </td>
        </tr>

        <tr>
            <td>
                <b>Designation</b> : <?= $designation ?>

            </td>
            <td>
                <b>Department</b> : <?= $department_name ?>

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
                <b>Name</b> : <?= $emer_contact_name ?>
            </td>

        </tr>

        <tr>
            <td>
                <b>Address</b> : <?= $present_address ?>
            </td>

        </tr>


        <tr>
            <td>
                <b>Contact Number</b> : <?= $emer_contact_no ?>
            </td>

        </tr>

        <tr>
            <td>
                <b>Relation with You</b> : <?= $emer_contact_relation ?>
            </td>

        </tr>
    </table>


</body>

</html>


<?php
$html = ob_get_clean();

$mpdf->WriteHTML($html);
$mpdf->Output(); ?>