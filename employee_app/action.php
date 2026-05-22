<?php

session_start();

include("config.php");



class DataOperation extends Database
{

	public function pre($val)
	{

		echo "<pre>";

		print_r($val);

		echo "</pre>";
	}



	public function getSequence($num)
	{

		return sprintf("%'.06d\n", $num + 1);
	}


	public function push_notification_android($device_id, $title, $message, $gourl = '')
	{

		//API URL of FCM
		$url = 'https://fcm.googleapis.com/fcm/send';
		/*api_key available in:
														  Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
		$api_key = 'AAAAB5iOW1k:APA91bFPferQL3p7UiF84lphBIQoYCkJCD1Quh7cQdjwfHJiM_TbowTRue47Dg_uOjf8I8JFkQS744EsKgG3n2tkQo67BfHceRkmnXr-0xtXJS1D-ID5SlAvUpRShoOfQ3ZAZCX7cFHc';

		$fields = array(
			'registration_ids' => array(
				$device_id
			),
			'data' => array(
				"title" => $title,
				"body" => $message,
				"url" => $gourl
			)
		);

		//header includes Content type and api key
		$headers = array(
			'Content-Type:application/json',
			'Authorization:key=' . $api_key
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);

		if ($result === FALSE) {
			die('FCM Send Error: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	function send_notification($tokens, $message_complete)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			'registration_ids' => $tokens,
			'notification' => $message_complete
		);

		$headers = array(
			'Authorization:key = AAAAB5iOW1k:APA91bFPferQL3p7UiF84lphBIQoYCkJCD1Quh7cQdjwfHJiM_TbowTRue47Dg_uOjf8I8JFkQS744EsKgG3n2tkQo67BfHceRkmnXr-0xtXJS1D-ID5SlAvUpRShoOfQ3ZAZCX7cFHc', //Change API KEY HERE
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		$result = curl_exec($ch);

		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}


	function getSundays($year_month)
	{
		$date = $year_month . "-01";
		$first_day = date('N', strtotime($date));
		$first_day = 7 - $first_day + 1;
		$last_day = date('t', strtotime($date));
		$days = array();
		for ($i = $first_day; $i <= $last_day; $i = $i + 7) {
			$days[] = $i;
		}
		return $days;
	}


	public function login_method_app($table, $mobile_no, $password)
	{

		$count = 0;

		$sql = "SELECT * from $table WHERE (mob_no='$mobile_no') AND emp_password='$password' AND status='1'";

		$query = mysqli_query($this->con, $sql);

		$count = mysqli_num_rows($query);
		//print_r($count);die;

		if ($count > 0) {

			$row = mysqli_fetch_array($query);

			$_SESSION['emp_id'] = $row['emp_id'];

			$_SESSION['emp_type'] = $row['emp_type'];

			setcookie("TA_id", $row['emp_id'], time() + 3600 * 365, "/", "", 0);

			setcookie("TA_mobile_no", $mobile_no, time() + 3600 * 365, "/", "", 0);

			setcookie("TA_password", $password, time() + 3600 * 365, "/", "", 0);

			return $count;

			// echo $sql;

			// die;

		}
	}

	function compressAndMoveImage($imagePath, $destinationDir, $quality = 75)
	{
		// Check if the file exists
		if (!file_exists($imagePath)) {
			return false;
		}

		// Get image information
		$info = getimagesize($imagePath);
		if ($info === false) {
			return false; // Invalid image or unsupported format
		}

		// Determine the extension from MIME type
		$mime = $info['mime'];
		$extension = '';

		switch ($mime) {
			case 'image/jpeg':
				$extension = 'jpg';
				break;
			case 'image/gif':
				$extension = 'gif';
				break;
			case 'image/png':
				$extension = 'png';
				break;
			default:
				return false; // Unsupported image type
		}

		// Create a new, unique file name
		$newFileName = uniqid() . '.' . $extension;
		$destinationPath = rtrim($destinationDir, '/') . '/' . $newFileName;

		// Create image resource based on file type
		$image = null;
		$success = false;

		if ($mime == 'image/jpeg') {
			$image = imagecreatefromjpeg($imagePath);
			if ($image) {
				$success = imagejpeg($image, $destinationPath, $quality);
			}
		} elseif ($mime == 'image/gif') {
			$image = imagecreatefromgif($imagePath);
			if ($image) {
				$success = imagegif($image, $destinationPath);
			}
		} elseif ($mime == 'image/png') {
			$image = imagecreatefrompng($imagePath);
			if ($image) {
				// PNG compression level (0 - no compression, 9 - max compression)
				$success = imagepng($image, $destinationPath, round($quality / 10));
			}
		}

		// Free up memory
		if ($image) {
			imagedestroy($image);
		}

		// Return the new file name if successful, or false if there was an error
		return $success ? $newFileName : false;
	}
	function uploadImageExt($imgpath, $docname, $i = 0)
	{







		if (1 == 1) {







			$doc_name = $docname['name'][$i];



			$tm = "DOC";



			$tm .= microtime(true) * 1000;



			$ext = pathinfo($doc_name, PATHINFO_EXTENSION);



			$doc_name = $tm . "." . $ext;



			//echo $imgpath."$doc_name";die;



			if (move_uploaded_file($docname['tmp_name'][$i], $imgpath . "$doc_name")) {



				// echo ($imgpath.$doc_name);die;



				return ($doc_name);
			} else {



				return ("");
			}
		} else {



			return ("0");
		}
	}

	public function get_utf8()
	{

		// mysqli_set_charset($this->con, "utf8mb4");

		$sqledit = mysqli_query($this->con, "set collation_connection='utf8'");

		return $sqledit;
	}


	//$latitude, $longitude for emp
	//$branch_latitude, $branch_longitude for branch
	public function calculateDistance($latitude, $longitude, $branch_latitude, $branch_longitude, $redius = 1)
	{
		// Earth radius in meters
		$earthRadius = 6371000;

		// Convert degrees to radians
		$lat1 = deg2rad($latitude);
		$lon1 = deg2rad($longitude);
		$lat2 = deg2rad($branch_latitude);
		$lon2 = deg2rad($branch_longitude);

		// Calculate the differences
		$deltaLat = $lat2 - $lat1;
		$deltaLon = $lon2 - $lon1;

		// Apply Haversine formula
		$a = sin($deltaLat / 2) * sin($deltaLat / 2) +
			cos($lat1) * cos($lat2) *
			sin($deltaLon / 2) * sin($deltaLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		// Calculate distance
		$distance = $earthRadius * $c;


		//return $distance; // Distance in meters
		$distance = round($distance);

		if ($distance <= $redius)
			$exist = "true";
		else
			$exist = "false";

		return $exist; // Distance in meters
	}


	function uploadImagename($imgpath, $docname)
	{



		if (1 == 1) {



			$doc_name = $docname['name'];

			$tm = "DOC";

			$tm .= microtime(true) * 1000;

			$ext = pathinfo($doc_name, PATHINFO_EXTENSION);

			$doc_name = $tm . "." . $ext;

			//echo $imgpath."$doc_name";die;

			if (move_uploaded_file($docname['tmp_name'], $imgpath . "$doc_name")) {

				//echo ($imgpath.$doc_name);die;

				return ($doc_name);
			} else {

				return ("");
			}
		} else {

			return ("0");
		}
	}



	function uploadImage($imgpath, $docname)
	{



		if (1 == 1) {



			$doc_name = $docname['name'];

			$tm = "DOC";

			$tm .= microtime(true) * 1000;

			$ext = pathinfo($doc_name, PATHINFO_EXTENSION);

			$doc_name = $tm . "." . $ext;

			//echo $imgpath."$doc_name";die;

			if (move_uploaded_file($docname['tmp_name'], $imgpath . "$doc_name")) {

				// echo ($imgpath.$doc_name);die;

				return ($doc_name);
			} else {

				return ("");
			}
		} else {

			return ("0");
		}
	}

	function getTotalBillAmt($id)
	{

		$total = 0;

		//echo "Select disc,rate,qty from saleentry_detail where saleid = '$id'";



		$sql = mysqli_query($this->con, "Select * from saleentry_detail where saleid = '$id'");

		if ($sql) {



			while ($row = mysqli_fetch_assoc($sql)) {

				$ret_qty = $this->getvalfield("salereturn", "sum(ret_qty)", "saleid='$id' && productid='$row[productid]'");

				$qty = $row['qty'] - $ret_qty;

				$rate = $row['rate'];

				$gst = $row['cgst'] + $row['sgst'];

				$igst = $row['igst'];

				$vat = $row['vat'];

				$disc = $row['disc'];

				$subtotal = $rate * $qty;



				$subtotal = $subtotal - $disc;



				$total += $subtotal;

				//echo $total;

			}
		}





		return round($total);
	}





	function getTotalIgst_Sale($id)
	{

		$total = 0;
		$igsttotal = 0;

		//echo "Select * from purchasentry_detail where purchaseid = '$id'";die;

		$sql = mysqli_query($this->con, "Select * from saleentry_detail where saleid = '$id'");

		if ($sql) {



			while ($row = mysqli_fetch_assoc($sql)) {

				$taxamt = 0;

				$ret_qty = $this->getvalfield("salereturn", "sum(ret_qty)", "saleid='$id' && productid='$row[productid]'");

				$qty = $row['qty'] - $ret_qty;

				$rate = $row['rate'];

				$disc = $row['disc'];



				if ($row['igst'] != '0') {

					$tax = $row['igst'];

					$total = $qty * $rate;



					if ($disc != '0') {

						$total = $total - $disc;
					}

					$taxamt = ($total * $tax) / 100;

					$igsttotal += $taxamt;
				}
			}
		}



		return $igsttotal;
	}



	function getrec($tablename, $tablepkey, $cond)
	{

		$num = $this->getvalfield($tablename, "max($tablepkey)", "$cond");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 5; ++$i) {

			$num = '0' . $num;
		}

		$num = 'rec' . $num;

		return $num;
	}



	function getTotalGst($id)
	{

		$total = 0;
		$stotal = 0;


		$sql = mysqli_query($this->con, "Select * from saleentry_detail where saleid = '$id'");

		if ($sql) {



			while ($row = mysqli_fetch_assoc($sql)) {

				$taxamt = 0;

				$ret_qty = $this->getvalfield("salereturn", "sum(ret_qty)", "saleid='$id' && productid='$row[productid]'");

				$qty = $row['qty'] - $ret_qty;

				$rate = $row['rate'];

				$disc = $row['disc'];



				if ($row['cgst'] != '0' && $row['sgst'] != '0') {

					$tax = $row['cgst'] + $row['sgst'];
				}





				$total = $qty * $rate;



				if ($disc != '0') {

					$total = $total - $disc;
				}



				$taxamt = ($total * $tax) / 100;



				$stotal += $taxamt;
			}
		}



		return $stotal;
	}





	public function count_cart_products()
	{



		if (isset($_SESSION['items'])) {

			$count = sizeof($_SESSION['items']);

			$itemcart = array_count_values($_SESSION['items']);

			$countrows = sizeof($itemcart);
		} else {

			$countrows = 0;
		}

		return $countrows;
	}



	function cartamt()
	{

		$countrows = 0;

		$total = 0;

		if (isset($_SESSION['items'])) {

			$itemcart = array_count_values($_SESSION['items']);

			$countrows = sizeof($itemcart);
		}



		if ($countrows > 0) {

			//print_r($itemcart);die;

			foreach ($itemcart as $key => $value) {

				$rate = $this->getvalfield("item_master", "rate", "item_id='$key'");

				$amt = $value * $rate;

				$total += $amt;
			}
		}

		return $total;
	}



	public function check_cart_empty()
	{

		if (isset($_SESSION['items'])) {

			$count = sizeof($_SESSION['items']);
		} else {

			$count = 0;
		}

		return $count;
	}



	public function my_simple_crypt($string, $action = 'e')
	{

		// you may change these values to your own

		$secret_key = 'trinitysolutionsraipur';

		$secret_iv = 'my_simple_secret_iv';



		$output = false;

		$encrypt_method = "AES-256-CBC";

		$key = hash('sha256', $secret_key);

		$iv = substr(hash('sha256', $secret_iv), 0, 16);



		if ($action == 'e') {

			$output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
		} else if ($action == 'd') {

			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}



		return $output;
	}



	function getRealIpAddr()
	{

		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{

			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{

			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {

			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}



	public function session_method($table, $username, $password)
	{

		$sql = "SELECT * from $table WHERE (username='$username') AND password='$password'";

		$query = mysqli_query($this->con, $sql);

		$row = mysqli_fetch_array($query);

		return $row;
	}



	public function session_method1($table, $username, $password)
	{

		$sql = "SELECT * from ecounter WHERE (email='$username') AND password='$password'";

		$query = mysqli_query($this->con, $sql);

		$row = mysqli_fetch_array($query);

		return $row;
	}



	public function max_method($tbl_id, $date)
	{

		$sql = "SELECT MAX(kotnumber) FROM kot_entry WHERE tbl_id=$tbl_id AND created_date = '$date'";

		$query = mysqli_query($this->con, $sql);

		$row = mysqli_fetch_array($query);

		return $row['MAX(kotnumber)'];
	}



	public function last_val($column1, $table, $column2)
	{

		$sql = "SELECT $column1 FROM $table ORDER BY $column2 DESC LIMIT 1";

		$query = mysqli_query($this->con, $sql);

		$row = mysqli_fetch_array($query);

		return $row['billnumber'];
	}





	public function gst_calculation($qty, $rate, $disc_per = 0, $cgst, $sgst, $igst, $inc_or_exc = 'exclusive')
	{



		$total_value = $qty * $rate;

		$disc_amt = $total_value * $disc_per / 100;

		$taxable_value = $total_value - $disc_amt;



		if ($inc_or_exc == 'exclusive') {

			if ($sgst > 0)

				$sgst_amt = round(($taxable_value * $sgst / 100), 2);
			else

				$sgst_amt = 0;



			if ($cgst > 0)

				$cgst_amt = round(($taxable_value * $cgst / 100), 2);
			else

				$cgst_amt = 0;



			if ($igst > 0)

				$igst_amt = round(($taxable_value * $igst / 100), 2);
			else

				$igst_amt = 0;



			$final_price = $taxable_value + $sgst_amt + $cgst_amt + $igst_amt;



			$all_values = array('taxable_value' => $taxable_value, 'sgst_amt' => $sgst_amt, 'cgst_amt' => $cgst_amt, 'igst_amt' => $igst_amt, 'final_price' => $final_price);
		} else {

			$final_price = $taxable_value;

			$taxable_value = round((100 * $final_price) / (100 + $sgst + $cgst + $igst), 2);





			if ($sgst > 0)

				$sgst_amt = round(($taxable_value * $sgst / 100), 2);
			else

				$sgst_amt = 0;



			if ($cgst > 0)

				$cgst_amt = round(($taxable_value * $cgst / 100), 2);
			else

				$cgst_amt = 0;



			if ($igst > 0)

				$igst_amt = round(($taxable_value * $igst / 100), 2);
			else

				$igst_amt = 0;





			$all_values = array('taxable_value' => $taxable_value, 'sgst_amt' => $sgst_amt, 'cgst_amt' => $cgst_amt, 'igst_amt' => $igst_amt, 'final_price' => $final_price);
		}



		return ($all_values);

		// echo $all_values;



	}





	public function executequery($sql)
	{

		$array = array();



		$query = mysqli_query($this->con, $sql);



		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}



	public function dbRowInsert($table_name, $form_data)
	{

		// retrieve the keys of the array (column titles)

		$fields = array_keys($form_data);



		// build the query

		$sql = "INSERT INTO " . $table_name . "

    (`" . implode('`,`', $fields) . "`)

    VALUES('" . implode("','", $form_data) . "')";

		//echo $sql;

		//die;

		// run and return the query result resource

		return mysqli_query($this->con, $sql);

		// echo $sql;die;

	}



	public function insert_record($table, $fields, $print = 0)
	{

		//"INSERT INTO table_name ( , , ) VALUE ('', '')";

		$sql = "";

		$sql .= "INSERT INTO " . $table;

		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";

		$sql .= "('" . implode("','", array_values($fields)) . "')";
		if ($print == 1) {
			echo $sql;
			die;
		}



		$query = mysqli_query($this->con, $sql);



		if ($query) {

			return 1;
		}
	}

	public function sendsms_planet($message, $mobile)
	{

		$xml_data = '<?xml version="1.0"?>

	<parent>

	<child>

	<user>planet</user>

	<key>e020062508XX</key>

	<mobile>' . $mobile . '</mobile>

	<message>' . $message . '</message>

	<accusage>1</accusage>

	<senderid>PLANET</senderid>

	</child>

	</parent>';



		$URL = "smsjust.com/sms/user/urllongsms.php";



		$ch = curl_init($URL);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));

		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$output = curl_exec($ch);

		curl_close($ch);



		return ($output);
	}

	public function getcode_demand($tablename, $tablepkey, $cond)
	{

		$num = $this->getvalfield($tablename, "max($tablepkey)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 10; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	public function getcode($tablename, $tablekey, $cond)
	{

		$num = $this->getvalfield($tablename, "max(reciept_no)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 5; ++$i) {

			$num = '00' . $num;
		}

		return $num;
	}



	public function getcodepay($tablename, $tablekey, $cond)
	{

		$num = $this->getvalfield($tablename, "max(reciept_no)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 5; ++$i) {

			$num = '00' . $num;
		}

		return $num;
	}



	public function getcodedirectpay($tablename, $tablekey, $cond)
	{

		$num = $this->getvalfield($tablename, "max(reciept_no)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 5; ++$i) {

			$num = '00' . $num;
		}

		return $num;
	}



	public function getcode1($tablename, $tablepkey, $cond)
	{

		$num = $this->getvalfield($tablename, "max($tablepkey)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 6; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	function net_order_amt()
	{

		$cartamt = $this->cartamt();

		$billdisc = $this->getvalfield("tax_setting", "billdisc", "is_applicable=1");

		$disc_amt = ($cartamt * $billdisc / 100);



		$minimumamt = $this->getvalfield("tax_setting", "minimumamt", "is_applicable=1");



		if ($cartamt < $minimumamt)

			$deliverycharge = $this->getvalfield("tax_setting", "deliverycharge", "is_applicable=1");
		else

			$deliverycharge = 0;



		$net_total = $cartamt - $disc_amt;

		if (isset($_SESSION['couponcode']))

			$net_total = $net_total - $_SESSION['couponcode'];



		$net_total += $deliverycharge;



		return $net_total;
	}



	public function getcodequotation($tablename, $tablepkey, $cond)
	{

		$num = $this->getvalfield($tablename, "max(quotation_no)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 10; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	public function getcode_challan($tablename, $tablepkey, $cond)
	{

		$num = $this->getvalfield($tablename, "max(billno)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 10; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}





	public function getcustomercode($tablename, $tablekey, $cond)
	{



		$num = $this->getvalfield($tablename, "max(code_no)", $cond);

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 4; ++$i) {

			$num = '00' . $num;
		}

		return $num;
	}



	public function get_overall_blls_amt($customer_id)
	{



		//opening balance

		//echo $customer_id; die;

		$prev_balance = $this->getvalfield("master_customer", "openingbal", "customer_id='$customer_id'");

		//get all sale entry bill amt

		$sql_sale = "select * from purchaseentry where customer_id = '$customer_id' and type = 'saleentry'";

		$row_sale = $this->executequery($sql_sale);

		$total_sale = 0;



		if (sizeof($row_sale) > 0) {

			$sale_amt = 0;

			foreach ($row_sale as $saleinfo) {

				# code...



				$sale_amt = $this->getTotalPerchaseBillAmt1($saleinfo['purchaseid']);

				//$sale_amt = 0;

				$total_sale += $sale_amt;
			}
		} //if close





		$voucher_payment = $this->getvalfield("voucherentry", "sum(paid_amt)", "customer_id='$customer_id' and payment_type = 'Payment'");



		$overall_amt = $total_sale + $voucher_payment + $prev_balance;

		return ($overall_amt);
	}



	public function getcode_bookingno($tablename, $tablepkey, $cond)
	{

		$num = $this->getvalfield($tablename, "max($tablepkey)", "1=1");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 5; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}





	public function get_product_total($product_id, $fromdate, $todate)
	{

		$sql = "select A.*,  (qty * rate_amt) as totalamt, (((cgst + sgst + igst)/100) * (qty * rate_amt)) as gsttax_rs  from saleentry_details as A left join saleentry as B on A.saleid = B.saleid where sale_date between '$fromdate' and '$todate' and product_id='$product_id'";



		$query = mysqli_query($this->con, $sql);

		$total = 0;

		$nettotal = 0;

		$total_tax = 0;

		while ($row = mysqli_fetch_assoc($query)) {

			$totalamt = $row['totalamt'];

			$gsttax_rs = $row['gsttax_rs'];





			//total value with tax

			$nettotal += ($totalamt + $gsttax_rs);
		}

		return $nettotal;
	}



	public function getcode_saleno($tablename, $tablepkey, $cond)
	{

		$num = 0;

		$sessionid = $this->getvalfield("m_session", "sessionid", "status=1");

		$company_id = $_SESSION['company_id'];

		$num = $this->getvalfield($tablename, "max(saleno)", "company_id=$company_id and sessionid = $sessionid");

		$num = sprintf("%'.06d\n", ++$num);

		return $num;
	}



	public function getcode_issueno($tablename, $tablepkey, $cond)
	{

		$sessionid = $this->getvalfield("m_session", "sessionid", "status=1");

		$company_id = $_SESSION['company_id'];

		$num = $this->getvalfield($tablename, "max(issueno)", "company_id=$company_id and sessionid = $sessionid");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;



		$len = strlen($num);

		for ($i = $len; $i < 6; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}

	public function getcodemeeting($tblname, $tblpkey, $cond)
	{

		//$meeting_date1 = date('Y-m-d');

		//$meeting_date = $this->getvalfield("meeting_master","meeting_date","meeting_date='$meeting_date1'");

		//$num =  $this->getvalfield($tblname,"max($tblpkey)","meeting_date='$meeting_date'");

		$num = $this->getvalfield($tblname, "max(application_no)", "1=1 and type='Meeting'");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 6; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	public function getcodeevent($tblname, $tblpkey, $cond)
	{



		$num = $this->getvalfield($tblname, "max(application_no)", "1=1 and type='Event'");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 6; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	public function getcodeconference($tblname, $tblpkey, $cond)
	{



		$num = $this->getvalfield($tblname, "max(application_no)", "1=1 and type='Conference'");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 6; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	public function getcodeplanning($tblname, $tblpkey, $cond)
	{



		$num = $this->getvalfield($tblname, "max(application_no)", "1=1 and type='Planning'");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 6; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}





	public function getcode_jarbillno($tablename, $tablepkey, $cond)
	{

		$num = 0;

		$sessionid = $this->getvalfield("m_session", "sessionid", "status=1");

		$company_id = $_SESSION['company_id'];

		$num = $this->getvalfield($tablename, "max(jar_billno)", "company_id=$company_id and sessionid = $sessionid");

		$num = sprintf("%'.06d\n", ++$num);

		return $num;
	}



	function getvalMultiple($table, $field, $where)
	{

		$getval = "";

		$sql = "select $field from $table where $where";

		//echo $sql;

		$getvalue = mysqli_query($this->con, $sql);;

		while ($row = mysqli_fetch_row($getvalue)) {

			if ($row[0] != "")

				$getval[] = $row[0];
		}

		return $getval;
	}



	public function getcode_voucher($tablename, $tablepkey, $cond)
	{

		$sessionid = $this->getvalfield("m_session", "sessionid", "status=1");

		$company_id = $_SESSION['company_id'];

		$num = $this->getvalfield($tablename, "max(voucher_no)", "sessionid = '$sessionid' and company_id = '$company_id'");

		//if($num == NULL)

		//$num = 0;

		++$num; // add 1;

		$len = strlen($num);

		for ($i = $len; $i < 5; ++$i) {

			$num = '0' . $num;
		}

		return $num;
	}



	public function insert_record_lastid($table, $fields)
	{

		//"INSERT INTO table_name ( , , ) VALUE ('', '')";

		$sql = "";

		$sql .= "INSERT INTO " . $table;

		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";



		$sql .= "('" . implode("','", array_values($fields)) . "')";

		//echo $sql;die;

		$query = mysqli_query($this->con, $sql);

		$keyvalue = mysqli_insert_id($this->con);

		if ($query) {

			return $keyvalue;

			//echo $query;die;

		}
	}



	public function insert_record_return_id($table, $fields)
	{

		//"INSERT INTO table_name ( , , ) VALUE ('', '')";

		$sql = "";

		$sql .= "INSERT INTO " . $table;

		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";

		$sql .= "('" . implode("','", array_values($fields)) . "')";

		$query = mysqli_query($this->con, $sql);

		$lastid = mysqli_insert_id($this->con);

		if ($query) {

			return $lastid;
		}
	}



	public function fetch_record($table)
	{

		$sql = "SELECT * FROM " . $table;

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}





	public function get_all_members($table)
	{

		$sql = "SELECT * FROM " . $table;

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			//$array[] = $row;

			$beforeTree[] = array('customer_id' => $row['customer_id'], 'parentid' => $row['parentid']);
		}

		return $beforeTree;
	}





	public function opening_balance($customer_id, $lastdate)
	{



		//opening balance

		$openingbal = $this->getvalfield("master_customer", "openingbal", "customer_id = '$customer_id' and open_bal_date < '$lastdate'");



		//sum total bill of customer (monthly jar)

		$sql = "select sum(nettotal) as totalamt from monthly_jar_bill_details as A left join monthly_jar_bill As B on A.mjar_billid = B.mjar_billid where customer_id = '$customer_id' and jar_billdate < '$lastdate'";

		$res = $this->executequery($sql);

		$nettotal = 0;

		foreach ($res as $row_get) {

			$nettotal += $row_get['totalamt'];
		}





		//sum total bill of customer (sale entry jar)

		$sql_sale = "select qty, rate_amt, disc, cgst, sgst, igst from saleentry_details as A left join saleentry As B on A.saleid = B.saleid where customer_id = '$customer_id' and sale_date < '$lastdate'";

		$res_sale = $this->executequery($sql_sale);

		$net_sale_total = 0;

		foreach ($res_sale as $row_sale) {

			$qty = $row_sale['qty'];

			$rate_amt = $row_sale['rate_amt'];

			$cgst = $row_sale['cgst'];

			$sgst = $row_sale['sgst'];

			$igst = $row_sale['igst'];



			$total_amt = $qty * $rate_amt;

			if ($total_amt > 0) {

				if ($cgst > 0) {

					$cgst_amt = $total_amt * $cgst / 100;
				}

				if ($sgst > 0) {

					$sgst_amt = $total_amt * $sgst / 100;
				}



				$net_amt = $total_amt + $cgst_amt + $sgst_amt;
			}



			$net_sale_total += $net_amt;
		}





		$total_payment = $this->getvalfield("voucherentry", "amount", "customer_id = '$customer_id' and vdate < '$lastdate' and paymt_id=3");



		$prev_balance = $openingbal + $nettotal + $net_sale_total - $total_payment;





		return $prev_balance;
	}





	public function fetch_record_desc_condition($table, $where, $field)
	{

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);



		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition . " ORDER BY " . $field . " DESC";

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;





		// $sql = "SELECT * FROM ".$table." ORDER BY $field DESC LIMIT 10";

		// $array = array();

		// $query = mysqli_query($this->con,$sql);

		// while ($row = mysqli_fetch_assoc($query)) {

		// $array[] = $row;

		// }

		// return $array;

	}



	public function fetch_record_desc($table, $field)
	{

		$sql = "SELECT * FROM " . $table . " ORDER BY $field DESC LIMIT 10";

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}



	function getvalfield($tblname, $column, $condition, $print = 0)
	{

		$sql = "select $column  from $tblname where $condition";

		if ($print == 1) {
			echo $sql;
			die;
		}
		$res = mysqli_query($this->con, $sql);

		$row = mysqli_fetch_assoc($res);

		//print_r($row);
		if ($row != '')

			return $row[$column];
	}



	function dateformatindia($date)
	{

		if ($date != "") {

			$ndate = explode("-", $date);

			$year = $ndate[0];

			$day = $ndate[2];

			$month = $ndate[1];



			if ($date == "0000-00-00" || $date == "")

				return "";
			else

				return $day . "-" . $month . "-" . $year;
		} else

			return "";
	}



	function dateformatusa($date)
	{

		if ($date != "") {

			$ndate = explode("-", $date);

			$year = $ndate[2];

			$day = $ndate[0];

			$month = $ndate[1];

			return $year . "-" . $month . "-" . $day;
		} else

			return "";
	}



	public function select_record($table, $where)
	{

		// id = '5' AND m_name = 'something'

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition;

		$query = mysqli_query($this->con, $sql);

		//echo $sql; die;

		$row = mysqli_fetch_assoc($query);

		return $row;
	}



	//below function count chlid of levels

	function display_children($parent, $level)
	{



		$result = mysqli_query($this->con, 'SELECT customer_id FROM customer_master ' . 'WHERE parentid="' . $parent . '"');

		$count = array(0 => 0);

		while ($row = mysqli_fetch_assoc($result)) {

			$data = str_repeat(' ', $level) . $row['customer_id'] . "\n";

			//echo $data;

			$count[0]++;

			$children = $this->display_children($row['customer_id'], $level + 1);

			$index = 1;

			foreach ($children as $child) {

				if ($child == 0)

					continue;

				if (isset($count[$index]))

					$count[$index] += $child;
				else

					$count[$index] = $child;

				$index++;
			}
		}

		return $count;

		//print_r($count);

	}



	function five_level_count($parent, $level)
	{



		$count = 0;

		$myarray = $this->display_children($parent, 0);

		//$this->pre($myarray);

		//die;

		for ($i = 0; $i < $level - 1; $i++) {



			$count += $myarray[$i];
		}

		return $count + 1;
	}





	public function InsertLog($pagename, $module, $submodule, $tablename, $tablekey, $keyvalue, $action)
	{



		$sessionid = $_SESSION['sessionid'];

		$userid = $_SESSION['userid'];

		$usertype = $_SESSION['usertype'];

		$activitydatetime = date('Y-m-d H:m:s');



		$sqlquery = "insert into activitylogreport(userid, usertype, module, submodule, pagename, primarykeyid ,tablename, activitydatetime, action,sessionid) values('$userid', '$usertype', '$module', '$submodule',  '$pagename', '$keyvalue','$tablename', '$activitydatetime', '$action','$sessionid')";

		//echo $sqlquery;die;

		mysqli_query($this->con, $sqlquery);
	}

	public function select_record2($table, $where)
	{

		// id = '5' AND m_name = 'something'

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition;

		$query = mysqli_query($this->con, $sql);

		//echo $query; die;

		while ($row = mysqli_fetch_assoc($query)) {

			$row1[] = $row;
		}

		return $row1;
	}



	public function select_data($table, $where)
	{

		// id = '5' AND m_name = 'something'

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition;

		// echo $sql;die;

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}





	public function select_crit($table, $field, $method, $date1, $date2)
	{

		$sql = "SELECT * FROM $table WHERE $field $method '$date1' AND '$date2'";

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}





	public function select_data_orderby($table, $where, $orderby)
	{

		// id = '5' AND m_name = 'something'

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}



		$condition = substr($condition, 0, -5);

		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition . " order by " . $orderby;

		//		echo $sql;

		//die;

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}



	public function select_data_condition_orderby($table, $field, $val, $orderby)
	{

		$sql = "";

		$sql .= "SELECT * FROM " . $table . " WHERE " . $field . "  != " . $val . " ORDER BY " . $orderby . " DESC ";

		//echo $sql ;die;

		$array = array();

		$query = mysqli_query($this->con, $sql);

		while ($row = mysqli_fetch_assoc($query)) {

			$array[] = $row;
		}

		return $array;
	}



	public function count_method($table, $where)
	{

		// id = '5' AND m_name = 'something'

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql .= "SELECT * FROM " . $table . " WHERE " . $condition;

		$query = mysqli_query($this->con, $sql);

		$count = mysqli_num_rows($query);

		//echo $count ;die;

		return $count;
	}





	public function count_method2($table)
	{

		$sql = "SELECT * FROM " . $table;

		$query = mysqli_query($this->con, $sql);

		$count = mysqli_num_rows($query);

		return $count;
	}







	public function check_duplicate($table, $fields, $where)
	{

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql .= "SELECT " . $fields . " FROM " . $table . " WHERE " . $condition;

		$query = mysqli_query($this->con, $sql);

		$duplicate = mysqli_num_rows($query);

		// echo $count ;die;

		return $sql;
	}



	public function check_duplicatep($table_name, $where)
	{

		$sqledit = mysqli_query($this->con, "SET NAMES utf8");

		//echo "select * from $table_name where $where";die;

		$sql = "select * from $table_name where $where";

		$res = mysqli_query($this->con, $sql);

		$cnt = mysqli_num_rows($res);

		return $cnt;
	}

	public function update_record($table, $where, $fields)
	{

		$sql = "";

		$condition = "";

		foreach ($where as $key => $value) {

			// id = 5 AND m_name = 'something'

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		foreach ($fields as $key => $value) {

			// UPDATE table SET m_name = '', qty = '' WHERE id = '';

			$sql .= $key . "='" . $value . "', ";
		}

		$sql = substr($sql, 0, -2);

		$sql = "UPDATE " . $table . " SET " . $sql . " WHERE " . $condition;

		//echo $sql;die;

		if (mysqli_query($this->con, $sql)) {

			return mysqli_insert_id($this->con);
		}
	}



	public function delete_record($table, $where)
	{

		$sql = '';

		$condition = '';

		foreach ($where as $key => $value) {

			$condition .= $key . "='" . $value . "' AND ";
		}

		$condition = substr($condition, 0, -5);

		$sql = "DELETE FROM " . $table . " WHERE " . $condition;

		//echo $sql;

		if (mysqli_query($this->con, $sql)) {

			return mysqli_insert_id($this->con);
		}
	}





	function add3dots($string, $repl, $limit)
	{

		if (strlen($string) > $limit) {

			return substr($string, 0, $limit) . $repl;
		} else {

			return $string;
		}
	}

	public function checkmenu($mudule_setting, $loginid)
	{



		$sql = mysqli_query($this->con, "SELECT B.* FROM privilage_setting AS A LEFT JOIN m_userprivilege AS B ON A.page_id = B.page_id where B.menuname='$mudule_setting' and A.userid='$loginid'");

		$numrows = mysqli_num_rows($sql);



		return $numrows;
	}



	public function check_menuname($location, $loginid)
	{



		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");

		$numrows = mysqli_num_rows($sql);



		return $numrows;
	}









	function getTotalPerchaseBillAmt($id)
	{



		$rate_amt = 0;

		$amount = 0;

		$totgst = 0;

		$totalamount = 0;

		$totsgst = 0;

		$totigst = 0;



		$sql = mysqli_query($this->con, "Select * from purchasentry_detail where purchaseid = '$id'");

		if ($sql) {





			while ($row = mysqli_fetch_array($sql)) {

				$total = 0;

				$qty = $row['qty'];

				$rate_amt = $row['rate_amt'];

				$cgst = $row['cgst'];

				$sgst = $row['sgst'];

				$igst = $row['igst'];

				$total = $qty * $rate_amt;

				$totalc = ($total * $cgst) / 100;

				$totals = ($total * $sgst) / 100;

				$totali = ($total * $igst) / 100;



				$amount += $total;

				$totgst += $totalc;

				$totigst += $totali;

				$totsgst += $totals;

				$totalgst = $totgst + $totsgst + $totigst;

				$totalamount = $amount + $totalgst;
			}
		}





		return $totalamount;
	}



	function getTotalPerchaseBillAmt1($id)
	{



		$rate_amt = 0;

		$amount = 0;

		$totgst = 0;

		$totalamount = 0;

		$totsgst = 0;

		$totigst = 0;



		$sql = mysqli_query($this->con, "Select * from purchasentry_detail where purchaseid = '$id' and sale_pur_type = 'sale'");

		if ($sql) {





			while ($row = mysqli_fetch_array($sql)) {

				$total = 0;

				//$purchaseid = $row['purchaseid'];

				$qty = $row['qty'];

				$rate_amt = $row['rate_amt'];

				$cgst = $row['cgst'];

				$sgst = $row['sgst'];

				$igst = $row['igst'];

				$cgst_amt = $row['cgst_amt'];

				$sgst_amt = $row['sgst_amt'];

				$igst_amt = $row['igst_amt'];

				//$net_amount = $obj->getvalfield("purchaseentry","net_amount","purchaseid='$purchaseid'");

				$taxable_value = $row['taxable_value'];



				$amount += $taxable_value;

				$totgst += $cgst_amt;

				$totigst += $igst_amt;

				$totsgst += $sgst_amt;

				$totalgst = $totgst + $totsgst + $totigst;

				$totalamount = $amount + $totalgst;
			}
		}





		return $totalamount;
	}







	function getTotalSaleentryBillAmt($id)
	{



		$rate_amt = 0;

		$amount = 0;

		$totgst = 0;

		$totalamount = 0;

		$totsgst = 0;

		$totigst = 0;



		$sql = mysqli_query($this->con, "Select * from saleentry_details where saleid = '$id'");

		if ($sql) {





			while ($row = mysqli_fetch_array($sql)) {

				$total = 0;

				$qty = $row['qty'];

				$rate_amt = $row['rate_amt'];

				$cgst = $row['cgst'];

				$sgst = $row['sgst'];

				$igst = $row['igst'];

				$total = $qty * $rate_amt;

				$totalc = ($total * $cgst) / 100;

				$totals = ($total * $sgst) / 100;

				$totali = ($total * $igst) / 100;



				$amount += $total;

				$totgst += $totalc;

				$totigst += $totali;

				$totsgst += $totals;

				$totalgst = $totgst + $totsgst + $totigst;

				$totalamount = $amount + $totalgst;
			}
		}





		return $totalamount;
	}



	function getTotalBookingBillAmt($id)
	{



		$rate_amt = 0;

		$amount = 0;

		$totgst = 0;

		$totalamount = 0;

		$totsgst = 0;

		$totigst = 0;



		$sql = mysqli_query($this->con, "Select * from booking_order_detail where booking_order_id = '$id'");

		if ($sql) {





			while ($row = mysqli_fetch_array($sql)) {

				$total = 0;

				$qty = $row['qty'];

				$rate_amt = $row['rate_amt'];

				$cgst = $row['cgst'];

				$sgst = $row['sgst'];

				$igst = $row['igst'];

				$total = $qty * $rate_amt;

				$totalc = ($total * $cgst) / 100;

				$totals = ($total * $sgst) / 100;

				$totali = ($total * $igst) / 100;



				$amount += $total;

				$totgst += $totalc;

				$totigst += $totali;

				$totsgst += $totals;

				$totalgst = $totgst + $totsgst + $totigst;

				$totalamount = $amount + $totalgst;
			}
		}



		return $totalamount;
	}





	function getTotalMonthlyJarBillAmt($id)
	{



		$totalamount = 0;

		$sql = mysqli_query($this->con, "Select * from monthly_jar_bill_details where mjar_billid = '$id'");

		if ($sql) {

			while ($row = mysqli_fetch_array($sql)) {

				$totalamount += $row['nettotal'];
			}
		}

		return $totalamount;
	}



	function convert_image($fname, $path, $wid, $hei)
	{

		$wid = intval($wid);

		$hei = intval($hei);

		//$fname = $sname;

		$sname = "$path$fname";

		//echo $sname;

		//header('Content-type: image/jpeg,image/gif,image/png');

		//image size

		list($width, $height) = getimagesize($sname);



		if ($hei == "") {

			if ($width < $wid) {

				$wid = $width;

				$hei = $height;
			} else {

				$percent = $wid / $width;

				$wid = $wid;

				$hei = round($height * $percent);
			}
		}



		//$wid=469;

		//$hei=290;

		$thumb = imagecreatetruecolor($wid, $hei);

		//image type

		$type = exif_imagetype($sname);

		//check image type

		switch ($type) {

			case 2:

				$source = imagecreatefromjpeg($sname);

				break;

			case 3:

				$source = imagecreatefrompng($sname);

				break;

			case 1:

				$source = imagecreatefromgif($sname);

				break;
		}

		// Resize

		imagecopyresized($thumb, $source, 0, 0, 0, 0, $wid, $hei, $width, $height);

		//echo "converted";

		//else

		//echo "not converted";

		// source filename

		$file = basename($sname);

		//destiantion file path

		//$path="uploaded/flashgallery/";

		$dname = $path . $fname;

		//display on browser

		//imagejpeg($thumb);

		//store into file path

		imagejpeg($thumb, $dname);
	}



	function get_client_ip()
	{

		$ipaddress = '';

		if (getenv('HTTP_CLIENT_IP'))

			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))

			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))

			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))

			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))

			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))

			$ipaddress = getenv('REMOTE_ADDR');
		else

			$ipaddress = 'UNKNOWN';



		return $ipaddress;
	}



	function test_input($data)
	{

		$data = trim($data);

		$data = addslashes($data);

		$data = htmlspecialchars($data);

		return $data;
	}



	function check_editBtn($location, $loginid)
	{



		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");

		$rowedit = mysqli_fetch_array($sql);

		return $rowedit['pagedit'];
	}



	function check_delBtn($location, $loginid)
	{



		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");

		$rowedit = mysqli_fetch_array($sql);

		return $rowedit['pagedel'];
	}



	function check_pageview($location, $loginid)
	{



		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");

		$rowedit = mysqli_fetch_array($sql);

		return $rowedit['pageview'];
	}



	function sendsmsGET($username, $pass, $senderid, $message, $serverUrl, $mobile)
	{

		//    echo $authKey; die;

		//username=beyondcg&pass=welcome@123&senderid=BEYOND&message=Testt&dest_mobileno=9179432534&response=Y

		$getData = 'username=' . $username . '&pass=' . $pass . '&senderid=' . $senderid . '&message=' . urlencode($message) . '&dest_mobileno=' . $mobile . '&response=Y';



		//API URL

		$url = "http://" . $serverUrl . "?" . $getData;







		// init the resource

		$ch = curl_init();

		curl_setopt_array($ch, array(

			CURLOPT_URL => $url,

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_SSL_VERIFYHOST => 0,

			CURLOPT_SSL_VERIFYPEER => 0



		));



		//get response

		$output = curl_exec($ch);



		//Print error if any



		if (curl_errno($ch)) {

			// echo 'error:' . curl_error($ch);

		}



		curl_close($ch);



		return 1;
	}



	function selectMultiple($table, $where)
	{

		$table = "SELECT * FROM `$table` WHERE product_id=1 and ratefrmplant=ratefrmplant";
	}





	function get_opening_stock($product_id, $from_date)
	{



		//opening stock form product master

		$opening_stock_master = $this->getvalfield("m_product", "opening_stock", "product_id = '$product_id' and stock_date <= '$from_date'");

		//count purchaseentry

		$sale_ret = 0;

		$sale = 0;

		$purchase_ret = 0;

		$purchase = 0;

		$purchasequery = "select qty, sale_pur_type, ret_qty from purchasentry_detail left join purchaseentry on  purchasentry_detail.purchaseid = purchaseentry.purchaseid

        where product_id = '$product_id' and purchaseentry.bill_date < '$from_date'";

		$res = $this->executequery($purchasequery);

		foreach ($res as $row_get) {

			$sale_pur_type = $row_get['sale_pur_type'];

			if ($sale_pur_type == 'purchase') {

				$purchase += (float) $row_get['qty'];

				$purchase_ret += (float) $row_get['ret_qty'];
			}



			if ($sale_pur_type == 'sale') {

				$sale += (float) $row_get['qty'];

				$sale_ret += (float) $row_get['ret_qty'];
			}
		}





		// issue and return

		$issue = 0;

		$issue_ret = 0;

		$sql_issue = "select sum(qty) as issue, sum(ret_qty) as issue_ret from issue_entry_details left join issue_entry on  issue_entry_details.issueid = issue_entry.issueid

        where product_id = '$product_id' and issue_entry.issuedate < '$from_date'";

		$res1 = $this->executequery($sql_issue);

		foreach ($res1 as $row_get1) {



			$issue = (float) $row_get1['issue'];

			$issue_ret = (float) $row_get1['issue_ret'];
		}







		$open_stock = $opening_stock_master + $purchase - $purchase_ret - $sale + $sale_ret - $issue + $issue_ret;

		return $open_stock;
	}







	function formatTreeChild($tree, $parent)
	{

		$tree2 = array();

		global $outpurarr;

		foreach ($tree as $item) {

			if ($item['parentid'] == $parent) {

				$tree2[$item['customer_id']] = $item;

				$tree2[$item['customer_id']]['child'] = $this->formatTreeChild($tree, $item['customer_id']);

				$outpurarr .= $item['customer_id'] . ",";
			}
		}



		return $tree2;
	}



	function countTotalChild($customer_id)
	{

		$sql = "select  count(*) as totalchild

		from    (select * from customer_master

         order by parentid, customer_id) customer_master,

        (select @pv := '$customer_id') initialisation

		where   find_in_set(parentid, @pv) > 0

		and     @pv := concat(@pv, ',', customer_id)";

		//die;

		$res = mysqli_query($this->con, $sql);

		$row = mysqli_fetch_row($res);

		return $row[0];
	}





	function formatTreeParent($tree, $customer_id)
	{

		$tree2 = array();

		global $outpurarr;

		foreach ($tree as $item) {

			if ($item['customer_id'] == $customer_id) {

				$tree2[$item['parentid']] = $item;

				$tree2[$item['parentid']]['parent'] = $this->formatTreeParent($tree, $item['parentid']);

				$outpurarr .= $item['parentid'] . ",";
			}
		}



		return $tree2;
	}

	function calculateOvertimeSalary($monthlySalary, $inTime, $outTime,  $officeHours = 9, $workingDays = 31, $timeUnit = 1)
	{
		// Validation checks
		if ($monthlySalary <= 0 || $officeHours <= 0 || $workingDays <= 0 || $timeUnit <= 0) {
			return 0; // Invalid inputs
		}
		// Convert inTime and outTime to timestamps (seconds)
		$inTimeHour = strtotime($inTime);
		$outTimeHour = strtotime($outTime);
		// Ensure outTime is after inTime
		if ($outTimeHour <= $inTimeHour) {
			return 0; // Invalid times
		}
		// Calculate hourly rate from monthly salary
		$monthlyWorkingHours = $officeHours * $workingDays; // Total hours in the month
		$hourlyRate = $monthlySalary / $monthlyWorkingHours;
		// Calculate total worked hours for the day
		$totalWorkedHours = ($outTimeHour - $inTimeHour) / 3600;
		// Handle under-time (if worked hours are less than office hours)
		if ($totalWorkedHours < $officeHours) {
			$underTime = $officeHours - $totalWorkedHours;
			// Calculate deduction for under-time
			$deduction = floor($underTime / $timeUnit) * $hourlyRate * $timeUnit;
			// Return the negative value for the deduction
			return -$deduction;
		}
		// Check if outTime exceeds 9 PM (21:00)
		$ninePm = strtotime('21:00'); // 9 PM in 24-hour format
		$overtimeAfter9PM = 0;
		if ($outTimeHour > $ninePm) {
			// Calculate overtime hours after 9 PM
			$overtimeAfter9PM = ($outTimeHour - $ninePm) / 3600;
			// Round down to nearest full time unit
			$overtimeAfter9PM = floor($overtimeAfter9PM / $timeUnit) * $timeUnit;
		}
		// If no overtime exists, return 0
		if ($overtimeAfter9PM < 1) {
			return 0; // No overtime pay for less than 1 hour
		}
		// Calculate overtime salary
		$overtimeSalary = $hourlyRate * $overtimeAfter9PM;
		// Return overtime amount
		return $overtimeSalary;
	}
	function countHolidays($fromDate, $toDate, $weeklyHolidays = [], $specificHolidayDates = [])
	{
		// Convert the dates to DateTime objects
		$start = new DateTime($fromDate);
		$end = new DateTime($toDate);
		$end->modify('+1 day'); // Include the end date in the range
		// Map weekday abbreviations to integers (e.g., 'Sun' => 0, 'Mon' => 1)
		$weekDayMap = [
			'Sun' => 0,
			'Mon' => 1,
			'Tue' => 2,
			'Wed' => 3,
			'Thu' => 4,
			'Fri' => 5,
			'Sat' => 6
		];
		// Normalize weekly holidays to proper format and convert to integers
		$holidayDays = array_map(function ($day) use ($weekDayMap) {
			$day = ucfirst(strtolower($day)); // Normalize input (e.g., 'sun' to 'Sun')
			return isset($weekDayMap[$day]) ? $weekDayMap[$day] : null;
		}, $weeklyHolidays);

		// Remove invalid days (if any)
		$holidayDays = array_filter($holidayDays, function ($day) {
			return $day !== null;
		});

		// Validate and normalize specific holiday dates to 'Y-m-d' format
		$specificHolidayDates = array_filter($specificHolidayDates, function ($date) {
			return is_string($date); // Ensure only strings are processed
		});

		$specificHolidayDates = array_map(function ($date) {
			return (new DateTime($date))->format('Y-m-d');
		}, $specificHolidayDates);

		$holidayCount = 0;
		$seenDates = []; // To avoid counting duplicate dates

		// Iterate through each day in the range
		while ($start < $end) {
			$currentDate = $start->format('Y-m-d');
			$currentDayOfWeek = $start->format('w'); // Numeric representation of the day (0 = Sunday)

			// Check if the date is a weekly holiday or a specific holiday
			if (in_array($currentDayOfWeek, $holidayDays) || in_array($currentDate, $specificHolidayDates)) {
				if (!in_array($currentDate, $seenDates)) {
					$holidayCount++;
					$seenDates[] = $currentDate;
				}
			}

			$start->modify('+1 day');
		}

		return $holidayCount;
	}


	function calculateSandwichLeaveWithHolidays($presentDates, $startDate, $endDate, $weeklyHolidays = ['Sun'])
	{
		// Convert start and end dates to DateTime objects
		$start = new DateTime($startDate);
		$end = new DateTime($endDate);

		// Ensure end date is greater than or equal to start date
		if ($end < $start) {
			throw new Exception("End date must be greater than or equal to the start date.");
		}

		// Sort the present dates
		sort($presentDates);

		$absentDates = []; // To store absent dates
		$sandwichDates = []; // To store sandwich leave dates

		// Iterate through each date in the range
		while ($start <= $end) {
			$currentDate = $start->format('Y-m-d');
			$dayOfWeek = $start->format('D'); // Get the abbreviated day name (e.g., Sun, Mon)

			// Check if the current date is present
			if (in_array($currentDate, $presentDates)) {
				$start->modify('+1 day');
				continue; // Skip present days
			}

			// Check if the day is sandwiched between absent days or holidays
			$prevDay = (clone $start)->modify('-1 day')->format('Y-m-d');
			$nextDay = (clone $start)->modify('+1 day')->format('Y-m-d');

			// Sandwich leave: Current day is a weekly holiday and flanked by non-present days
			if (in_array($dayOfWeek, $weeklyHolidays)) {
				if (!in_array($prevDay, $presentDates) && !in_array($nextDay, $presentDates)) {
					$sandwichDates[] = $currentDate; // Mark as sandwich leave
				}
			} else {
				// Regular absent day: Not present and not sandwiched
				$absentDates[] = $currentDate;
			}

			$start->modify('+1 day');
		}

		// Total days in the range
		$totalDays = (new DateTime($startDate))->diff(new DateTime($endDate))->days + 1;

		// Calculate total present days
		$totalPresent = count($presentDates);

		// Calculate total absent days (regular absent + sandwich leave)
		$totalAbsent = count(array_diff($absentDates, $sandwichDates)) + count($sandwichDates);

		// Return the results
		return [
			'totalDays' => $totalDays,
			'totalPresent' => $totalPresent,
			'totalAbsent' => $totalAbsent,
			'sandwichLeave' => count($sandwichDates),
			'absentDates' => $absentDates,
			'sandwichDates' => $sandwichDates,
		];
	}
}







$obj = new DataOperation;
