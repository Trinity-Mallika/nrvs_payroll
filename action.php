<?php session_start();
include("config.php");

class DataOperation extends Database
{
	public function pre($val)
	{
		echo "<pre>";
		print_r($val);
		echo "</pre>";
	}


	public function login_method($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE username='$username' AND password='$password' AND status='1'";
		$query = mysqli_query($this->con, $sql);
		$count = mysqli_num_rows($query);
		if ($count > 0) {
			//$_SESSION['usertype']="trinity";
			$row = mysqli_fetch_array($query);
			if ($row['usertype'] == 'Admin' || $row['usertype'] == 'admin') {
				$_SESSION['usertype'] = $row['usertype'];
				$_SESSION['userid'] = $row['userid'];
			}

			return $count;
		}
	}
	function renderCheckboxTree($nodes, $levels, $levelIndex = 0, $unit_id = "", $is_all_unit = false)
	{
		// Normalize unit_id to array
		if (!is_array($unit_id)) {
			$unit_id = array_filter(array_map('intval', explode(',', $unit_id)));
		}

		echo '<ul>';

		foreach ($nodes as $node) {

			$hasChildren = !empty($node['children']);
			$liClass  = $hasChildren ? 'main-node' : 'form-check ms-3 child-node';
			$divClass = $hasChildren ? 'bg-body-tertiary form-check rounded-3' : 'form-check';
			$labelCls = $hasChildren ? 'form-check-label fw-bold' : 'form-check-label';

			echo '<li class="' . $liClass . '">';
			echo '<div class="' . $divClass . '">';

			// Base checkbox
			$checkboxAttr = 'class="form-check-input unit_checkbox" id="' . $node['id'] . '"';

			// Leaf node (actual UNIT)
			if (!$hasChildren && isset($levels[$levelIndex]['id'])) {

				$idField = $levels[$levelIndex]['id'];
				$rawId   = str_replace($idField . '_', '', $node['id']);

				$checkboxAttr .= ' name="unit_id[]" value="' . $rawId . '"';

				// Edit mode → checked units
				if (in_array((int)$rawId, $unit_id)) {
					$checkboxAttr .= ' checked';
				}

				// ✅ ALL selected → disable units (EDIT MODE FIX)
				if ($is_all_unit) {
					$checkboxAttr .= ' disabled';
				}
			}

			echo '<input type="checkbox" ' . $checkboxAttr . '>';
			echo '<label class="' . $labelCls . '" for="' . $node['id'] . '">' . $node['label'] . '</label>';

			if ($hasChildren) {
				echo '<span class="toggle-icon float-end">▸</span>';
			}

			echo '</div>';

			// Children
			if ($hasChildren) {
				$this->renderCheckboxTree($node['children'], $levels, $levelIndex + 1, $unit_id, $is_all_unit);
			}

			echo '</li>';
		}

		echo '</ul>';
	}

	function buildSiteTree($levels, $parentData = null, $levelIndex = 0)
	{
		if (!isset($levels[$levelIndex]))
			return [];

		$level = $levels[$levelIndex];
		$nodes = [];

		// Build query
		$query = is_callable($level['query'])
			? $level['query']($parentData)
			: $level['query'];

		$rows = $this->executequery($query);

		foreach ($rows as $row) {
			$label = $row[$level['label']];

			// Optional label lookup
			if (isset($level['label_lookup'])) {
				[$lookupTable, $lookupField, $lookupWhere] = $level['label_lookup'];
				$label = $this->getvalfield($lookupTable, $lookupField, "$lookupWhere = '{$row[$level['label']]}'");
			}

			$id = $level['id'] . '_' . $row[$level['id']];
			$children = $this->buildSiteTree($levels, $row, $levelIndex + 1);

			$nodes[] = [
				'id' => $id,
				'label' => $label,
				'children' => $children
			];
		}

		return $nodes;
	}


	public function login_method_app($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE username='$username' AND password='$password' AND status='1'";

		$query = mysqli_query($this->con, $sql);
		$count = mysqli_num_rows($query);
		if ($count > 0) {

			$row = mysqli_fetch_array($query);
			$_SESSION['userid'] = $row['userid'];
			$_SESSION['usertype'] = $row['usertype'];

			setcookie("rusername", $username, time() + (86400 * 30 * 30), "/"); // 30 days
			setcookie("rpassword", $password, time() + (86400 * 30 * 30), "/"); // 30 days
			return $count;
		}
	}


