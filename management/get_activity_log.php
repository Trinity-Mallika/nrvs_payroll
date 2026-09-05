<?php

include("../adminsession.php");

header('Content-Type: application/json');

$primary_id = (int)($_POST['primary_id'] ?? 0);
$page_type  = trim($_POST['page_type'] ?? '');
$tablename  = $_POST['tblname']??'';
$tblpkey  = $_POST['tblpkey']??'';

if ($primary_id <= 0) {

    echo json_encode([
        'status' => 'error',
        'msg'    => 'Invalid primary ID.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Page Mapping
|--------------------------------------------------------------------------
*/

$pageMapping = [

    'salary' => [
        'salary_generate.php',
        'salary_generate_detail.php',
        'salary_generate_report.php',
        'salary_apr.php'
    ],

];


/*
|--------------------------------------------------------------------------
| Validate Page Type
|--------------------------------------------------------------------------
*/

if (!isset($pageMapping[$page_type])) {

    echo json_encode([
        'status' => 'error',
        'msg'    => 'Invalid page type.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Page Condition
|--------------------------------------------------------------------------
*/

$pageConditions = [];

foreach ($pageMapping[$page_type] as $page) {

    $page = $obj->test_input($page);

    $pageConditions[] = "l.pagename = '$page'";
}

$pageCondition = implode(' OR ', $pageConditions);


/*
|--------------------------------------------------------------------------
| Fetch Activity + User Details
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        l.logactivity_id,
        l.primary_id,
        l.flag,
        l.activity_type,
        l.createdby,
        l.pagename,
        l.created_date,
        l.created_time,
        l.ipaddress,

        u.username,
        u.fullname,
        u.mobile

    FROM logactivity_master l

    LEFT JOIN user u
        ON u.userid = l.createdby

    WHERE l.primary_id = '$primary_id'

    AND (
        $pageCondition
    )

    ORDER BY l.logactivity_id DESC
";


$logRows = $obj->executequery($sql);

$entrySql = "
    SELECT
        t.createdby,
        t.createdate,
        u.fullname,
        u.username
    FROM $tablename t
    LEFT JOIN user u ON u.userid = t.createdby
    WHERE t.$tblpkey = '$primary_id'
";

$entryRow = $obj->executequery($entrySql);

$entryData = $obj->executequery($entrySql);
$entryRow = $entryData[0] ?? [];
/*
|--------------------------------------------------------------------------
| Generate HTML
|--------------------------------------------------------------------------
*/

ob_start();

?>

<div class="table-responsive">

    <?php if(!empty($entryRow)){ ?>

<div class="card mb-3">
    <div class="card-body p-2">

        <div class="row">

            <div class="col-md-6">
                <strong>Entry By :</strong>

                <?= htmlspecialchars(
                    $entryRow['fullname']
                    ?? $entryRow['username']
                    ?? ''
                ); ?>
            </div>

            <div class="col-md-6">
                <strong>Entry Date :</strong>

                <?= $obj->dateformatindia(
                    $entryRow['createdate']
                ); ?>
            </div>

        </div>

    </div>
</div>

<?php } ?>

    <table class="table table-bordered table-hover align-middle mb-0">

        <thead class="table-light">

            <tr>

                <th width="60">
                    Sr.
                </th>

                <th>
                    Activity
                </th>

                <th>
                    Type
                </th>

                <th>
                    Page
                </th>

                <th>
                    User
                </th>

                <th>
                    Mobile
                </th>

                <th>
                    Date
                </th>

                <th>
                    Time
                </th>

            </tr>

        </thead>

        <tbody>

            <?php if (empty($logRows)) { ?>

                <tr>

                    <td colspan="8"
                        class="text-center text-muted py-4">

                        No activity found.

                    </td>

                </tr>

            <?php } else { ?>

                <?php

                $sr = 1;

                foreach ($logRows as $log) {

                    $type = $log['activity_type'] ?? '';

                ?>

                    <tr> 
                        <td class="text-center">
                            <?= $sr++; ?>
                        </td> 
                        <td>

                            <?= htmlspecialchars(
                                $log['flag'] ?? ''
                            ); ?>

                        </td> 
                        <td> 
                            <?php if ($type == 'Updated') { ?>

                                <span class="badge bg-primary">
                                    Updated
                                </span>

                            <?php } elseif ($type == 'Created') { ?>

                                <span class="badge bg-success">
                                    Created
                                </span>

                            <?php } elseif ($type == 'Deleted') { ?>

                                <span class="badge bg-danger">
                                    Deleted
                                </span>

                            <?php } else { ?>

                                <span class="badge bg-secondary">
                                    <?= htmlspecialchars($type); ?>
                                </span>

                            <?php } ?>

                        </td> 
                        <td>

                            <?= htmlspecialchars(
                                $log['pagename'] ?? ''
                            ); ?>

                        </td> 
                        <td>

                            <div>
                                <strong>
                                    <?= htmlspecialchars(
                                        $log['fullname'] ?? ''
                                    ); ?>
                                </strong>
                            </div>

                            <small class="text-muted">
                                <?= htmlspecialchars(
                                    $log['username'] ?? ''
                                ); ?>
                            </small>

                        </td> 
                        <td>

                            <?= htmlspecialchars(
                                $log['mobile'] ?? ''
                            ); ?>

                        </td> 
                        <td>

                            <?= htmlspecialchars(
                               $obj->dateformatindia($log['created_date'])
                            ); ?>

                        </td> 
                        <td>

                            <?= htmlspecialchars(
                                $log['created_time'] ?? ''
                            ); ?>

                        </td>

                    </tr>

                <?php } ?>

            <?php } ?>

        </tbody>

    </table>

</div>

<?php

/*
|--------------------------------------------------------------------------
| Store Generated HTML
|--------------------------------------------------------------------------
*/

$html = ob_get_clean();


/*
|--------------------------------------------------------------------------
| JSON Response
|--------------------------------------------------------------------------
*/

echo json_encode([
    'status' => 'success',
    'html'   => $html
]);

exit;