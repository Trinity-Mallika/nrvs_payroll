<?php
include("../adminsession.php");

$emp_id = $_POST['emp_id'];

$res = $obj->executequery("
    SELECT *
    FROM loan_advance
    WHERE emp_id='$emp_id'
    ORDER BY loan_advance_id DESC
");
?>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-primary">
        <tr>
            <th width="50">+</th>
            <th>Sr No</th>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Interest Amt</th>
            <th>Total Amount</th>
            <th>No Of Installment</th>
            <th>Installment Amount</th>
            <th>Paid Amount</th>
            <th>Balance</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $sr = 1;
        foreach ($res as $row) {
            if ($row['appr_status'] == 1) {
                $status = '<span class="badge bg-success">Approved</span>';
            } else {
                $status = '<span class="badge bg-warning">Pending</span>';
            }

            $loan_advance_id = $row['loan_advance_id'];

            $total_paid = $obj->getvalfield("loan_advance_details","amount","loan_advance_id='$loan_advance_id' and is_paid=1");
            $balance=$row['total_amount']-$total_paid;
        ?>

        <!-- Main Row -->
        <tr>

            <td class="text-center">

                <a href="javascript:void(0)" onclick="toggleDetails('detail<?= $loan_advance_id ?>')">

                    <i class="ri-add-circle-fill text-primary fs-5"></i>

                </a>

            </td>

            <td><?= $sr++ ?></td>

            <td>
                <?= date('d-m-Y', strtotime($row['loan_date'])) ?>
            </td>

         <td>
    <?php if ($row['type'] == 'Loan') { ?>

        <span class="badge bg-danger">
            <?= $row['type'] ?>
        </span>

    <?php } else { ?>

        <span class="badge bg-primary">
            <?= $row['type'] ?>
        </span>

    <?php } ?>
</td>
              <td>
                ₹ <?= number_format($row['loan_adv_amt'], 2) ?>
            </td>
              <td>
                ₹ <?= number_format($row['interest_amount'], 2) ?>
            </td>

            <td>
                ₹ <?= number_format($row['total_amount'], 2) ?>
            </td>

            <td>
                <?= $row['no_of_inst'] ?>
            </td>

            <td>
                ₹ <?= number_format($row['inst_amount'], 2) ?>
            </td>
            <td>
                ₹ <?= number_format($total_paid, 2) ?>
            </td>
            <td>
                ₹ <?= number_format($balance, 2) ?>
            </td>

            <td>
                <?= $status ?>
            </td>

        </tr>

        <!-- Detail Row -->
        <tr id="detail<?= $loan_advance_id ?>" style="display:none;">

            <td colspan="12">

                <div class="table-responsive">

                    <table class="table table-sm table-bordered mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Sr No</th>
                                <th>Month-Year</th>
                                <th>Amount</th>
                                <th>Remark</th>
                                <th>Paid Status</th>
                                <th>Paid Date</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php
                                $details = $obj->executequery("
                                    SELECT *
                                    FROM loan_advance_details
                                    WHERE loan_advance_id='$loan_advance_id'
                                    ORDER BY loan_details_id ASC
                                ");

                                $d_sr = 1;

                                foreach ($details as $d) {

                                    $monthName = date(
                                        "F",
                                        mktime(0, 0, 0, $d['month'], 1)
                                    );

                                    if ($d['is_paid'] == 1) {

                                        $paid_status = '
                                            <span class="badge bg-success">
                                                Paid
                                            </span>
                                        ';

                                        $paid_date = !empty($d['paid_date'])
                                            ? date(
                                                'd-m-Y',
                                                strtotime($d['paid_date'])
                                            )
                                            : '-';

                                    } else {

                                        $paid_status = '
                                            <span class="badge bg-danger">
                                                Unpaid
                                            </span>
                                        ';

                                        $paid_date = '-';
                                    }
                                ?>

                            <tr>

                                <td>
                                    <?= $d_sr++ ?>
                                </td>

                                <td>
                                    <?= $monthName ?>
                                    -
                                    <?= $d['year'] ?>
                                </td>

                                <td>
                                    ₹ <?= number_format($d['amount'], 2) ?>
                                </td>

                                <td>
                                    <?= $d['remark'] ?>
                                </td>

                                <td>
                                    <?= $paid_status ?>
                                </td>

                                <td>
                                    <?= $paid_date ?>
                                </td>

                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </td>

        </tr>

        <?php } ?>

    </tbody>

</table>

<script>
function toggleDetails(id) {

    $("#" + id).toggle();

}
</script>