	function software_expire()
	{

		$currentdate = date('Y-m-d');
		$cntrow = $this->getvalfield("software_expired", "count(*)", "'$currentdate' between start_date and expired_date");

		return $cntrow;
	}

	function getDaysArray($month, $year)
	{
		$days_in_month = $this->daysInMonth($month, $year);
		$days_array = array();

		for ($i = 1; $i <= $days_in_month; $i++) {
			$days_array[] = array(
				'day' => $i,
				'day_name' => date('D', strtotime("$year-$month-$i")),
				'month' => date('M', strtotime("$year-$month-$i")),
				'fulldate' => date('Y-m-d', strtotime("$year-$month-$i")),
			);
		}

		return $days_array;
	}

	function daysInMonth($month, $year)
	{
		return date('t', strtotime("$year-$month-01"));
	}


	function uploadImage($imgpath, $docname)
	{



		if (1 == 1) {



			$doc_name = $docname['name'];

			$tm = "DOC";

			$tm .= microtime(true) * 1000;

			$ext = pathinfo($doc_name, PATHINFO_EXTENSION);

			$doc_name = $tm . "." . $ext;



			if (move_uploaded_file($docname['tmp_name'], $imgpath . "$doc_name")) {



				return ($doc_name);
			} else {

				return ("");
			}
		} else {

			return ("0");
		}
	}

	function send_whatsapp($mobilenumber, $message, $globalmedia = '')
	{


		$message = urlencode($message);
		// $api_key = "f7b108479d034095917c3ff3f36bef50";
		$api_key = $this->getvalfield("company_setting", "whatsapp_api_key", "1=1");

		if ($globalmedia != "")

			$url = "http://148.251.129.118/wapp/api/send?apikey=$api_key&mobile=$mobilenumber&msg=$message&img1=$globalmedia";

		else
			$url = "http://148.251.129.118/wapp/api/send?apikey=$api_key&mobile=$mobilenumber&msg=$message";

		// echo $url;
		// die;

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

		return $output;
	}



	function opening_bal($supplier_id, $from_date, $to_date = '')
	{
		$to_date = $to_date ?: '9999-12-31';

		$opening_date = $this->getvalfield("master_supplier", "open_bal_date", "supplier_id = '$supplier_id'");

		$invoice_total = (float)$this->getvalfield("billing_entry", "SUM(net_amount)", "supplier_id='$supplier_id' AND bill_date < '$from_date' and bill_type in ('invoice','challan')");
		$payment_total = (float)$this->getvalfield("payment", "SUM(pay_amt)", "supplier_id='$supplier_id' AND pay_date < '$from_date'");

		if ($from_date == $opening_date) {
			$crit = "open_bal_date = '$from_date'"; // if opening and from date are equal
		} elseif ($opening_date < $from_date) {
			$crit = "open_bal_date < '$from_date'"; // if opening is less than from date
		} elseif ($opening_date > $from_date && $opening_date <= $to_date) {
			$crit = "open_bal_date BETWEEN '$from_date' AND '$to_date'"; // if opening is greater than but less than todate
		} else {
			$crit = "open_bal_date < '$from_date'";
		}

		$opening_bal = (float)$this->getvalfield("master_supplier", "openingbal", "supplier_id='$supplier_id' AND $crit");
		$opening_balance = $invoice_total - $payment_total - $opening_bal;

		return $opening_balance;
	}

	function opening_bal_vehicle($add_vehicle_id, $from_date, $to_date = '')
	{
		$to_date = $to_date ?: '9999-12-31';

		$invoice_total = (float)$this->getvalfield("billing_entry", "SUM(net_amount)", "add_vehicle_id='$add_vehicle_id' AND bill_date < '$from_date' and bill_type in ('invoice','challan')");
		$payment_total = (float)$this->getvalfield("payment", "SUM(pay_amt)", "add_vehicle_id='$add_vehicle_id' AND pay_date < '$from_date'");

		$opening_balance = $invoice_total - $payment_total;

		return $opening_balance;
	}


	public function daybookclosing($pay_date, $bank_id)
	{
		if ($bank_id != '') {
			$crit = " and pay_mode='$bank_id'";
		} else {
			$crit = "";
		}
		$payment = $this->getvalfield("payment", "sum(pay_amt)", "pay_date < '$pay_date' and type='payment' $crit");
		$salary = $this->getvalfield("payment", "sum(pay_amt)", "pay_date < '$pay_date' and type='salary' $crit");
		$expenses = $this->getvalfield("payment", "sum(pay_amt)", "pay_date < '$pay_date' and type='expense' $crit ");

		$opn_balance = $payment - $expenses - $salary;
		return $opn_balance;
	}


