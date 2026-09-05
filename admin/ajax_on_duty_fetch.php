<?php include_once("../adminsession.php");

$keyvalue = $obj->test_input($_POST['keyvalue']);

$details = $obj->executequery("Select * from on_duty_details where on_duty_id='$keyvalue' and unit_id='$unitid'   order by date asc");
$sno = 1;
foreach ($details as $row) {

?>
    <tr>
        <td><?= $sno++ . ")"; ?> </td>
        <td><?= $obj->dateformatindia($row['date']); ?> </td>
        <td><?= $row['intime']; ?> </td>
        <td><?= $row['outtime']; ?> </td>
        <td><?= $row['place']; ?> </td>
        <td><?= $row['with_employee']; ?> </td>
        <td><?= $row['remark']; ?> </td>

        <td>
            <?php if ($row['status'] == 0) {  ?>
                <button type="button" class="btn btn-danger btn-sm"
                    data-nexttab="pills-experience-tab" onclick="funDel('<?= $row['on_duty_details_id']; ?>');"><i
                        class="ri-delete-bin-5-line label-icon align-middle fs-14 "></i>Del</button>
                <button type="button" class="btn btn-primary btn-sm"
                    onclick="editOnDuty('<?= $row['on_duty_details_id']; ?>','<?= $row['date']; ?>','<?= $row['intime']; ?>','<?= $row['outtime']; ?>','<?= $row['place']; ?>','<?= $row['with_employee']; ?>','<?= $row['remark']; ?>');">Edit</button>
            <?php } elseif ($row['status'] == 1) { ?>
                <span class="badge bg-success text-white">Approved</span>
            <?php } elseif ($row['status'] == 2) { ?>
                <span class="badge bg-danger text-white">Rejected</span>
            <?php } else { ?>
                <span class="badge bg-warning text-white">Pending</span>
            <?php } ?>
        </td>

        <td>
            
        </td>
    </tr>
<?php }  ?>