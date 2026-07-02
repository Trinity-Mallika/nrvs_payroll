<?php include_once("../../adminsession.php");

$imgpath1 = './uploaded/emp_documents/';
$type = $obj->test_input($_POST['type']);
$keyvalue = $obj->test_input($_POST['keyvalue']);
if ($type == 'family') {
    $details = $obj->executequery("Select * from emp_family_details where emp_id='$keyvalue' and unit_id='$unitid' order by family_detail_id desc");
    foreach ($details as $row) {

?>
        <tr>
            <td><?= $row['member_name']; ?> </td>
            <td><?= $row['relation']; ?> </td>
            <td><?= $obj->dateformatindia($row['dob']); ?> </td>
            <td><?= $row['address']; ?> </td>
            <td><?= $row['gender']; ?> </td>
            <td><a href="<?= $imgpath1 . $row['aadhar_card'] ?>" class="btn btn-sm btn-primary" target="_blank">view</a></td>

            <td class="text-center">
                <div class="d-flex align-items-center wt">
                    <div class="flex-grow-1 ms-2 name">
                        <label class="switch">
                            <input type="checkbox"
                                <?php if ($row['is_nominee'] == 1) echo "checked"; ?>>
                            <span class="slider round" onclick="change_nominee('<?php echo $row['family_detail_id']; ?>');"> </span>
                        </label>
                    </div>
                </div>
            </td>

            <td>
                <button type="button" class="btn btn-primary btn-sm"
                    onclick="edit_family(
                    '<?= $row['family_detail_id']; ?>',
                    '<?= htmlspecialchars($row['member_name'], ENT_QUOTES); ?>',
                    '<?= htmlspecialchars($row['relation'], ENT_QUOTES); ?>',
                    '<?= $row['dob']; ?>',
                    '<?= htmlspecialchars($row['address'], ENT_QUOTES); ?>',
                    '<?= $row['gender']; ?>',
                    '<?= $row['is_nominee']; ?>',
                    '<?= $row['aadhar_card']; ?>'
                )">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button type="button" class="btn btn-danger btn-sm btn-label right ms-auto nexttab nexttab"
                    data-nexttab="pills-experience-tab" onclick="delete_family('<?= $row['family_detail_id']; ?>','<?= $row['aadhar_card']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 ms-2"></i>Del</button>
            </td>
        </tr>
    <?php }
} else if ($type == 'education') {
    $details = $obj->executequery("Select * from emp_education where emp_id='$keyvalue' and unit_id='$unitid' order by education_id desc");

    foreach ($details as $row) {
    ?>
        <tr>
            <td><?= $row['examination']; ?> </td>
            <td><?= $row['university']; ?> </td>
            <td><?= $row['college']; ?> </td>
            <td><?= $row['pass_year']; ?> </td>
            <td><?= $row['percentage']; ?> </td>
            <td><?= $row['subject']; ?> </td>
            <td>
                <button type="button" class="btn btn-primary btn-sm"
                    onclick="edit_education(
            '<?= $row['education_id']; ?>',
            '<?= htmlspecialchars($row['examination'], ENT_QUOTES); ?>',
            '<?= htmlspecialchars($row['university'], ENT_QUOTES); ?>',
            '<?= htmlspecialchars($row['college'], ENT_QUOTES); ?>',
            '<?= $row['pass_year']; ?>',
            '<?= $row['percentage']; ?>',
            '<?= htmlspecialchars($row['subject'], ENT_QUOTES); ?>'
        )">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button type="button" class="btn btn-danger btn-sm btn-label right ms-auto nexttab nexttab"
                    data-nexttab="pills-experience-tab" onclick="delete_education('<?= $row['education_id']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 ms-2"></i>Del</button>
            </td>
        </tr>

    <?php }
} else if ($type == 'document') {

    $details = $obj->executequery("Select * from emp_document where emp_id='$keyvalue' and unit_id='$unitid' order by emp_doc_id desc");

    foreach ($details as $row) {
        $doc_name = $obj->getvalfield("document_master", "document_name", "doc_id='$row[doc_id]'");
    ?>
        <tr>
            <td><?= $doc_name; ?> </td>
            <td><a href="<?= $imgpath1 . $row['doc_file'] ?>" class="btn btn-sm btn-primary" target="_blank">view</a></td>

            <td><?= $obj->dateformatindia($row['doc_expiry_date']); ?> </td>
            <td><?= $row['doc_remark']; ?> </td>

            <td>
                <button type="button"
                    class="btn btn-success btn-sm"
                    onclick="edit_document(
                '<?= $row['emp_doc_id']; ?>',
                '<?= $row['doc_id']; ?>',
                '<?= $row['doc_expiry_date']; ?>',
                '<?= addslashes($row['doc_remark']); ?>',
                '<?= $row['doc_file']; ?>'
            )">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button type="button" class="btn btn-danger btn-sm btn-label right ms-auto nexttab nexttab"
                    data-nexttab="pills-experience-tab" onclick="delete_document('<?= $row['emp_doc_id']; ?>','<?= $row['doc_file']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 ms-2"></i>Del</button>
            </td>
        </tr>

    <?php }
} else if ($type == 'language_known') {

    $details = $obj->executequery("Select * from emp_language where emp_id='$keyvalue' and unit_id='$unitid' order by emp_language_id desc");

    foreach ($details as $row) {
    ?>
        <tr>
            <td><?= $row['language_name']; ?> </td>
            <td><?= $row['is_speak'] == '1' ? 'Yes' : 'No'; ?> </td>
            <td><?= $row['is_read'] == '1' ? 'Yes' : 'No'; ?> </td>
            <td><?= $row['is_write'] == '1' ? 'Yes' : 'No'; ?> </td>

            <td>

                <button type="button"
                    class="btn btn-success btn-sm"
                    onclick="edit_language(
                '<?= $row['emp_language_id']; ?>',
                '<?= addslashes($row['language_name']); ?>',
                '<?= $row['is_speak']; ?>',
                '<?= $row['is_read']; ?>',
                '<?= $row['is_write']; ?>'
            )">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button type="button" class="btn btn-danger btn-sm btn-label right ms-auto nexttab nexttab"
                    data-nexttab="pills-experience-tab" onclick="delete_language('<?= $row['emp_language_id']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 ms-2"></i>Del</button>
            </td>
        </tr>
    <?php }
} else if ($type == 'bank_details') {

    $details = $obj->executequery("Select * from emp_bank_details where emp_id='$keyvalue' and unit_id='$unitid' order by 	emp_bank_id desc");


    foreach ($details as $row) {
        $bank_name = $obj->getvalfield("bank_master", "bank_name", "bank_id='$row[bank_id]'");
    ?>

        <tr>
            <td><?= $bank_name; ?> </td>
            <td><?= $row['branch_name']; ?> </td>
            <td><?= $row['acc_holder_name']; ?> </td>
            <td>AC : <?= $row['account_no']; ?> </td>
            <td><?= $row['ifsc_code']; ?> </td>


            <td class="text-center">
                <div class="d-flex align-items-center wt">
                    <div class="flex-grow-1 ms-2 name">
                        <label class="switch">
                            <input type="checkbox"
                                <?php if ($row['is_active'] == 1) echo "checked"; ?>>
                            <span class="slider round" onclick="change_status('<?php echo $row['emp_bank_id']; ?>');"> </span>
                        </label>
                    </div>
                </div>
            </td>
            <td>

                <button type="button"
                    class="btn btn-success btn-sm"
                    onclick="edit_bank(
                '<?= $row['emp_bank_id']; ?>',
                '<?= $row['bank_id']; ?>',
                '<?= addslashes($row['acc_holder_name']); ?>',
                '<?= $row['account_no']; ?>',
                '<?= $row['ifsc_code']; ?>',
                '<?= $row['is_active']; ?>',
                '<?= addslashes($row['branch_name']); ?>'
            )">
                    <i class="ri-edit-line"></i> Edit
                </button>
                <button type="button" class="btn btn-danger btn-sm btn-label right ms-auto nexttab nexttab"
                    data-nexttab="pills-experience-tab" onclick="delete_bank_details('<?= $row['emp_bank_id']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 ms-2"></i>Del</button>
            </td>
        </tr>

<?php }
} else if ($type == 'get_designation') {

    $department_id = $_REQUEST['department_id'];
    $designation_id = $_REQUEST['designation_id'] ?? 0;

    $options = "<option value=''>Please Select</option>";
    $selected = "";
    if ($department_id != "" || $department_id > 0) {

        $res = $obj->executequery("Select * from designation_master where unit_id='$unitid' and department_id='$department_id' order by designation asc");

        foreach ($res as $row) {
            $selected = ($designation_id == $row['designation_id']) ? 'selected' : '';
            $options .= "<option value='" . $row['designation_id'] . "' $selected>" . $row['designation'] . "  </option>";
        }
    }

    echo $options;
} ?>