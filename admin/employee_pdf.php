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
//$unit_imgpath = 'uploaded/unit_logo/';
$unit_imgpath = '../management/uploaded/emp_documents/';


$tblname = "employee_master";
$tblpkey = "emp_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$fields = [
    'emp_code',
    'first_name',
    'emp_sign',
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
    'allow_weekly_off',
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
    'anniversary_date',
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
    $is_allow_c_off = $obj->getvalfield("department_master", "c_off_check", "department_id='$department_id'");
    $is_all_leave_add = $obj->getvalfield("unit_master", "add_leave", "unit_id='$unitid'");

    $grade_name = $obj->getvalfield("grade_master", "grade_name", "grade_id='$grade_id'");
    $bank_name = $obj->getvalfield("bank_master", "bank_name", "bank_id='$bank_id'");
    $shift_name = $obj->getvalfield("shift_master", "shift_name", "shift_id='$shift_id'");
    $unit_logo = $obj->getvalfield("unit_master", "logo_image", "unit_id='$unit_id'");

    $unit_name    = $obj->getvalfield("unit_master", "unit_name", "unit_id='$unit_id'");
    $head_name    = $obj->getvalfield("unit_master", "unithead", "unit_id='$unit_id'");
    $unit_mobile  = $obj->getvalfield("unit_master", "mobile", "unit_id='$unit_id'");
    $unit_email   = $obj->getvalfield("unit_master", "email_id", "unit_id='$unit_id'");
    $unit_address = $obj->getvalfield("unit_master", "address", "unit_id='$unit_id'");

$setting_type = ($is_esic  == 1) ? 'ESIC' : 'Non ESIC';

    $checkedDocs = [];
    if (!empty($document_checked_ids)) {
        $checkedDocs = explode(',', $document_checked_ids);
    }

    $emp_family = $obj->executequery("SELECT * FROM emp_family_details WHERE emp_id = '$keyvalue'");
    $mother_name = '';

    if (!empty($emp_family)) {
        foreach ($emp_family as $family) {

            if (strtolower($family['relation']) == 'mother') {
                $mother_name = $family['member_name'];
            }
        }
    }


    $emp_documents = $obj->executequery("SELECT ed.*, dm.document_name FROM emp_document ed LEFT JOIN document_master dm ON dm.doc_id = ed.doc_id WHERE ed.emp_id = '$keyvalue'");

    $emp_education = $obj->executequery("SELECT * FROM emp_education WHERE emp_id = '$keyvalue' ORDER BY education_id ASC");

    // Branch Transfer Records
    $emp_branch_transfer = $obj->executequery("SELECT bt.*, um.unit_name, dm.department_name, des.designation FROM emp_branch_transfer bt LEFT JOIN unit_master um ON um.unit_id = bt.unit_id LEFT JOIN department_master dm ON dm.department_id = bt.department_id LEFT JOIN designation_master des ON des.designation_id = bt.designation_id WHERE bt.emp_id = '$keyvalue' ORDER BY bt.branch_transfer_id ASC");

    $loan_advance = $obj->executequery("
        SELECT *
        FROM loan_advance
        WHERE emp_id = '$keyvalue'
        ORDER BY loan_advance_id ASC
    ");
}

