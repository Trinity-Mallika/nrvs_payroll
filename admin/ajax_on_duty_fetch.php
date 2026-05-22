<?php include_once("../adminsession.php");

$keyvalue = $obj->test_input($_POST['keyvalue']);

$details = $obj->executequery("Select * from on_duty_details where on_duty_id='$keyvalue' and unit_id='$unitid'   order by on_duty_details_id desc");
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
            <button type="button" class="btn btn-danger btn-sm btn-label right ms-auto nexttab nexttab"
                data-nexttab="pills-experience-tab" onclick="funDel('<?= $row['on_duty_details_id']; ?>');"><i
                    class="ri-delete-bin-5-line label-icon align-middle fs-14 ms-2"></i>Del</button>
        </td>
    </tr>
<?php }  ?>