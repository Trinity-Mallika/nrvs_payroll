<?php include_once("appsession.php");

$keyvalue = $obj->test_input($_POST['keyvalue']);

$details = $obj->executequery("Select * from on_duty_details where on_duty_id='$keyvalue' and createdby='$emp_id' order by date asc");
$sno = 1;
foreach ($details as $row) {

?>
<div class="card card-body p-2 mb-2 position-relative">

    <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
        <?php if($row['status']!=1){ ?>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" title="Edit" onclick="editOnduty(
               '<?= $row['on_duty_details_id']; ?>',
               '<?= $row['date']; ?>',
               '<?= $row['intime']; ?>',
               '<?= $row['outtime']; ?>',
               '<?= $row['place']; ?>',
               '<?= $row['with_employee']; ?>',
               '<?= htmlspecialchars($row['remark'], ENT_QUOTES); ?>'
                );">
            <i class="bi bi-pencil-fill"></i>
        </a>
        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete"
            onclick="funDel('<?= $row['on_duty_details_id'] ?>')">
            <i class="bi bi-trash-fill"></i>
        </a>
        <?php }?>
    </div>
    <table class="table table-borderless table-sm mb-0" style="font-size: 14px;">
        <tr>
            <td width="40%">Date</td>
            <td>: </td>
            <th class="text-primary-emphasis"><?= $obj->dateformatindia($row['date']); ?> </th>
        </tr>
        <tr>
            <td>In Time</td>
            <td>: </td>
            <th class="text-primary-emphasis"><?= $row['intime']; ?></th>
        </tr>
        <tr>
            <td>Out Time</td>
            <td>: </td>
            <th class="text-primary-emphasis"><?= $row['outtime']; ?></th>
        </tr>
        <tr>
            <td>Place</td>
            <td>: </td>
            <th class="text-primary-emphasis"><?= $row['place']; ?></th>
        </tr>
        <tr>
            <td>With Employee</td>
            <td>: </td>
            <th class="text-primary-emphasis"><?= $row['with_employee']; ?> </th>
        </tr>
        <tr>
            <td>Remark</td>
            <td>: </td>
            <th class="text-primary-emphasis"><?= $row['remark']; ?> </th>
        </tr>
    </table>
</div>

<?php }  ?>