ob_start();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
        }

        .container {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .heading {
            font-size: 16px;
            font-weight: bold;
            padding-top: 15px;
            padding-bottom: 5px;
        }

        .label {
            width: 180px;
        }

        .colon {
            width: 10px;
        }

        .value {
            width: 250px;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo {
            width: 120px;
        }

        .photo {
            width: 95px;
            height: 115px;
        }

        .sign {
            width: 95px;
            height: 40px;
        }


        .company-info {
            text-align: left;
            font-size: 13px;
        }
    </style>

</head>

<body>

    <div class="container">

        <!-- Header Section -->

        <table class="header-table">

            <tr>

                <td width="25%">
                    <?php if (!empty($unit_logo) && file_exists($unit_imgpath . $unit_logo)) { ?>
                        <img src="<?php echo $unit_imgpath . $unit_logo; ?>" class="logo">
                    <?php } ?>

                </td>

                <td width="50%" class="company-info">

                    <span class="fe-semibold"> Unit Name :</span>
                    <small><?= $unit_name ?></small> <br>

                    <span class="fe-semibold"> Head Name :</span>
                    <small><?= $head_name ?></small> <br>

                    <span class="fe-semibold"> Contact No :</span>
                    <small><?= $unit_mobile ?></small> <br>

                    <span class="fe-semibold"> Email :</span>
                    <small><?= $unit_email ?></small> <br>

                    <span class="fe-semibold"> Unit Address :</span>
                    <small><?= $unit_address ?></small>

                </td>


            </tr>

        </table>

        <hr>
        <!-- Employee Basic Info -->

        <table>

            <tr>

                <td class="label">Employee Name</td>
                <td class="colon">:</td>
                <td class="value"><?php echo strtoupper($first_name . ' ' . $last_name); ?></td>

                <td rowspan="6" align="right" width="200">

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center">
                                <?php if (!empty($profile_image) && file_exists($imgpath1 . $profile_image)) { ?>
                                    <img src="<?php echo $imgpath1 . $profile_image; ?>" class="photo">
                                <?php } ?>
                            </td>

                            <td align="center">
                                <?php if (!empty($emp_sign) && file_exists($imgpath1 . $emp_sign)) { ?>
                                    <img src="<?php echo $imgpath1 . $emp_sign; ?>" class="sign">
                                <?php } ?>
                            </td>
                        </tr>
                    </table>

                </td>


            </tr>

            <tr>
                <td class="label">Employee Code</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $emp_code; ?></td>
            </tr>

            <tr>
                <td class="label">Company Name</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $unit_name; ?></td>
                <!-- <td class="value">AGRAWAL INFRABUILD PRIVATE LIMITED</td> -->
            </tr>

            <tr>
                <td class="label">Branch Name</td>
                <td class="colon">:</td>
                <td class="value">HEAD OFFICE</td>
            </tr>

            <tr>
                <td class="label">Department</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $department_name; ?></td>

            </tr>

            <tr>
                <td class="label">Designation</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $designation ?></td>
            </tr>

        </table>


        <!-- Personal Details -->

        <div class="heading">Personal Details</div>

        <table>

            <tr>
                <td class="label">Father/Husband</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $father_name; ?></td>

                <td class="label">Mother Name</td>
                <td class="colon">:</td>
                <td class="value"><?= $mother_name ?></td>

            </tr>

            <tr>
                <td class="label">Date Of Birth</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $obj->dateformatindia($dob); ?></td>

                <td class="label">Email ID</td>
                <td class="colon">:</td>
                <td class="value"><?= $email_id ?></td>

            </tr>

            <tr>
                <td class="label">Gender</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $gender; ?></td>

                <td class="label">Blood Group</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $blood_group; ?></td>
            </tr>

            <tr>
                <td class="label">Marriage Status</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $marital_status; ?></td>

                <td class="label">Anniversary</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $obj->dateformatindia($anniversary_date); ?></td>
            </tr>

            <tr>
                <td class="label">Present Address</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $present_address; ?></td>

                <td class="label">Permanent Address</td>
                <td class="colon">:</td>
                <td class="value"><?= $permanent_address ?></td>
            </tr>

            <tr>
                <td class="label">Mobile No</td>
                <td class="colon">:</td>
                <td class="value"><?= $mobile_no ?></td>

                <td class="label">Alternate Mobile No</td>
                <td class="colon">:</td>
                <td class="value"><?= $alt_mobile_no ?></td>
            </tr>

            <tr>

                <td class="label">Employee Religion</td>
                <td class="colon">:</td>
                <td class="value"><?= $religion ?></td>
            </tr>




        </table>


        <!-- HR Details -->

        <div class="heading">HR Details</div>

        <table class="hr-table">

            <tr>
                <td class="label">Work ID</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $emp_code; ?></td>

                <td class="label">Shift</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $shift_id; ?></td>
            </tr>

            <tr>
                <td class="label">Reporting Emp</td>
                <td class="colon">:</td>
                <td class="value"><?= $reporting_manager ?></td>

                <td class="label">Date of Joining</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $obj->dateformatindia($date_of_joining); ?></td>
            </tr>

            <tr>
                <td class="label">Driving License No</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $driving_license; ?></td>

                <td class="label">Passport No</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $passport_no; ?></td>
            </tr>

            <tr>
                <td class="label">Bank Account No</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $account_no; ?></td>

                <td class="label">Bank Name</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $bank_name; ?></td>
            </tr>

            <tr>
                <td class="label">IFSC Code</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $ifsc_code; ?></td>

                <td class="label">Aadhaar No</td>
                <td class="colon">:</td>
                <td class="value"><?= $aadhar_no ?></td>
            </tr>

            <tr>
                <td class="label">PF Number</td>
                <td class="colon">:</td>
                <td class="value"><?php echo $pf_uan; ?></td>

                <td class="label">PF Date</td>
                <td class="colon">:</td>
                <td class="value"><?= $obj->dateformatindia($pf_joining_date) ?></td>
            </tr>

            <tr>
                <td class="label">Pan No</td>
                <td class="colon">:</td>
                <td class="value"><?= $pan_no ?></td>

                <td class="label">ESIC No</td>
                <td class="colon">:</td>
                <td class="value"><?= $esic_no ?></td>
            </tr>

            <tr>
                <td class="label">Remark</td>
                <td class="colon">:</td>
                <td class="value"><?= $identification_masks ?></td>

                <td></td>
                <td></td>
                <td></td>
            </tr>

        </table>

        <!-- Document Details -->
        <h3>Document Details</h3>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <tr>
                <th style="text-align:left;"><small>SNo</small></th>
                <th style="text-align:left;"><small>Document Type</small></th>
                <th style="text-align:left;"><small>Expiry Date</small></th>
                <th style="text-align:left;"><small>Remark</small></th>
                <th style="text-align:left;"><small>File Attached</small></th>
            </tr>

            <?php
            if (!empty($emp_documents)) {
                $i = 1;
                foreach ($emp_documents as $doc) {
            ?>
                    <tr>
                        <td><small><?= $i++; ?></small></td>

                        <td><small><?= $doc['document_name']; ?></small></td>

                        <td>
                            <small>
                                <?= (!empty($doc['doc_expiry_date']) && $doc['doc_expiry_date'] != '0000-00-00')
                                    ? $obj->dateformatindia($doc['doc_expiry_date'])
                                    : '' ?>
                            </small>
                        </td>

                        <td><small><?= $doc['doc_remark']; ?></small></td>

                        <td>
                            <small>
                                <?= (!empty($doc['doc_file']) && file_exists($imgpath1 . $doc['doc_file']))
                                    ? $doc['doc_file']
                                    : 'No File'; ?>
                            </small>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="5" align="center">No Documents Found</td></tr>';
            }
            ?>
        </table>


        <pagebreak />
        <!-- Educations Details -->

        <h3>Education Details</h3>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">

            <tr>
                <th style="text-align:left;"> <small> SNo</small> </th>
                <th style="text-align:left;"> <small> Degree Of Exam</small> </th>
                <th style="text-align:left;"> <small> University/College/School</small> </th>
                <!-- <th> <small> Division</small> </th> -->
                <th style="text-align:left;"> <small> Percentage Of Marks</small> </th>
                <th style="text-align:left;"> <small> Passing Year</small> </th>
                <th style="text-align:left;"> <small> Subject</small> </th>
                <!--<th> <small> Remark</small> </th> -->
            </tr>

            <?php
            if (!empty($emp_education)) {
                $i = 1;
                foreach ($emp_education as $edu) {
            ?>
                    <tr>
                        <td><small><?= $i++; ?></small></td>
                        <td><small><?= $edu['examination']; ?></small></td>
                        <td><small><?= $edu['university']; ?><br><?= $edu['college']; ?></small></td>
                        <td><small><?= $edu['percentage']; ?>%</small></td>
                        <td><small><?= $edu['pass_year']; ?></small></td>
                        <td><small><?= $edu['subject']; ?></small></td>
                        <!-- <td><small>-</small></td> -->
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6" align="center">No Education Records Found</td></tr>';
            }
            ?>

        </table>
        <br>
        <!-- Prevous Orgination Details -->
        <h3>Previous Organization Details</h3>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <tr>
                <th style="text-align:left;"><small>SNo</small></th>
                <th style="text-align:left;"><small>Organization Name</small></th>
                <th style="text-align:left;"><small>Joining From</small></th>
                <th style="text-align:left;"><small>Joining To</small></th>
                <th style="text-align:left;"><small>Designation</small></th>
                <th style="text-align:left;"><small>Last Salary</small></th>
                <th style="text-align:left;"><small>Reason For Leaving</small></th>
            </tr>

            <?php if (!empty($employer_name)) {
            ?>
                <tr>
                    <td><small>1</small></td>
                    <td><small><?= $employer_name ?></small></td>

                    <td>
                        <small>
                            <?= (!empty($service_from) && $service_from != '0000-00-00')
                                ? $obj->dateformatindia($service_from)
                                : '' ?>
                        </small>
                    </td>

                    <td>
                        <small>
                            <?= (!empty($service_to) && $service_to != '0000-00-00')
                                ? $obj->dateformatindia($service_to)
                                : '' ?>
                        </small>
                    </td>

                    <td><small><?= $employer_designation ?></small></td>

                    <td><small><?= $last_salary ?></small></td>

                    <td><small><?= $reason ?></small></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="7" align="center">No Previous Organization Found</td>
                </tr>
            <?php } ?>
        </table>


        <!-- Branch Details -->
        <br>
        <h3>Branch Details</h3>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">

            <tr>
                <th style="text-align:left;"> <small> SNo</small> </th>
                <th style="text-align:left;"> <small> Date From-To</small> </th>
                <th style="text-align:left;"> <small> Unit Name</small> </th>
                <th style="text-align:left;"> <small> Department</small> </th>
                <th style="text-align:left;"> <small> Designation</small> </th>
            </tr>

            <?php
            if (!empty($emp_branch_transfer)) {
                $i = 1;
                foreach ($emp_branch_transfer as $bt) {
            ?>
                    <tr>
                        <td><small><?= $i++; ?></small></td>

                        <td>
                            <small>
                                <?= (!empty($bt['joining_date']) && $bt['joining_date'] != '0000-00-00')
                                    ? $obj->dateformatindia($bt['joining_date'])
                                    : '' ?>
                                -
                                <?= (!empty($bt['last_work_date']) && $bt['last_work_date'] != '0000-00-00')
                                    ? $obj->dateformatindia($bt['last_work_date'])
                                    : 'Till Date' ?>
                            </small>
                        </td>

                        <td><small><?= $bt['unit_name']; ?></small></td>
                        <td><small><?= $bt['department_name']; ?></small></td>
                        <td><small><?= $bt['designation']; ?></small></td>
                    </tr>

            <?php
                }
            } else {
                echo '<tr><td colspan="5" align="center">No Branch Transfer Found</td></tr>';
            }
            ?>


        </table>


        <!-- Loan Advance -->
        <br>
        <h3>Loan Advance</h3>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">

            <tr>
                <th style="text-align:left;"> <small> SNo</small> </th>
                <th style="text-align:left;"> <small> Loan/Adv. Date</small> </th>
                <th style="text-align:left;"> <small> From Date</small> </th>
                <th style="text-align:left;"> <small> To Date</small> </th>
                <th style="text-align:left;"> <small> Loan/Adv Amt</small> </th>
                <th style="text-align:left;"> <small> Inst</small> </th>
                <th style="text-align:left;"> <small> Paid + Setoff Amt</small> </th>
                <th style="text-align:left;"> <small> Balance Amount</small> </th>
            </tr>

            <?php
            if (!empty($loan_advance)) {
                $i = 1;
                foreach ($loan_advance as $bt) {
            ?>
                    <tr>
                        <td><small><?= $i++; ?></small></td>

                        <td>
                            <small>
                            <?= (!empty($bt['loan_date']) && $bt['loan_date'] != '0000-00-00')
                                ? $obj->dateformatindia($bt['loan_date'])
                                : '' ?>
                            </small>
                        </td>
                        <td>
                            <small>
                                <?= date("M", mktime(0, 0, 0, $bt['start_month'], 1)) . '-' . $bt['start_year']; ?>
                            </small>
                        </td>

                        <td>
                            <small>
                                <?= date("M", mktime(0, 0, 0, $bt['last_month'], 1)) . '-' . $bt['last_year']; ?>
                            </small>
                        </td>
                        
                        <td><small><?= $bt['loan_adv_amt']. ' + '. $bt['interest_amount']; ?><br><?= ' ( '. $bt['type'] .' ) '; ?></small></td>
                        <td><small><?= $bt['no_of_inst']; ?></small></td>
                        <?php
                            $paid_amount = $obj->getvalfield(
                                "loan_advance_details",
                                "SUM(amount)",
                                "loan_advance_id='" . $bt['loan_advance_id'] . "' AND is_paid='1'"
                            );

                            $paid_amount = ($paid_amount != "") ? $paid_amount : 0;

                            $balance_amount = $bt['total_amount'] - $paid_amount;
                        ?>

                        <td><small><?= number_format($paid_amount, 2); ?></small></td>
                        <td><small><?= number_format($balance_amount, 2); ?></small></td>
                    </tr>

            <?php
                }
            } else {
                echo '<tr><td colspan="8" align="center">No Branch Transfer Found</td></tr>';
            }
            ?>


        </table>


          <h3>Paid Salary Detail</h3>

        <table border="1" width="100%" cellpadding="5" cellspacing="0">

            <tr>
                <th style="text-align:left;"> <small>SNo</small> </th>
                <th style="text-align:left;"> <small>Month</small> </th>
                <th style="text-align:left;"> <small>Year</small> </th>
                <th style="text-align:left;"> <small>Salary</small> </th>
                <th style="text-align:left;"> <small>Increment</small> </th>
                <th style="text-align:left;"> <small>Revised Salary</small></th>
                <th style="text-align:left;"> <small>TD</small> </th>
                <th style="text-align:left;"> <small>P</small> </th> 
                <th style="text-align:left;"> <small>WO</small> </th>
                <th style="text-align:left;"> <small>EL</small> </th>  
                <th style="text-align:left;"> <small>TWD</small> </th>
                <th style="text-align:left;"> <small>Gross</small> </th>
                <th style="text-align:left;"> <small>PF</small> </th>
                <th style="text-align:left;"> <small>ESIC</small> </th>
                <th style="text-align:left;"> <small>Loan/Advance</small> </th>
                <th style="text-align:left;"> <small>Tot.Add</small> </th>
                <th style="text-align:left;"> <small>Tot.Ded</small> </th>
                <th style="text-align:left;"> <small>TDS</small> </th>
                <th style="text-align:left;"> <small>Payable Salary</small> </th>
            
            </tr>

            <?php
                $salary_details = $obj->executequery("select * from salary_structure where emp_id='$keyvalue' order by month asc");
                $i=1;
                foreach ($salary_details as $row) {
                    $month = $row['month'];
                    $year  = $row['year'];
                    $emp_id  = $row['emp_id'];

                    $attendance = $obj->executequery("
                        SELECT 
                            SUM(CASE 
                                WHEN attendance_status = 'Present' THEN 1 
                                ELSE 0 
                            END) AS total_present1,

                            SUM(CASE 
                                WHEN attendance_status = 'Half Day' THEN 1 
                                ELSE 0 
                            END) AS total_half1,

                            SUM(CASE 
                                WHEN attendance_status IN ('Present','Weekly Leave','Earning Leave','C Off','Extra Off','Leave') THEN 1 
                                ELSE 0 
                            END) AS total_present,

                            SUM(CASE 
                                WHEN attendance_status IN ('Half Day','Half Weekly Leave','Half Earning Leave','Half C Off','Half Extra Off','Half Leave') THEN 1 
                                ELSE 0 
                            END) AS total_half

                        FROM attendance_entry
                        WHERE emp_id = '$emp_id' 
                        AND month = '$month' 
                        AND year = '$year' AND unit_id='$unitid'
                    ");

                    $att = $attendance[0] ?? [];
                                    
                    $total_present1 = $att['total_present1'] ?? 0;
                    $total_half1    = $att['total_half1'] ?? 0;

                    $total_present  = $att['total_present'] ?? 0;
                    $total_half     = $att['total_half'] ?? 0;

                    $real_total_attandence = $total_present1 + ($total_half1 / 2);
                    $total_attandence      = $total_present + ($total_half / 2);

                    $week_leave = $obj->totalWeeklyLeave($unitid, $real_total_attandence, $allow_weekly_off);
                    $earn_leave_present =  $real_total_attandence+$week_leave;
                    $monthly_leave = $obj->getTotalLeaveByWorkingDays($setting_type, $earn_leave_present, $unitid);
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    $result = $obj->calculateLeaveUsage(
                        $daysInMonth,
                        $total_attandence,
                        $week_leave,
                        $monthly_leave,
                        $is_allow_c_off,
                        $is_all_leave_add
                    );
                     $total_payable_days = $result['total_working_days'];
            ?>
                    <tr>
                        <td><small><?= $i++; ?></small></td>

                        <td>  <?= date("F", mktime(0, 0, 0, $row['month'], 1)) ?></td>
                        <td>  <?= $row['year'] ?> </td>
                        <td>  <?= $row['basic_salary'] ?> </td>
                        <td>  <?= $row['increment'] ?> </td>
                        <td>  <?= $row['revised_salary'] ?> </td> 
                        <td> <?=$daysInMonth?>  </td> 
                        <td> <?=$total_attandence?>  </td> 
                        <td> <?=$week_leave?>  </td> 
                        <td> <?=$monthly_leave?>  </td>  
                        <td> <?=$total_payable_days?>  </td>   
                        <td>  <?= $row['total_salary'] ?> </td>
                        <td>  <?= $row['pf_emp'] ?> </td>
                        <td>  <?= $row['esic_emp'] ?> </td>
                        <td>  <?= $row['loan_amt'] ?> <?= $row['advance_amt'] ?> </td> 
                        <td>  <?= $row['additional_payment'] ?> </td>
                        <td>  <?= $row['other_deduction'] ?> </td>
                        <td>  <?= $row['tds_deduction'] ?> </td>
                        <td>  <?= $row['total_pay_sal_after_ded'] ?> </td>
                    </tr>

            <?php 
            }
            ?>


        </table>


    </div>

</body>

</html>


<?php
$html = ob_get_clean();
$mpdf->WriteHTML($html); 
 
$mpdf->Output(); ?>