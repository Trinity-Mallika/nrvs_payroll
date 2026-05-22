<?php
include("../adminsession.php");

$loan_advance_id = $obj->test_input($_POST['loan_advance_id']);
$emp_id = $obj->test_input($_POST['emp_id']);
$detail_crit = "WHERE loan_advance_id='$loan_advance_id' and unit_id='$unitid'";
$sn = 1;
$details = $obj->executequery("SELECT * FROM loan_advance_details $detail_crit order by loan_details_id asc");
?>
<table class="table table-bordered table-sm">
    <thead class="table-light text-center">
        <tr>
            <th>Sr No</th>
            <th>Month Year</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Deduction Date</th>
        </tr>
    </thead>
    <tbody id="modal_installment_tbody">

        <?php foreach ($details as $row) {
            $isReadonly = ($row['is_paid'] == 1) ? 'readonly' : '';
            $isDisabled = ($row['is_paid'] == 1) ? 'disabled' : '';
        ?>
            <tr>
                <td class="text-center"><?= $sn++; ?></td>
                <td class="d-flex">
                    <select name="modal_month[]" class="form-select chosen-select form-select-sm" <?= $isDisabled ?>>
                        <option value="">Select Month</option>
                        <?php
                        $months = [
                            1 => "January",
                            2 => "February",
                            3 => "March",
                            4 => "April",
                            5 => "May",
                            6 => "June",
                            7 => "July",
                            8 => "August",
                            9 => "September",
                            10 => "October",
                            11 => "November",
                            12 => "December"
                        ];

                        foreach ($months as $key => $month) {
                            $selected = ($row['month'] == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$month</option>";
                        }
                        ?>
                    </select>
                    <span class="m-1">-</span>
                    <!-- Year -->
                    <select class="form-select form-select-sm chosen-select" name="modal_year[]" <?= $isDisabled ?>>
                        <option value="">Select</option>
                        <?php
                        for ($year1 = 2025; $year1 <= 2100; $year1++) {
                            $selected = ($row['year'] == $year1) ? 'selected' : '';
                            echo "<option value='$year1' $selected>$year1</option>";
                        }
                        ?>
                    </select>

                </td>
                <td>
                    <input type="number"
                        name="installment_amount[]"
                        class="form-control form-control-sm text-center inst_amt"
                        value="<?= $row['amount'] ?>"
                        onkeyup="updateInstallmentTotal()"
                        onchange="updateInstallmentTotal()" <?= $isReadonly ?>>
                </td>
                <td>
                    <input type="text" name="installment_remark[]"
                        class="form-control form-control-sm" <?= $isReadonly ?> value="<?= $row['remark'] ?>" />
                </td>
                <td><?= $obj->dateformatindia($row['paid_date']); ?></td>
            </tr>
        <?php } ?>

    </tbody>
</table>