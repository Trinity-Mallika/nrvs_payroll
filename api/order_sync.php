<?php
ini_set('max_execution_time', 300);
include("../action.php");

header('Content-Type: application/json; charset=utf-8');

/* HELPER RESPONSE */

function apiResponse($status, $message, $data = [], $httpCode = 200)
{
    http_response_code($httpCode);

    echo json_encode([
        'status'  => $status,
        'message' => $message,
        'data'    => $data
    ]);

    exit;
}


/* ONLY POST METHOD ALLOWED */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    apiResponse(
        false,
        'Only POST method is allowed.',
        [],
        405
    );
}


/* READ JSON INPUT */

$input = file_get_contents('php://input');
if (empty($input)) {
    apiResponse(
        false,
        'Request body is required.',
        [],
        400
    );
} 

$data = json_decode($input, true);
 
/* VALID JSON CHECK */
if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    apiResponse(
        false,
        'Invalid JSON request.',
        [],
        400
    );
}  
/* CUSTOMER ID VALIDATION */
$customer_id = $data['customer_id'] ?? '';
$local_uuid = $data['local_uuid'] ?? '';  

if ( $customer_id === '') {
    apiResponse(
        false,
        'Valid customer is required.',
        [],
        422
    );
} 

/* TOTAL AMOUNT VALIDATION */

$total_amount = $data['total_amount'] ?? '';

if (
    $total_amount === '' ||
    !is_numeric($total_amount) ||
    (float)$total_amount < 0
) {
    apiResponse(
        false,
        'Valid total_amount is required.',
        [],
        422
    );
}

$total_amount = (float)$total_amount;  
/* ITEMS VALIDATION */

if (!isset($data['items']) || !is_array($data['items']) || count($data['items']) == 0
) { 
    apiResponse(
        false,
        'At least one item is required.',
        [],
        422
    );
}
/* VALIDATE ALL ITEMS FIRST */
$items = [];
$calculatedTotal = 0;
foreach ($data['items'] as $index => $item) {
    /* PRODUCT ID */
    $product_id = $item['product_id'] ?? '';
    if ($product_id === '' ) {
        apiResponse(
            false,
            "Invalid product_id at item " . ($index + 1) . ".",
            [],
            422
        );
    } 
    $quantity = $item['quantity'] ?? '';
    if ($quantity === '' || !is_numeric($quantity) || (float)$quantity <= 0
    ) {
        apiResponse(
            false,
            "Invalid quantity at item " . ($index + 1) . ".",
            [],
            422
        );
    }
    $quantity = (float)$quantity;

    $rate = $item['rate'] ?? '';
    if ($rate === '' || !is_numeric($rate) || (float)$rate < 0) {
        apiResponse(
            false,
            "Invalid rate at item " . ($index + 1) . ".",
            [],
            422
        );
    }
    $rate = (float)$rate;

    $amount = $item['amount'] ?? '';
    if ($amount === '' ||!is_numeric($amount) || (float)$amount < 0) {
        apiResponse(
            false,
            "Invalid amount at item " . ($index + 1) . ".",
            [],
            422
        );
    }
    $amount = (float)$amount;
    $expectedAmount = round($quantity * $rate,2);

    if (abs($expectedAmount - $amount) > 0.01) {
        apiResponse(
            false,
            "Amount mismatch at item " . ($index + 1) .
            ". Expected " . number_format($expectedAmount, 2, '.', '') .
            ", received " . number_format($amount, 2, '.', '') . ".",
            [],
            422
        );
    }

    /* ADD ITEM */
    $items[] = [
        'product_id' => $product_id,
        'qty'        => $quantity,
        'rate'       => $rate,
        'amount'     => $amount
    ];
    $calculatedTotal += $amount;
}


/* VERIFY ORDER TOTAL */

$calculatedTotal = round($calculatedTotal,2);

if (abs($calculatedTotal - $total_amount) > 0.01) {
    apiResponse(
        false,
        'total_amount mismatch. Calculated total is ' .
        number_format($calculatedTotal, 2, '.', '') .
        ' but received total_amount is ' .
        number_format($total_amount, 2, '.', '') . '.',
        [],
        422
    );
}

$insertedItems = [];
try {
    /* START TRANSACTION */
  $sessionid = $obj->getvalfield("m_session", "sessionid", "status=1");
    foreach ($items as $item) {
        $insertData = [
            'cust_id'    => $customer_id,
            'local_uuid'    => $local_uuid,
            'product_id' => $item['product_id'], 
            'qty'        => $item['qty'], 
            'rate'       => $item['rate'], 
            'amount'     => $item['amount'], 
            // 'createdby'  => $loginid,  
            'createdate' => date('Y-m-d H:i:s'),
            'lastupdated'=> date('Y-m-d H:i:s'),
            'sessionid'  => $sessionid
        ];
        $insertId = $obj->insert_record_lastid(
            "order_sync",
            $insertData
        );

        if (!$insertId) {
            throw new Exception(
                'Failed to insert order item.'
            );
        }

        $insertedItems[] = [
            'order_sync_id' => $insertId,
            'product_id'    => $item['product_id'],
            'quantity'      => $item['qty'],
            'rate'           => $item['rate'],
            'amount'        => $item['amount']
        ];
    }

    /*
    --------------------------------------------------------
    COMMIT
    --------------------------------------------------------
    */
 

    /*
    ========================================================
    SUCCESS RESPONSE
    ========================================================
    */

    apiResponse(
        true,
        'Order synced successfully.',
        [
            'local_uuid'     => $local_uuid,
            'customer_id'    => $customer_id,
            'order_date'     =>date('Y-m-d H:i:s'),
            'total_amount'   => $total_amount,
            'total_items'    => count($insertedItems),
            'items'          => $insertedItems
        ],
        200
    );


} catch (Exception $e) {

    /*
    --------------------------------------------------------
    ROLLBACK
    --------------------------------------------------------
    */
 

    apiResponse(
        false,
        'Order sync failed.',
        [],
        500
    );
}
 