	function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}


	public function session_method($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE username='$username' AND password='$password'";
		$query = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_array($query);
		return $row;
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

		$fields = array_keys($form_data);

		// build the query
		$sql = "INSERT INTO " . $table_name . "
    (`" . implode('`,`', $fields) . "`)
    VALUES('" . implode("','", $form_data) . "')";

		return mysqli_query($this->con, $sql);
	}

	public function insert_record($table, $fields, $print = 0)
	{

		$sql = "";
		$sql .= "INSERT INTO " . $table;
		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";
		$sql .= "('" . implode("','", array_values($fields)) . "')";
		if ($print == 1) {
			print_r($sql);
			die;
		}
		$query = mysqli_query($this->con, $sql);

		if ($query) {
			return 1;
		}
	}


	public function getcode($tablename, $tablepkey, $cond)
	{
		$num = $this->getvalfield($tablename, "max($tablepkey)", $cond);
		if ($num == NULL)
			$num = 0;
		++$num; // add 1;
		$len = strlen($num);
		for ($i = $len; $i < 5; ++$i) {
			$num = '0' . $num;
		}
		return $num;
	}


	public function my_simple_crypt($string, $action = 'e')
	{

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

	public function gst_calculation($qty, $rate, $disc_per = 0, $cgst, $sgst, $igst, $inc_or_exc = 'exclusive')

	{


		if ($disc_per == '') {

			$disc_per = 0;
		}

		$total_value =  $qty * $rate;

		$disc_amt = $total_value * $disc_per / 100;
		$taxable_value = $total_value - $disc_amt;

		if ($inc_or_exc == 'exclusive') {

			if ($sgst > 0) {

				$sgst_amt = round(($taxable_value * $sgst / 100), 2);
			} else {

				$sgst_amt = 0;
			}

			if ($cgst > 0) {

				$cgst_amt = round(($taxable_value * $cgst / 100), 2);
			} else {

				$cgst_amt = 0;
			}

			if ($igst > 0) {

				$igst_amt = round(($taxable_value * $igst / 100), 2);
			} else {

				$igst_amt = 0;
			}






			$final_price = $taxable_value + $sgst_amt + $cgst_amt + $igst_amt;
			$all_values = array('taxable_value' => $taxable_value, 'sgst_amt' => $sgst_amt, 'cgst_amt' => $cgst_amt, 'igst_amt' => $igst_amt, 'final_price' => $final_price);
		} else {

			$final_price = $taxable_value;
			$taxable_value = round((100 * $final_price) / (100 + $sgst + $cgst + $igst), 2);

			if ($sgst > 0) {
				$sgst_amt = round(($taxable_value * $sgst / 100), 2);
			} else {
				$sgst_amt = 0;
			}

			if ($cgst > 0) {
				$cgst_amt = round(($taxable_value * $cgst / 100), 2);
			} else {
				$cgst_amt = 0;
			}
			if ($igst > 0) {
				$igst_amt = round(($taxable_value * $igst / 100), 2);
			} else {
				$igst_amt = 0;
			}
			$all_values = array('taxable_value' => $taxable_value, 'sgst_amt' => $sgst_amt, 'cgst_amt' => $cgst_amt, 'igst_amt' => $igst_amt, 'final_price' => $final_price);
		}
		return ($all_values);
	}


	function getvalMultiple($table, $field, $where)
	{
		$getval[] = '';
		$sql = "select $field from $table where $where";
		// echo $sql;
		$getvalue = mysqli_query($this->con, $sql);
		while ($row = mysqli_fetch_row($getvalue)) {
			if ($row[0] != "")
				$getval[] = $row[0];
		}
		return $getval;
	}




	function convertRupeesToWords($amount)
	{
		$words = array(
			'',
			'One',
			'Two',
			'Three',
			'Four',
			'Five',
			'Six',
			'Seven',
			'Eight',
			'Nine',
			'Ten',
			'Eleven',
			'Twelve',
			'Thirteen',
			'Fourteen',
			'Fifteen',
			'Sixteen',
			'Seventeen',
			'Eighteen',
			'Nineteen'
		);

		$tens = array(
			'',
			'',
			'Twenty',
			'Thirty',
			'Forty',
			'Fifty',
			'Sixty',
			'Seventy',
			'Eighty',
			'Ninety'
		);

		$suffixes = array(
			'',
			'Thousand',
			'Lakh',
			'Crore'
		);

		if ($amount == 0) {
			return 'Zero Rupees';
		}

		$amount = number_format($amount, 2, '.', '');
		$amount_parts = explode('.', $amount);
		$rupees = (int) $amount_parts[0];
		$paise = isset($amount_parts[1]) ? (int) $amount_parts[1] : 0;

		$rupees_in_words = '';

		$crore = floor($rupees / 10000000);
		if ($crore > 0) {
			$rupees_in_words .= $words[$crore] . ' Crore ';
			$rupees -= $crore * 10000000;
		}

		$lakh = floor($rupees / 100000);
		if ($lakh > 0) {
			$rupees_in_words .= $words[$lakh] . ' Lakh ';
			$rupees -= $lakh * 100000;
		}

		$thousand = floor($rupees / 1000);
		if ($thousand > 0) {
			$rupees_in_words .= $words[$thousand] . ' Thousand ';
			$rupees -= $thousand * 1000;
		}

		if ($rupees > 0) {
			if ($rupees < 20) {
				$rupees_in_words .= $words[$rupees] . ' ';
			} else {
				$tens_digit = floor($rupees / 10);
				$ones_digit = $rupees % 10;
				$rupees_in_words .= $tens[$tens_digit] . ' ' . $words[$ones_digit] . ' ';
			}
		}

		if ($paise > 0) {
			$rupees_in_words .= 'and ' . $paise . '/100';
		}

		$rupees_in_words .= 'Rupees';

		return $rupees_in_words;
	}



	function getIndianCurrency(float $number)
	{
		// echo $number;
		// die;

		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(
			0 => '',
			1 => 'one',
			2 => 'two',
			3 => 'three',
			4 => 'four',
			5 => 'five',
			6 => 'six',
			7 => 'seven',
			8 => 'eight',
			9 => 'nine',
			10 => 'ten',
			11 => 'eleven',
			12 => 'twelve',
			13 => 'thirteen',
			14 => 'fourteen',
			15 => 'fifteen',
			16 => 'sixteen',
			17 => 'seventeen',
			18 => 'eighteen',
			19 => 'nineteen',
			20 => 'twenty',
			30 => 'thirty',
			40 => 'forty',
			50 => 'fifty',
			60 => 'sixty',
			70 => 'seventy',
			80 => 'eighty',
			90 => 'ninety'
		);
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_length) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
			} else
				$str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	}


	public function insert_record_lastid($table, $fields, $print = 0)
	{
		//"INSERT INTO table_name ( , , ) VALUE ('', '')";
		$sql = "";
		$sql .= "INSERT INTO " . $table;
		$sql .= " (" . implode(",", array_keys($fields)) . ") VALUE ";

		$sql .= "('" . implode("','", array_values($fields)) . "')";
		if ($print == 1) {
			print_r($sql);
			die;
		}
		// echo $sql;die;
		$query = mysqli_query($this->con, $sql);
		$keyvalue = mysqli_insert_id($this->con);
		if ($query) {
			return $keyvalue;
			//echo $query;die;
		}
	}



	function getvalfield($tablename, $column, $condition, $print = 0)
	{
		$sql = "select $column  from $tablename where $condition";
		if ($print == 1) {
			echo $sql;
			die;
		}
		//echo $sql;
		$res = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_assoc($res);
		//print_r($row);
		if ($row != "") {


			return $row[$column];
		}
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

	public function select_record($table, $where, $print = 0)
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
		if ($print == 1) {

			echo $sql;
			die;
		}
		$row = mysqli_fetch_assoc($query);
		if ($row != '')
			return $row;
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
		//echo $sql;die;
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


	public function update_record($table, $where, $fields, $print = 0)
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
		if ($print == 1) {
			print_r($sql);
			die;
		}
		// echo $sql;
		// die;
		if (mysqli_query($this->con, $sql)) {
			return mysqli_insert_id($this->con);
		}
	}


	public function get_JC_code($tablename, $jc_field, $cond, $prefix)
	{
		// Fetch the latest jc_no based on the condition
		$latestJCNo = $this->getvalfield($tablename, $jc_field, "$cond ORDER BY $jc_field DESC");

		// If a jc_no exists, extract the numeric part and increment
		if ($latestJCNo) {
			$numericPart = (int)substr($latestJCNo, strlen($prefix)); // Remove prefix and convert to integer
			$newNumber = $numericPart + 1; // Increment the numeric part
		} else {
			// If no jc_no exists, start with 1
			$newNumber = 1;
		}

		// Format the new number with leading zeros (5 digits)
		$formattedNumber = str_pad($newNumber, 5, '0', STR_PAD_LEFT);

		// Combine the prefix and formatted number
		$newJCCode = $prefix . $formattedNumber;

		return $newJCCode;
	}

	public function delete_record($table, $where, $print = 0)
	{
		$sql = '';
		$condition = '';
		foreach ($where as $key => $value) {
			$condition .= $key . "='" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$sql = "DELETE FROM " . $table . " WHERE " . $condition;
		if ($print == 1) {
			echo $sql;
			die;
		}

		if (mysqli_query($this->con, $sql)) {
			return mysqli_insert_id($this->con);
		}
	}


	public function checkmenu($mudule_setting, $loginid)
	{

		$sql = mysqli_query($this->con, "SELECT B.* FROM privilage_setting AS A LEFT JOIN m_userprivilege AS B ON A.page_id = B.page_id where B.menuname='$mudule_setting' and A.userid='$loginid'");
		// echo $sql;die;

		$numrows = mysqli_num_rows($sql);
		return $numrows;
	}

	public function check_menuname($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$numrows = mysqli_num_rows($sql);

		return $numrows;
	}

	public function check_menudash($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.page_heading='$location'");
		$numrows = mysqli_num_rows($sql);

		return $numrows;
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
		if ($rowedit != '')
			return $rowedit['pagedit'];
	}

	function check_delBtn($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$rowedit = mysqli_fetch_array($sql);
		if ($rowedit != '')

			return $rowedit['pagedel'];
	}

	function check_pageview($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$rowedit = mysqli_fetch_array($sql);
		if ($rowedit != '')
			return $rowedit['pageview'];
	}
	function opening_ledger($supplier_id, $from_date)
	{
		$opening_balance = $this->getvalfield("master_supplier", "sum(openingbal)", "supplier_id ='$supplier_id' and open_bal_date < '$from_date'");

		$bill_amt = $this->getvalfield("billing_entry", "sum(net_amount)", "supplier_id ='$supplier_id' and bill_date < '$from_date'");

		$payment_amt = $this->getvalfield("payment", "sum(pay_amt)", "supplier_id ='$supplier_id' and pay_date < '$from_date'");

		return $opening_balance + $bill_amt - $payment_amt;
	}

	function calculateOvertimeTime($workingHours, $officeWorkingHours)
	{

		list($wh, $wm, $ws) = array_map('intval', explode(':', $workingHours));
		$workingSeconds = ($wh * 3600) + ($wm * 60) + $ws;

		list($oh, $om, $os) = array_map('intval', explode(':', $officeWorkingHours));
		$officeSeconds = ($oh * 3600) + ($om * 60) + $os;

		if ($workingSeconds <= 0 || $officeSeconds <= 0) {
			return '00:00:00';
		}

		// Calculate overtime
		$overtimeSeconds = $workingSeconds - $officeSeconds;

		if ($overtimeSeconds <= 0) {
			return '00:00:00';
		}

		// Convert seconds to HH:MM:SS
		return gmdate('H:i:s', $overtimeSeconds);
	}




	function getTotalLeaveByWorkingDays($setting_type, $total_working_days, $unitid)
	{
		$row = $this->select_record("c_off_setting", array('week_off_Setting_r' => $setting_type, 'unit_id' => $unitid));
		if (empty($row)) {
			return 0;
		}
		$days = [
			(float)$row['d1'] => (float)$row['w1'],
			(float)$row['d2'] => (float)$row['w2'],
			(float)$row['d3'] => (float)$row['w3'],
			(float)$row['d4'] => (float)$row['w4'],
			(float)$row['d5'] => (float)$row['w5'],
		];
		// Remove zero/invalid days
		$days = array_filter($days, fn($v, $k) => $k > 0, ARRAY_FILTER_USE_BOTH);

		// Sort slabs ascending
		ksort($days, SORT_NUMERIC);

		$leave = 0;
		foreach ($days as $day => $lv) {
			if ((float)$total_working_days >= (float)$day) {
				$leave = (float)$lv;
			} else {
				break;
			}
		}

		return $leave;
	}


	function totalWeeklyLeave($unitid, $total_working_days)
	{

		$row = $this->select_record(
			"weekly_off_setting",
			[
				'setting_type' => 'week_off',
				'unit_id' => $unitid
			]
		);

		if (empty($row)) {
			return 0;
		}

		// Prepare slabs
		$days = [
			$row['d1'] => $row['w1'],
			$row['d2'] => $row['w2'],
			$row['d3'] => $row['w3'],
			$row['d4'] => $row['w4'],
			$row['d5'] => $row['w5'],
		];

		// Remove invalid entries
		$days = array_filter($days, fn($v, $k) => $k > 0, ARRAY_FILTER_USE_BOTH);

		// Sort slabs by day ASC
		ksort($days);

		$leave = 0; // default

		//  last matching slab
		foreach ($days as $day => $lv) {
			if ($total_working_days >= $day) {
				$leave = (float)$lv;
			} else {
				break;
			}
		}

		return $leave;
	}
	function getLeave($emp_id, $month, $year)
	{
		$baseDate = date('Y-m-01', strtotime("$year-$month-01"));
		$baseMonth = (int)$month;
		$baseYear  = (int)$year;

		$opening_balance = $this->getvalfield(
			"employee_master",
			"opening_balance",
			"emp_id='$emp_id'"
		);

		$opening_date = $this->getvalfield(
			"employee_master",
			"opening_date",
			"emp_id='$emp_id'"
		);

		// last 3 months list
		$prevMonths = [];
		for ($i = 1; $i <= 3; $i++) {
			$prevMonths[] = [
				'month' => date('n', strtotime("-$i month", strtotime($baseDate))),
				'year'  => date('Y', strtotime("-$i month", strtotime($baseDate)))
			];
		}

		// ONLY remaining_leave is truth
		$available_leave = $this->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(remining_leave),0)",
			"emp_id='$emp_id'
	     AND (
	        (month='{$prevMonths[0]['month']}' AND year='{$prevMonths[0]['year']}')
	     OR (month='{$prevMonths[1]['month']}' AND year='{$prevMonths[1]['year']}')
	     OR (month='{$prevMonths[2]['month']}' AND year='{$prevMonths[2]['year']}')
	     )"
		);

		if (!empty($opening_date) && $opening_balance > 0) {
			$openingMonth = (int)date('n', strtotime($opening_date));
			$openingYear  = (int)date('Y', strtotime($opening_date));
			$diff = ($baseYear - $openingYear) * 12 + ($baseMonth - $openingMonth);
			if ($diff >= 0 && $diff <= 3) {
				$available_leave += $opening_balance;
			}
		}

		return $available_leave;
	}

	// function generateSalaryStructure(
	// 	float $total_salary,
	// 	int   $total_days_in_month,
	// 	int   $working_days,
	// 	float $loan_amount = 0,
	// 	float $tds = 0,
	// 	float $lpg_ded = 0,
	// 	float $shoes_ded = 0,
	// 	float $other_ded = 0
	// ) {

	// 	/* ---------------- BASIC & INCREMENT ---------------- */
	// 	if ($total_salary > 100000) {
	// 		$basic_salary = 100000;
	// 		$increment    = $total_salary - 100000;
	// 	} else {
	// 		$basic_salary = $total_salary;
	// 		$increment    = 0;
	// 	}

	// 	/* ---------------- PER DAY BASIC SALARY ---------------- */
	// 	$per_day_salary = $basic_salary / $total_days_in_month;

	// 	/* ---------------- PER DAY INCREMENT ---------------- */
	// 	$per_day_increment = $increment / $total_days_in_month;
	// 	$increment_balance = $per_day_increment * $working_days;

	// 	/* ---------------- OVERTIME ---------------- */
	// 	$extra_days = max(0, $working_days - $total_days_in_month);
	// 	$extra_salary_balance = $extra_days * $per_day_salary;

	// 	/* ---------------- LOAN TO DAYS ---------------- */
	// 	$loan_days = 0;
	// 	if ($loan_amount > 0) {
	// 		$loan_days = ceil($loan_amount / $per_day_salary);
	// 	}

	// 	/* ---------------- DEDUCTION DAYS ---------------- */
	// 	$deduction_days = max(0, $working_days - $loan_days);

	// 	/* ---------------- NET SALARY (BASIC) ---------------- */
	// 	$net_salary = round($deduction_days * $per_day_salary);

	// 	/* ---------------- LOAN ADJUSTMENT ---------------- */
	// 	$loan_adjusted_amount = $loan_days * $per_day_salary;
	// 	$loan_balance = max(0, $loan_adjusted_amount - $loan_amount);

	// 	/* ---------------- TOTAL DEDUCTION ---------------- */
	// 	$total_deduction = $loan_amount + $tds + $lpg_ded + $shoes_ded + $other_ded;

	// 	/* ---------------- FINAL BALANCE ---------------- */
	// 	$balance = round(
	// 		$increment_balance +
	// 			$extra_salary_balance +
	// 			$loan_balance,
	// 		2
	// 	);

	// 	return [
	// 		'basic_salary'        => round($basic_salary),
	// 		'increment'           => round($increment),

	// 		'per_day_salary'      => round($per_day_salary, 2),
	// 		'per_day_increment'   => round($per_day_increment, 2),

	// 		'total_days_month'    => $total_days_in_month,
	// 		'working_days'        => $working_days,
	// 		'extra_days'          => $extra_days,

	// 		'loan_days'           => $loan_days,
	// 		'deduction_days'      => $deduction_days,

	// 		'net_salary'          => $net_salary,
	// 		'bank_salary'         => $net_salary,

	// 		'increment_balance'   => round($increment_balance, 2),
	// 		'loan_balance'        => round($loan_balance, 2),
	// 		'lpg_ded'             => $lpg_ded,
	// 		'shoes_ded'             => $shoes_ded,
	// 		'other_ded'             => $other_ded,

	// 		'total_deduction'     => round($total_deduction),
	// 		'balance'             => $balance,
	// 		'adu'                 => $balance - ($lpg_ded + $shoes_ded + $other_ded)
	// 	];
	// }

	// function generateSalaryStructure(
	// 	float $total_salary,
	// 	int   $total_days_in_month,
	// 	int   $working_days,
	// 	float $loan_amount = 0,
	// 	float $pf = 0,
	// 	float $esi = 0,
	// 	float $tds = 0,
	// 	float $lpg_ded = 0,
	// 	float $shoes_ded = 0,
	// 	float $other_ded = 0
	// ) {

	// 	/* ---------------- BASIC & INCREMENT ---------------- */
	// 	if ($total_salary > 100000) {
	// 		$basic_salary = 100000;
	// 		$increment    = $total_salary - 100000;
	// 	} else {
	// 		$basic_salary = $total_salary;
	// 		$increment    = 0;
	// 	}

	// 	/* ---------------- PER DAY BASIC ---------------- */
	// 	$per_day_salary = $basic_salary / $total_days_in_month;

	// 	/* ---------------- EARNED BASIC ---------------- */
	// 	$earned_basic = $per_day_salary * $working_days;

	// 	/* ---------------- LOAN DEDUCTION (CAPPED) ---------------- */
	// 	$actual_loan_deduction = min($loan_amount, $earned_basic);
	// 	$remaining_loan = max(0, $loan_amount - $actual_loan_deduction);

	// 	/* ---------------- LOAN DAYS ---------------- */
	// 	$loan_days = 0;
	// 	if ($loan_amount > 0) {
	// 		$loan_days = ceil($loan_amount / $per_day_salary);
	// 	}

	// 	// $loan_days = ($actual_loan_deduction > 0)
	// 	// 	? floor($actual_loan_deduction / $per_day_salary)
	// 	// 	: 0;

	// 	/* ---------------- NET BASIC AFTER LOAN ---------------- */
	// 	$net_basic = round($earned_basic - $actual_loan_deduction);

	// 	/* ---------------- INCREMENT (PROPORTIONAL) ---------------- */
	// 	$per_day_increment = $increment / $total_days_in_month;
	// 	$increment_balance = $per_day_increment * $working_days;

	// 	/* ---------------- TOTAL DEDUCTION ---------------- */
	// 	$total_deduction = $actual_loan_deduction
	// 		+ $pf + $esi + $tds
	// 		+ $lpg_ded + $shoes_ded + $other_ded;

	// 	/* ---------------- BANK SALARY ---------------- */
	// 	$bank_salary = $net_basic
	// 		- ($pf + $esi + $tds + $lpg_ded + $shoes_ded + $other_ded);

	// 	if ($bank_salary < 0) {
	// 		$bank_salary = 0;
	// 	}

	// 	return [
	// 		'basic_salary'        => round($basic_salary),
	// 		'increment'           => round($increment),

	// 		'per_day_salary'      => round($per_day_salary, 2),
	// 		'earned_basic'        => round($earned_basic, 2),

	// 		'working_days'        => $working_days,

	// 		'loan_amount'         => round($loan_amount),
	// 		'loan_deducted'       => round($actual_loan_deduction, 2),
	// 		'remaining_loan'      => round($remaining_loan, 2),
	// 		'loan_days'           => $loan_days,

	// 		'pf'                  => round($pf, 2),
	// 		'esi'                 => round($esi, 2),
	// 		'tds'                 => round($tds, 2),

	// 		'lpg_ded'             => round($lpg_ded, 2),
	// 		'shoes_ded'           => round($shoes_ded, 2),
	// 		'other_ded'           => round($other_ded, 2),

	// 		'net_basic_salary'    => $net_basic,
	// 		'bank_salary'         => round($bank_salary),

	// 		'increment_balance'   => round($increment_balance, 2),
	// 		'total_deduction'     => round($total_deduction),

	// 		// ADU = remaining loan carry forward
	// 		'adu'                 => round($remaining_loan)
	// 	];
	// }

	function generateSalaryStructure(
		float $total_salary = 0,
		float $total_days_in_month = 31,
		float $working_days = 0,
		float $loan_amount = 0,
		float $tds = 0,
		float $lpg_ded = 0,
		float $shoes_ded = 0,
		float $other_ded = 0,
		float $pf_amt = 0,
		float $esic_amt = 0,
	) {

		/* ---------------- BASIC & INCREMENT ---------------- */
		if ($total_salary > 100000) {
			$basic_salary = 100000;
			$increment    = $total_salary - 100000;
		} else {
			$basic_salary = $total_salary;
			$increment    = 0;
		}

		/* ---------------- PER DAY BASIC SALARY ---------------- */
		$per_day_salary = $basic_salary / $total_days_in_month;

		/* ---------------- PER DAY INCREMENT ---------------- */
		$per_day_increment = $increment / $total_days_in_month;
		$increment_balance = $per_day_increment * $working_days;

		/* ---------------- OVERTIME ---------------- */
		$extra_days = max(0, $working_days - $total_days_in_month);
		$extra_salary_balance = $extra_days * $per_day_salary;

		$total_working_day = $working_days - $extra_days;

		/* ---------------- LOAN TO DAYS ---------------- */
		$loan_days = 0;
		if ($loan_amount > 0) {
			$loan_days = ceil($loan_amount / $per_day_salary);
		}

		/* ---------------- DEDUCTION DAYS ---------------- */
		$deduction_days = max(0, $total_working_day - $loan_days);

		/* ---------------- NET SALARY (BASIC) ---------------- */
		//$net_salary = round($deduction_days * $per_day_salary);
		$net_salary = round($deduction_days * $per_day_salary);

		/* ---------------- LOAN ADJUSTMENT ---------------- */
		$loan_adjusted_amount = $loan_days * $per_day_salary;
		$loan_balance = max(0, $loan_adjusted_amount - $loan_amount);

		/* ---------------- TOTAL DEDUCTION ---------------- */
		//$total_deduction = $loan_adjusted_amount + $tds + $lpg_ded + $shoes_ded + $other_ded;
		$total_deduction = $tds + $lpg_ded + $shoes_ded + $other_ded;

		/* ---------------- FINAL BALANCE ---------------- */
		$balance = round(
			$increment_balance +
				$extra_salary_balance +
				$loan_balance,
			2
		);

		return [
			'basic_salary'        => round($basic_salary),
			'increment'           => round($increment),

			'per_day_salary'      => round($per_day_salary, 2),
			'per_day_increment'   => round($per_day_increment, 2),

			'total_days_month'    => $total_days_in_month,
			'working_days'        => $working_days,
			'extra_days'          => $extra_days,
			'total_working_day'   => $total_working_day,

			'loan_days'           => $loan_days,
			'deduction_days'      => $deduction_days,
			'loan_adjusted_amount'      => round($loan_adjusted_amount),
			'loan_balance'        => round($loan_balance, 2),
			'pf_amt'          => $pf_amt,
			'esic_amt'          => $esic_amt,
			'net_salary'          => $net_salary,
			'bank_salary'         => round($net_salary - ($pf_amt + $esic_amt)),

			'increment_balance'   => round($increment_balance, 2),


			'total_deduction'     => round($total_deduction),
			'balance'             => $balance,
			'adu'                =>  round($balance - $total_deduction),
		];
	}
	function nextMonthYear($month, $year)
	{
		$month++;
		if ($month > 12) {
			$month = 1;
			$year++;
		}
		return [$month, $year];
	}
}


$obj = new DataOperation;
