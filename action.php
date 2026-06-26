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

	public function session_method_app($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE emp_code='$username' AND password='$password'";
		$query = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_array($query);
		return $row;
	}

	public function login_method_app2($table, $username, $password)
	{
		$sql = "SELECT * from $table WHERE emp_code='$username' AND password='$password'";
		// print_r($sql);
		// die;

		$query = mysqli_query($this->con, $sql);
		$count = mysqli_num_rows($query);
		if ($count > 0) {

			$row = mysqli_fetch_array($query);
			$_SESSION['emp_id'] = $row['emp_id'];
			// $_SESSION['usertype'] = $row['usertype'];
			// setcookie("rusername", $username, time() + (86400 * 30 * 30), "/"); // 30 days
			// setcookie("rpassword", $password, time() + (86400 * 30 * 30), "/"); // 30 days
			return $count;
		}
	}

	public function login_method_app($table, $mobile_no, $password)
	{

		$count = 0;

		$sql = "SELECT * from $table WHERE (emp_code='$mobile_no') AND password='$password'";

		$query = mysqli_query($this->con, $sql);

		$count = mysqli_num_rows($query);
		//print_r($count);die;

		if ($count > 0) {

			$row = mysqli_fetch_array($query);

			$_SESSION['emp_id'] = $row['emp_id'];

			//$_SESSION['emp_type'] = $row['emp_type'];

			setcookie("TA_id", $row['emp_id'], time() + 3600 * 365, "/", "", 0);

			setcookie("TA_mobile_no", $mobile_no, time() + 3600 * 365, "/", "", 0);

			setcookie("TA_password", $password, time() + 3600 * 365, "/", "", 0);

			return $count;

			// echo $sql;

			// die;

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

	function formatAmount($amount)
	{
		if ($amount === '' || $amount === null) {
			return '';
		}
		return ((float)$amount == (int)$amount)
			? (int)$amount
			: rtrim(rtrim($amount, '0'), '.');
	}


	public function session_method($table, $username, $password, $unit_id)
	{
		$sql = "SELECT * from $table WHERE username='$username' AND password='$password' AND FIND_IN_SET('$unit_id', unit_id)";
		$query = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_array($query);
		return $row;
	}



	public function session_method_management($table, $username, $password)
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

	public function bulk_update($table, array $data, array $where, $print = 0)
	{
		if (empty($data) || empty($where)) {
			return false;
		}

		$setArr = [];
		foreach ($data as $column => $value) {
			$escapedValue = mysqli_real_escape_string($this->con, $value);
			$setArr[] = "{$column} = '{$escapedValue}'";
		}

		$whereArr = [];
		foreach ($where as $column => $value) {
			$escapedValue = mysqli_real_escape_string($this->con, $value);
			$whereArr[] = "{$column} = '{$escapedValue}'";
		}

		$sql = "UPDATE {$table}
				SET " . implode(", ", $setArr) . "
				WHERE " . implode(" AND ", $whereArr);

		if ($print == 1) {
			echo $sql;
			die;
		}

		$query = mysqli_query($this->con, $sql);

		if (!$query) {
			echo mysqli_error($this->con);
			die;
		}

		return true;
	}

	public function bulk_update_with_arr($table, array $data, array $where, $print = 0)
{
    if (empty($data) || empty($where)) {
        return false;
    }

    $setArr = [];
    foreach ($data as $column => $value) {
        if ($value === null) {
            $setArr[] = "{$column}=NULL";
        } else {
            $value = mysqli_real_escape_string($this->con, $value);
            $setArr[] = "{$column}='{$value}'";
        }
    }

    $whereArr = [];

    foreach ($where as $column => $value) {

        if (is_array($value)) {

            $escaped = array_map(function ($v) {
                return "'" . mysqli_real_escape_string($this->con, $v) . "'";
            }, $value);

            $whereArr[] = "{$column} IN (" . implode(',', $escaped) . ")";
        } else {

            $value = mysqli_real_escape_string($this->con, $value);
            $whereArr[] = "{$column}='{$value}'";
        }
    }

    $sql = "UPDATE {$table}
            SET " . implode(',', $setArr) . "
            WHERE " . implode(' AND ', $whereArr);

    if ($print == 1) {
        echo $sql;
        die;
    }

    $query = mysqli_query($this->con, $sql);

    if (!$query) {
        echo mysqli_error($this->con);
        die;
    }

    return true;
}

	public function bulk_insert($table, array $rows, $print = 0)
	{
		if (empty($rows)) {
			return false;
		}

		// Columns from first row
		$columns = array_keys($rows[0]);

		$valuesArr = [];
		foreach ($rows as $row) {
			$escaped = [];
			foreach ($columns as $col) {
				$escaped[] = "'" . mysqli_real_escape_string($this->con, $row[$col] ?? '') . "'";
			}
			$valuesArr[] = "(" . implode(",", $escaped) . ")";
		}

		$sql = "INSERT INTO {$table} (" . implode(",", $columns) . ") VALUES " . implode(",", $valuesArr);

		if ($print == 1) {
			echo $sql;
			die;
		}

		$query = mysqli_query($this->con, $sql);
		$keyvalue = mysqli_insert_id($this->con);
		if (!$query) {
			echo mysqli_error($this->con);
			die;
		}

		return $keyvalue;
		//return true;
	}


	public function bulk_delete($table, array $conditions)
	{
		if (empty($conditions)) {
			return false;
		}

		$where = [];

		foreach ($conditions as $col => $val) {

			if (is_array($val)) {
				$escaped = array_map(fn($v) => "'" . mysqli_real_escape_string($this->con, $v) . "'", $val);
				$where[] = "$col IN (" . implode(",", $escaped) . ")";
			} else {
				$where[] = "$col = '" . mysqli_real_escape_string($this->con, $val) . "'";
			}
		}

		$sql = "DELETE FROM {$table} WHERE " . implode(" AND ", $where);

		$query = mysqli_query($this->con, $sql);

		if (!$query) {
			echo mysqli_error($this->con);
			die;
		}


		return true;
	}





	public function getcode($tablename, $tablepkey, $cond)
	{
		$num = $this->getvalfield($tablename, "max($tablepkey)", $cond);
		if ($num == NULL)
			$num = 0;
		++$num; // add 1;
		$len = strlen($num);
		for ($i = $len; $i < 4; ++$i) {
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

	function dateformatindia1($date)
	{
		if (empty($date) || $date == '0000-00-00' || $date == '-') {
			return '';
		}

		$timestamp = strtotime($date);
		if (!$timestamp) {
			return ''; // invalid date
		}

		return date('d-m-Y', $timestamp);
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

	function check_addBtn($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$rowedit = mysqli_fetch_array($sql);
		if ($rowedit != '')
			return $rowedit['page_add'];
	}
	function check_printBtn($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$rowedit = mysqli_fetch_array($sql);
		if ($rowedit != '')
			return $rowedit['page_print'];
	}

	function check_aprBtn($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$rowedit = mysqli_fetch_array($sql);
		if ($rowedit != '')
			return $rowedit['page_approve'];
	}

	function check_spclBtn($location, $loginid)
	{

		$sql = mysqli_query($this->con, "select * from privilage_setting as A left join m_userprivilege as B on A.page_id = B.page_id  where A.userid='$loginid' && B.pagelink='$location'");
		$rowedit = mysqli_fetch_array($sql);
		if ($rowedit != '')
			return $rowedit['page_special'];
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

	function totalWeeklyLeave($unitid, $total_working_days, $emp_id,$month,$year,$is_edit=0)
	{
    // Get latest status
	$salary_generated = $this->getvalfield(
		"salary_structure",
		"COUNT(*)",
		"emp_id='$emp_id' AND month='$month' AND year='$year'"
	);

	if ($salary_generated > 0 && $is_edit==0) {
		$salary_created_date = $this->getvalfield(
            "salary_structure",
            "createdate",
            "emp_id='$emp_id' AND month='$month' AND year='$year'
             ORDER BY salary_struc_id DESC LIMIT 1"
        );

        $allow_weekly_off = $this->getvalfield(
            "emp_allow_week_status",
            "is_allow",
            "emp_id='$emp_id'
            AND type='allow_weekoff'
            AND createdate <= '$salary_created_date'
            ORDER BY createdate DESC,
            allow_week_active_id DESC
            LIMIT 1"
        );

       	// $allow_weekly_off = $this->getvalfield(
		// 	"emp_allow_week_status",
		// 	"is_allow",
		// 	"emp_id='$emp_id' and type='allow_weekoff'
		// 	AND (
		// 	last_inactive_year < '$year'
		// 		OR  last_inactive_year = '$year' AND last_inactive_month <= '$month'
		// 	)
		// 	ORDER BY last_inactive_year DESC,
		// 		last_inactive_month DESC,
		// 		allow_week_active_id DESC
		// 	LIMIT 1"
		// );	
    }else{
		$allow_weekly_off = $this->getvalfield(
			"employee_master",
			"allow_weekly_off",
			"emp_id='$emp_id'"
    	);
	} 

    // If weekly off not allowed, return 0
    if ($allow_weekly_off != 1) {
        return 0;
    }
 
 
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

    $days = [
        $row['d1'] => $row['w1'],
        $row['d2'] => $row['w2'],
        $row['d3'] => $row['w3'],
        $row['d4'] => $row['w4'],
        $row['d5'] => $row['w5'],
    ];

    $days = array_filter($days, fn($v, $k) => $k > 0, ARRAY_FILTER_USE_BOTH);

    ksort($days);

    $leave = 0;

    foreach ($days as $day => $lv) {
        if ($total_working_days >= $day) {
            $leave = (float)$lv;
        } else {
            break;
        }
    }
	$total_leave = $allow_weekly_off == 1 ? $leave : 0;
    return $total_leave;
	}


	// function totalWeeklyLeave($unitid, $total_working_days, $allow_weekly_off)
	// {
	// 	$row = $this->select_record(
	// 		"weekly_off_setting",
	// 		[
	// 			'setting_type' => 'week_off',
	// 			'unit_id' => $unitid
	// 		]
	// 	);

	// 	if (empty($row)) {
	// 		return 0;
	// 	}

	// 	// Prepare slabs
	// 	$days = [
	// 		$row['d1'] => $row['w1'],
	// 		$row['d2'] => $row['w2'],
	// 		$row['d3'] => $row['w3'],
	// 		$row['d4'] => $row['w4'],
	// 		$row['d5'] => $row['w5'],
	// 	];

	// 	// Remove invalid entries
	// 	$days = array_filter($days, fn($v, $k) => $k > 0, ARRAY_FILTER_USE_BOTH);

	// 	// Sort slabs by day ASC
	// 	ksort($days);

	// 	$leave = 0; // default

	// 	//  last matching slab
	// 	foreach ($days as $day => $lv) {
	// 		if ($total_working_days >= $day) {
	// 			$leave = (float)$lv;
	// 		} else {
	// 			break;
	// 		}
	// 	}
	// 	$total_leave = $allow_weekly_off == 1 ? $leave : 0;
	// 	return $total_leave;
	// }



	// function getLeave($emp_id, $month, $year)
	// {
	// 	$baseDate = date('Y-m-01', strtotime("$year-$month-01"));
	// 	$baseMonth = (int)$month;
	// 	$baseYear  = (int)$year;

	// 	$opening_balance = $this->getvalfield(
	// 		"employee_master",
	// 		"opening_balance",
	// 		"emp_id='$emp_id'"
	// 	);

	// 	$opening_date = $this->getvalfield(
	// 		"employee_master",
	// 		"opening_date",
	// 		"emp_id='$emp_id'"
	// 	);

	// 	// last 3 months list
	// 	$prevMonths = [];
	// 	for ($i = 1; $i <= 3; $i++) {
	// 		$prevMonths[] = [
	// 			'month' => date('n', strtotime("-$i month", strtotime($baseDate))),
	// 			'year'  => date('Y', strtotime("-$i month", strtotime($baseDate)))
	// 		];
	// 	}

	// 	$used_coff   = $this->getvalfield(
	// 		"salary_structure",
	// 		"IFNULL(SUM(c_off_leave),0)",
	// 		"emp_id='$emp_id'
	//      AND (
	//         (month='{$prevMonths[0]['month']}' AND year='{$prevMonths[0]['year']}')
	//      OR (month='{$prevMonths[1]['month']}' AND year='{$prevMonths[1]['year']}')
	//      OR (month='{$prevMonths[2]['month']}' AND year='{$prevMonths[2]['year']}')
	//      )"
	// 	);

	// 	// ONLY remaining_leave is truth
	// 	$available_leave = $this->getvalfield(
	// 		"emp_monthly_leave",
	// 		"IFNULL(SUM(total_leave),0)",
	// 		"emp_id='$emp_id' AND leave_type='weekly'
	//      AND (
	//         (month='{$prevMonths[0]['month']}' AND year='{$prevMonths[0]['year']}')
	//      OR (month='{$prevMonths[1]['month']}' AND year='{$prevMonths[1]['year']}')
	//      OR (month='{$prevMonths[2]['month']}' AND year='{$prevMonths[2]['year']}')
	//      )"
	// 	);

	// 	// if (!empty($opening_date) && $opening_balance > 0) {
	// 	// 	$openingMonth = (int)date('n', strtotime($opening_date));
	// 	// 	$openingYear  = (int)date('Y', strtotime($opening_date));
	// 	// 	$diff = ($baseYear - $openingYear) * 12 + ($baseMonth - $openingMonth);
	// 	// 	if ($diff >= 0 && $diff <= 3) {
	// 	// 		$available_leave = $available_leave + $opening_balance;
	// 	// 	}
	// 	// }
	// 	return $available_leave + $opening_balance - $used_coff;
	// 	//return $used_coff;
	// }

	public function executenonquery($sql)
	{
		$result = mysqli_query($this->con, $sql);

		if ($result === false) {
			throw new Exception("SQL Error: " . mysqli_error($this->con));
		}

		return true;
	}


	function getLeave($emp_id, $month, $year)
	{
		$baseDate = date('Y-m-01', strtotime("$year-$month-01"));
		$prevMonths = [];
		for ($i = 0; $i < 3; $i++) {
			$prevMonths[] = [
				'month' => date('n', strtotime("-$i month", strtotime($baseDate))),
				'year'  => date('Y', strtotime("-$i month", strtotime($baseDate)))
			];
		}
		$opening_balance = $this->getvalfield(
			"employee_master",
			"used_opening_balance",
			"emp_id='$emp_id'"
		);

		$opening_date = $this->getvalfield(
			"employee_master",
			"opening_date",
			"emp_id='$emp_id'"
		);

		$opening_allowed = 0;
		if (!empty($opening_date) && $opening_balance > 0) {
			$startDate = date('Y-m-01', strtotime("-2 months", strtotime($baseDate)));
			$endDate   = date('Y-m-t', strtotime($baseDate));

			if ($opening_date >= $startDate && $opening_date <= $endDate) {
				$opening_allowed = $opening_balance;
			}
		}
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
			"emp_id='$emp_id' AND leave_type='weekly'
	     AND (
	        (month='{$prevMonths[0]['month']}' AND year='{$prevMonths[0]['year']}')
	     OR (month='{$prevMonths[1]['month']}' AND year='{$prevMonths[1]['year']}')
	     OR (month='{$prevMonths[2]['month']}' AND year='{$prevMonths[2]['year']}')
	     )"
		);
		return $available_leave + $opening_allowed;
	}

	function getExtraOffBalance($emp_id, $month, $year)
	{
		$month = (int)$month;
		$current_date =  date("Y-m-d", strtotime("$year-$month-01"));
		$current_month = (int)date("m", strtotime($current_date));
		$current_year  = (int)date("Y", strtotime($current_date));
		$prev_month = (int)date("m", strtotime("$current_date -1 month"));
		$prev_year  = (int)date("Y", strtotime("$current_date -1 month"));

		// Upload Total
		$upload_total = $this->executequery("
        SELECT
            COALESCE(SUM(total_leave),0) as total_extra_off
        FROM  emp_monthly_leave
        WHERE emp_id = '$emp_id'
        AND (
            (month = '$current_month' AND year = '$current_year')
            OR
            (month = '$prev_month' AND year = '$prev_year')
        ) and leave_type='eoff'
    ");


		$total_extra_off = $upload_total[0]['total_extra_off'];

		$used_extra_off = $this->getvalfield(
			"attendance_entry",
			"COALESCE(SUM(
            CASE
                WHEN attendance_status = 'Extra Off' THEN 1
                WHEN attendance_status = 'Half Extra Off' THEN 0.5
                ELSE 0
            END
        ),0)",
			"emp_id = '$emp_id'
        AND (
            (
                MONTH(attendance_date) = '$current_month'
                AND YEAR(attendance_date) = '$current_year'
            )
            OR
            (
                MONTH(attendance_date) = '$prev_month'
                AND YEAR(attendance_date) = '$prev_year'
            )
        )
        AND attendance_status IN ('Extra Off','Half Extra Off')"
		);

		// Balance
		$balance = $total_extra_off - $used_extra_off;

		// if ($balance < 0) {
		//     $balance = 0;
		// }

		return [
			'current_month' => $current_month,
			'prev_month'    => $prev_month,
			'uploaded'      => $total_extra_off,
			'used'          => $used_extra_off,
			'balance'       => $balance
		];
	}

	function get_opening_leave_balance($emp_id, $sessionid, $month = '', $year = '')
	{
		$month = (int)$month;
		$year = (int)$year;

		$total_opening_balance = $this->getvalfield(
			"emp_leave_allotment",
			"SUM(opening_leave)",
			"emp_id = '$emp_id'
    AND sessionid = '$sessionid'"
		);

		$monthCond = "";
		if ($month > 0 && $year > 0) {

			$monthCond = " AND (year < '$year' OR (year = '$year' AND month <= '$month')) ";
		}

		$used_opening_balance = 0;

		$res = $this->executequery("
    SELECT
        COALESCE(SUM(
            CASE
                WHEN attendance_status = 'Leave' THEN 1
                WHEN attendance_status = 'Half Leave' THEN 0.5
                ELSE 0
            END
        ),0) as total_used

    FROM attendance_entry

    WHERE emp_id = '$emp_id'
    AND attendance_status IN ('Leave','Half Leave')
    AND sessionid = '$sessionid'
	$monthCond
	");



		if (!empty($res)) {
			$used_opening_balance = $res[0]['total_used'];
		}
		// Balance
		$balance = $total_opening_balance - $used_opening_balance;

		// if ($balance < 0) {
		// 	$balance = 0;
		// }

		return $balance;
	}




	function getEarningLeave($emp_id, $sessionid, $month = '', $year = '')
	{
		//$year = (int)$year;
		$month = (int)$month;
		$year = (int)$year;
		$monthCond = "";

		// print_r($month);
		// die;

		if ($month > 0 && $year > 0) {
			$monthCond = " AND (
       		 year < '$year'
        	OR (year = '$year' AND month <= '$month')
    		) ";

		}

		$used_earning_leave = $this->getvalfield(
			"attendance_entry",
			"IFNULL(SUM(
				CASE
					WHEN attendance_status IN ('Earning Leave', 'Leave') THEN 1
            		WHEN attendance_status IN ('Half Earning Leave', 'Half Leave') THEN 0.5
					ELSE 0
				END
			),0)",
			"emp_id='$emp_id'
			AND sessionid='$sessionid' $monthCond"
		);
		$earning_leave = $this->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(total_leave),0)",
			"emp_id='$emp_id'
         AND leave_type='earning'
         AND sessionid='$sessionid' $monthCond"
		);
		$total_earning = $earning_leave - $used_earning_leave;

		return $total_earning;
	}




// 	function getEarningLeave($emp_id, $sessionid, $month = '', $year = '')
// {
//     $month = (int)$month;
//     $year  = (int)$year;

//     $monthCond = "";

//     $unit_id = $this->getvalfield(
//         "employee_master",
//         "unit_id",
//         "emp_id='$emp_id'"
//     );

//     $is_all_leave_add = $this->getvalfield(
//         "unit_master",
//         "add_leave",
//         "unit_id='$unit_id'"
//     );

//     if ($month > 0 && $year > 0) {

//         $monthCond = " AND (
//             year < '$year'
//             OR (year = '$year' AND month <= '$month')
//         ) ";
//     }

//     // Used Earn Leave
//     $used_earning_leave = $this->getvalfield(
//         "attendance_entry",
//         "IFNULL(SUM(
//             CASE
//                 WHEN attendance_status = 'Earning Leave' THEN 1
//                 WHEN attendance_status = 'Half Earning Leave' THEN 0.5
//                 ELSE 0
//             END
//         ),0)",
//         "emp_id='$emp_id'
//         AND sessionid='$sessionid'
//         $monthCond"
//     );

//     // Uploaded Earn Leave
//     $earning_leave = $this->getvalfield(
//         "emp_monthly_leave",
//         "IFNULL(SUM(total_leave),0)",
//         "emp_id='$emp_id'
//         AND leave_type='earning'
//         AND sessionid='$sessionid'
//         $monthCond"
//     );

//     /*
//     ==================================================
//     IF add_leave = 0
//     THEN CURRENT MONTH ATTENDANCE SE LEAVE CALCULATE
//     ==================================================
//     */

//     if ($is_all_leave_add == 0) {
// 		$month = !empty($month) ? (int)$month : date('n');
// 		$year  = !empty($year)  ? (int)$year  : date('Y');

//         // Employee Details
//         $empData = $this->select_record(
//             "employee_master",
//             ['emp_id' => $emp_id]
//         );

//         $allow_weekly_off = $empData['allow_weekly_off'];
//         $is_esic          = $empData['is_esic'];

//         $setting_type = ($is_esic == 1) ? 'ESIC' : 'Non ESIC';

//         // Current Month Attendance
//         $attData = $this->executequery("
//             SELECT attendance_status
//             FROM attendance_entry
//             WHERE emp_id='$emp_id'
//             AND sessionid='$sessionid'
//             AND month='$month'
//             AND year='$year'
//         ");

//         $real_total_attandence = 0;

//         foreach ($attData as $att) {

//             switch ($att['attendance_status']) {

//                 case 'Present':
//                     $real_total_attandence += 1;
//                     break;

//                 case 'Half Day':
//                     $real_total_attandence += 0.5;
//                     break;
//             }
//         }

//         // Weekly Leave
//         $week_leave = $this->totalWeeklyLeave(
//             $unit_id,
//             $real_total_attandence,
//             $allow_weekly_off
//         );

//         // Total Present
//         $earn_leave_present = $real_total_attandence + $week_leave;

//         // Monthly Earn Leave
//         $monthly_leave = $this->getTotalLeaveByWorkingDays(
//             $setting_type,
//             $earn_leave_present,
//             $unit_id
//         );

//         // Current Month Earn Leave Add
//         $earning_leave += $monthly_leave;
//     }

//     // Final Balance
//     $total_earning = $earning_leave - $used_earning_leave;

//     return $total_earning;
// 	}

	function getEmpCoffLeave($emp_id, $sessionid, $month = '', $year = '')
	{
		//$year = (int)$year;
		$month = (int)$month;
		$year = (int)$year;
		$department_id = $this->getvalfield(
			"employee_master",
			"department_id",
			"emp_id='$emp_id'"
		);

		$c_off_check = $this->getvalfield(
			"department_master",
			"c_off_check",
			"department_id='$department_id'"
		);

		// $c_off_check = $this->getvalfield(
		// 	"depart_setting_track",
		// 	"is_allow",
		// 	"department_id='$department_id'
		// 	AND type='c_off' 
		// 	AND (
		// 		last_inactive_year < '$year'
		// 		OR (
		// 			last_inactive_year='$year'
		// 			AND last_inactive_month <= '$month'
		// 		)
		// 	)
		// 	ORDER BY last_inactive_year DESC,
		// 			last_inactive_month DESC
		// 	LIMIT 1"
		// );

		if ($c_off_check == 1) {
		$monthCond = "";
			if ($month > 0 && $year > 0) {
				$monthCond = " AND (
				year < '$year'
				OR (year = '$year' AND month <= '$month')
				) ";
			}
		} else {
			// Only Current Month
        	$monthCond = " AND month='$month' AND year='$year' ";
    	}

		$used_coff_leave = $this->getvalfield(
			"attendance_entry",
			"IFNULL(SUM(
				CASE
					WHEN attendance_status = 'C Off' THEN 1
					WHEN attendance_status = 'Half C Off' THEN 0.5
					ELSE 0
				END
			),0)",
			"emp_id='$emp_id'
			AND sessionid='$sessionid' $monthCond"
		);
		$coff_leave = $this->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(total_leave),0)",
			"emp_id='$emp_id'
         AND leave_type='weekly'
         AND sessionid='$sessionid' $monthCond"
		);
		$total_coff = $coff_leave - $used_coff_leave;
		return $total_coff;
	}

	function getCurrentWeekLeave($emp_id, $month, $year)
	{
		$year = (int)$year;
		$month = (int)$month;
		$weekly_leave = $this->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(remining_leave),0)",
			"emp_id='$emp_id'
         AND leave_type='weekly'
         AND year='$year' AND month='$month'"
		);

		return $weekly_leave;
	}

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

	// function hoursToTime($hours)
	// {
	// 	$h = floor($hours);
	// 	$m = ($hours - $h) * 60;
	// 	return sprintf('%02d:%02d:00', $h, $m);
	// }
	function timeToHours($time)
	{
		list($h, $m, $s) = array_map('intval', explode(':', $time));
		return $h + ($m / 60) + ($s / 3600);
	}

	function hoursToTime($hours)
	{
		return gmdate("H:i:s", $hours * 3600);
	}

	// function calculateWorkingDays($data)
	// {
	// 	$daysInMonth = $data['daysInMonth'];
	// 	$presentDays = $data['present'];
	// 	$holidays    = $data['holiday'];
	// 	//$advance     = $data['advance'];
	// 	$weeklyBal   = $data['weekly'];
	// 	$monthlyBal  = $data['monthly'];
	// 	//$cOffBal     = $data['c_off'];
	// 	//$overtime    = $data['overtime'];
	// 	$used_extra_off    = $data['used_extra_off'];
	// 	$is_allow_c_off   = $data['allow_c_off'];
	// 	$addAllLeave = $data['add_all_leave'];
	// 	$allow_earn_leave_carry = $data['allow_earn_leave_carry'];

	// 	//$baseTotal = $presentDays + $holidays + $advance;
	// 	$baseTotal = $presentDays + $holidays;
	// 	//$baseTotal = $presentDays;

	// 	$used = [
	// 		'weekly'   => 0,
	// 		'monthly'  => 0,
	// 		'c_off'    => 0,
	// 		'overtime' => 0
	// 	];

	// 	if ($is_allow_c_off == 1) {
	// 		$shortage = max(0, $daysInMonth - $baseTotal);

	// 		$used['overtime'] = min($shortage, $used_extra_off);
	// 		$shortage -= $used['overtime'];

	// 		// $used['c_off'] = min($shortage, $cOffBal);
	// 		// $shortage -= $used['c_off'];

	// 		$used['weekly'] = min($shortage, $weeklyBal);
	// 		$shortage -= $used['weekly'];

	// 		if ($addAllLeave == 1) {
	// 			$used['monthly'] = min($shortage, $monthlyBal);
	// 			$shortage -= $used['monthly'];
	// 		}

	// 		$totalWorking =
	// 			$baseTotal +
	// 			$used['weekly'] +
	// 			$used['monthly'] +
	// 			$used['c_off'] +
	// 			$used['overtime'];
	// 	} else {

	// 		$used['overtime'] = $used_extra_off;
	// 		$used['weekly']  = $weeklyBal;

	// 		if ($addAllLeave == 1) {
	// 			$used['monthly'] = $monthlyBal;
	// 		}

	// 		$totalWorking =
	// 			$baseTotal +
	// 			$used['weekly'] +
	// 			$used['monthly'] +
	// 			$used['overtime'];
	// 	}

	// 	//$totalWorking = min($totalWorking, $daysInMonth);

	// 	return [
	// 		'total_working_days' => $totalWorking,
	// 		'used_weekly'        => $used['weekly'],
	// 		'used_monthly'       => $used['monthly'],
	// 		'used_c_off'         => $used['c_off'],
	// 		//'used_overtime'      => $used['overtime'],
	// 		//'remaining_c_off'    => max(0, $cOffBal - $used['c_off'])
	// 	];
	// }



	function calculateWorkingDays($data)
	{
		$daysInMonth = $data['daysInMonth'];
		$presentDays = $data['present'];
		$holidays    = $data['holiday'];

		$weeklyBal   = $data['weekly'];
		$monthlyBal  = $data['monthly'];

		$used_extra_off = $data['used_extra_off'];

		$is_allow_c_off = $data['allow_c_off'];
		$addAllLeave    = $data['add_all_leave'];
		$allow_earn_leave_carry = $data['allow_earn_leave_carry'];
		$joining_date = $data['joining_date']??'';
		$month = $data['month']??'';
		$year = $data['year']??'';

		$eligibleDays = $daysInMonth;
		if (!empty($joining_date)) {
			$joinMonth = date('m', strtotime($joining_date));
			$joinYear  = date('Y', strtotime($joining_date));
			if ($joinMonth == $month && $joinYear == $year) {
				$joinDay = date('d', strtotime($joining_date)); 
				$eligibleDays = $daysInMonth - $joinDay + 1;
			}
		}


		//$baseTotal = $presentDays + $holidays;

		$baseTotal = $presentDays;

		$used = [
			'weekly'   => 0,
			'monthly'  => 0,
			'c_off'    => 0,
			'overtime' => 0
		];

		$shortage = 0;

		// CASE 1 : C-OFF ALLOWED
		if ($is_allow_c_off == 1) {

			$shortage = max(0, $eligibleDays - $baseTotal);

			// OVERTIME
			$used['overtime'] = min($shortage, $used_extra_off);
			$shortage -= $used['overtime'];

			// WEEKLY
			$used['weekly'] = min($shortage, $weeklyBal);
			$shortage -= $used['weekly'];
		}

		// CASE 2 : C-OFF NOT ALLOWED
		else {

			$used['overtime'] = $used_extra_off;

			// overtime ke baad shortage nikalo
			$shortage = $eligibleDays - ($baseTotal + $used['overtime']);

			if ($shortage < 0) {
				$shortage = 0;
			}

			// sirf required weekly leave use karo
			$used['weekly'] = min($shortage, $weeklyBal);
			$shortage -= $used['weekly'];
		}

		//  COMMON MONTHLY LEAVE LOGIC
		if ($addAllLeave == 1 && $allow_earn_leave_carry == 1) {

			$used['monthly'] = min($shortage, $monthlyBal);
			$shortage -= $used['monthly'];
		}

		// TOTAL
		$totalWorking =
			$baseTotal +
			$used['weekly'] +
			$used['monthly'] +
			$used['overtime'];

		$totalWorking = min($totalWorking, $eligibleDays);

		return [
			'total_working_days' => $totalWorking,
			'used_weekly'        => $used['weekly'],
			'used_monthly'       => $used['monthly'],
			'used_c_off'         => $used['c_off'],
			'used_overtime'      => $used['overtime']
		];
	}

	function getHolidayCountWithSandwichRule($emp_id, $unitid, $month, $year)
	{
		// Fetch holidays with type
		$holidays = $this->executequery("
        SELECT `date`, holiday_type
        FROM holiday_entry
        WHERE FIND_IN_SET('$unitid', unit_id)
          AND MONTH(`date`) = '$month'
          AND YEAR(`date`) = '$year'
          AND is_deleted = 0
    ");

		$result = [
			'total'     => 0,
			'national'  => 0,
			'religious' => 0,
			'seasonal'  => 0
		];

		$presentStatuses = ['Present', 'Weekly Leave', 'Earning Leave', 'Half Day', 'Extra Off', 'Half Extra Off', 'Half Weekly Leave', 'Half Earning Leave', 'Leave', 'Half Leave', 'C Off', 'Half Leave','Half C Off'];

		foreach ($holidays as $row) {

			$holidayDate = $row['date'];
			$holidayType = strtolower(trim($row['holiday_type']));
			// expected: national / religious / seasonal

			$prevDate = date('Y-m-d', strtotime($holidayDate . ' -1 day'));
			$nextDate = date('Y-m-d', strtotime($holidayDate . ' +1 day'));

			$prevStatus = $this->getvalfield(
				"attendance_entry",
				"attendance_status",
				"emp_id='$emp_id' AND attendance_date='$prevDate'"
			);

			$nextStatus = $this->getvalfield(
				"attendance_entry",
				"attendance_status",
				"emp_id='$emp_id' AND attendance_date='$nextDate'"
			);
  			$holidayStatus = $this->getvalfield(
				"attendance_entry",
				"attendance_status",
				"emp_id='$emp_id' AND attendance_date='$holidayDate'"
        	);
			// Sandwich Rule
			//
			if (
				in_array($holidayStatus, $presentStatuses) ||
				in_array($prevStatus, $presentStatuses) ||
				in_array($nextStatus, $presentStatuses)
			) {
				$result['total']++;

				if (isset($result[$holidayType])) {
					$result[$holidayType]++;
				}
			}
		}

		return $result;
	}

	function getHolidayCountWithSandwichRule2(
    $emp_id,
    $holidayRows,
    $holidayAttendanceMap
	)
	{
		$result = [
			'total'     => 0,
			'national'  => 0,
			'religious' => 0,
			'seasonal'  => 0,
			'dates'     => []
		];

		$presentStatuses = ['Present', 'Weekly Leave', 'Earning Leave', 'Half Day', 'Extra Off', 'Half Extra Off', 'Half Weekly Leave', 'Half Earning Leave', 'Half Leave', 'C Off', 'Half Leave','Half C Off'];

		foreach ($holidayRows as $row) {

			$holidayDate = $row['date'];
			$holidayType = strtolower(trim($row['holiday_type']));

			$prevDate = date('Y-m-d', strtotime($holidayDate . ' -1 day'));
			$nextDate = date('Y-m-d', strtotime($holidayDate . ' +1 day'));

			$prevStatus =
				$holidayAttendanceMap[$emp_id][$prevDate]
				?? '';

			$nextStatus =
				$holidayAttendanceMap[$emp_id][$nextDate]
				?? '';

			$holidayStatus =
				$holidayAttendanceMap[$emp_id][$holidayDate]
				?? '';

			if (
				in_array($holidayStatus, $presentStatuses)
				||
				in_array($prevStatus, $presentStatuses)
				||
				in_array($nextStatus, $presentStatuses)
			) {

				$result['total']++;
				$result['dates'][] = $holidayDate;

				if (isset($result[$holidayType])) {
					$result[$holidayType]++;
				}
			}
		}

		return $result;
	}


function calculateLeaveUsage($daysInMonth,$presentDays,$weeklyBalance,$monthlyBalance,$is_allow_c_off,$is_all_leave_add,$allow_earn_leave_carry = 0,$overtimeDays = 0,$holidays = 0,$joining_date = '',$month = '',$year = '') {
 
	$eligibleDays = $daysInMonth;
  	if (!empty($joining_date)) {
		$joinMonth = date('m', strtotime($joining_date));
		$joinYear  = date('Y', strtotime($joining_date));
		if ($joinMonth == $month && $joinYear == $year) {
			$joinDay = date('d', strtotime($joining_date)); 
			$eligibleDays = $daysInMonth - $joinDay + 1;
		}
	}
    // Present + Holiday
    //$baseTotal = $presentDays + $holidays;
    $baseTotal = $presentDays;

    $usedWeeklyLeave = 0;
    $usedMonthlyLeave = 0;
    $usedCOff = 0;
    $usedOvertime = 0;
    $shortage = 0;

    // CASE 1 : C-OFF ALLOWED
    if ($is_allow_c_off == 1) {

        $shortage = $eligibleDays - $baseTotal;

        if ($shortage < 0) {
            $shortage = 0;
        }

        // Use overtime first
        $usedOvertime = min($shortage, $overtimeDays);
        $shortage -= $usedOvertime;

        // Weekly Leave
        $usedWeeklyLeave = min($shortage, $weeklyBalance);
        $shortage -= $usedWeeklyLeave;
    }

    // CASE 2 : C-OFF NOT ALLOWED
    else {

        // Direct overtime use
        $usedOvertime = $overtimeDays;

        // Recalculate shortage after overtime
        $shortage = $eligibleDays - ($baseTotal + $usedOvertime);

        if ($shortage < 0) {
            $shortage = 0;
        }

        // Only required weekly leave
        $usedWeeklyLeave = min($shortage, $weeklyBalance);
        $shortage -= $usedWeeklyLeave;
    }

    // COMMON MONTHLY LEAVE LOGIC
    if ($is_all_leave_add == 1 && $allow_earn_leave_carry == 1) {

        $usedMonthlyLeave = min($shortage, $monthlyBalance);
        $shortage -= $usedMonthlyLeave;
    }

    // Total working days
    $totalWorkingDays =
        $baseTotal +
        $usedWeeklyLeave +
        $usedOvertime +
        $usedMonthlyLeave;

    // Max limit
    if ($totalWorkingDays > $eligibleDays) {
        $totalWorkingDays = $eligibleDays;
    }
	$absent = $eligibleDays-$totalWorkingDays;
 	if ($absent < 0) {
        $absent = 0;
    }
    return [
        'used_weekly'       => $usedWeeklyLeave,
        'used_monthly'      => $usedMonthlyLeave,
        'used_coff'         => $usedCOff,
        'used_overtime'     => $usedOvertime,
        'total_working_days'=> $totalWorkingDays,
        'absent'=> $absent,
    ];
}


	// function calculateLeaveUsage($daysInMonth, $presentDays, $weeklyBalance, $monthlyBalance,  $is_allow_c_off, $is_all_leave_add, $overtimeDays = 0)
	// {
	// 	//  $coffBalance,
	// 	$baseTotal = $presentDays;

	// 	$usedWeeklyLeave = 0;
	// 	$usedMonthlyLeave = 0;
	// 	$usedCOff = 0;
	// 	$usedOvertime = 0;

	// 	if ($is_allow_c_off == 1) {

	// 		$shortage = $daysInMonth - $baseTotal;
	// 		if ($shortage < 0) $shortage = 0;

	// 		$usedOvertime = min($shortage, $overtimeDays);
	// 		$shortage -= $usedOvertime;

	// 		// $usedCOff = min($shortage, $coffBalance);
	// 		// $shortage -= $usedCOff;

	// 		$usedWeeklyLeave = min($shortage, $weeklyBalance);
	// 		$shortage -= $usedWeeklyLeave;

	// 		if ($is_all_leave_add == 1) {
	// 			$usedMonthlyLeave = min($shortage, $monthlyBalance);
	// 			$shortage -= $usedMonthlyLeave;
	// 		}
	// 	} else {

	// 		$usedOvertime = $overtimeDays;
	// 		$usedWeeklyLeave = $weeklyBalance;

	// 		if ($is_all_leave_add == 1) {
	// 			$usedMonthlyLeave = $monthlyBalance;
	// 		}

	// 		$usedCOff = 0;
	// 	}

	// 	$totalWorkingDays = $baseTotal + $usedWeeklyLeave + $usedMonthlyLeave + $usedCOff + $usedOvertime;

	// 	if ($totalWorkingDays > $daysInMonth) {
	// 		$totalWorkingDays = $daysInMonth;
	// 	}

	// 	return [
	// 		'used_weekly' => $usedWeeklyLeave,
	// 		'used_monthly' => $usedMonthlyLeave,
	// 		'used_coff' => $usedCOff,
	// 		'used_overtime' => $usedOvertime,
	// 		'total_working_days' => $totalWorkingDays
	// 	];
	// }

	function calculateLateIn($office_in_time, $actual_in_time, $in_margin)
	{
		$shiftStart = strtotime($office_in_time);
		$actualIn   = strtotime($actual_in_time);

		// allowed time with margin
		$allowedTime = $shiftStart + ($in_margin * 60);

		if ($actualIn > $allowedTime) {
			$lateSeconds = $actualIn - $allowedTime;
			return gmdate("H:i:s", $lateSeconds);
		}

		return "00:00:00";
	}
	function calculateEarlyOut($office_out_time, $actual_out_time, $out_margin)
	{
		$shiftEnd  = strtotime($office_out_time);
		$actualOut = strtotime($actual_out_time);

		// allowed early leave time
		$allowedOut = $shiftEnd - ($out_margin * 60);

		if ($actualOut < $allowedOut) {
			$earlySeconds = $allowedOut - $actualOut;
			return gmdate("H:i:s", $earlySeconds);
		}

		return "00:00:00";
	}

	function calculateMonthlyTDS($monthlySalary)
	{
		$annualSalary = $monthlySalary * 12;

		// fetch slabs from DB
		$slabs = $this->executequery("
        SELECT min_income, max_income, tax_rate
        FROM tds_slabs
        ORDER BY min_income ASC
    ");

		$tax = 0;

		foreach ($slabs as $slab) {

			$min = (float)$slab['min_income'];
			$max = (float)$slab['max_income'];
			$rate = (float)$slab['tax_rate'];

			if ($annualSalary > $min) {

				// if no upper limit OR salary inside slab
				if ($max == 0 || $annualSalary <= $max) {
					$tax += ($annualSalary - $min) * ($rate / 100);
					break;
				} else {
					$tax += ($max - $min) * ($rate / 100);
				}
			}
		}

		// rebate rule (important)
		if ($annualSalary <= 1200000) {
			$tax = 0;
		}

		// return monthly TDS
		return round($tax / 12);
	}

	function calculateWorkingHoursAndStatus(
		$attendance_date,
		$intime,
		$outtime,
		$shift_working_hrs,
		$shift_working_half_hrs,	
		$in_margin,
		$out_margin
	) {

		// Convert shift working hours to minutes
		list($wh, $wm, $ws) = explode(':', $shift_working_hrs);
		$officeWorkingMinutes = ($wh * 60) + $wm;

		// Convert half day working hours to minutes
		list($hh, $hm, $hs) = explode(':', $shift_working_half_hrs);
		$halfWorkingMinutes = ($hh * 60) + $hm;

		// Create DateTime
		$start = new DateTime($attendance_date . ' ' . $intime);
		$end   = new DateTime($outtime);

		// Handle cross-day
		if ($end < $start) {
			$end->modify('+1 day');
		}

		// Difference
		$interval = $start->diff($end);

		// ✅ TOTAL SECONDS (CORRECT)
		$totalSeconds =
			($interval->days * 24 * 60 * 60) +
			($interval->h * 60 * 60) +
			($interval->i * 60) +
			$interval->s;

		$hours = floor($totalSeconds / 3600);
		$minutes = floor(($totalSeconds % 3600) / 60);
		$seconds = $totalSeconds % 60;

		$working_hours = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

		// ✅ TOTAL MINUTES (FIXED)
		$workedMinutes =
			($interval->days * 24 * 60) +
			($interval->h * 60) +
			$interval->i;

		// Apply margin
		$totalMarginMinutes = $in_margin + $out_margin;
		$minimumRequiredMinutes = $officeWorkingMinutes - $totalMarginMinutes;

		// Attendance logic
		if ($workedMinutes < $halfWorkingMinutes) {
			$attendance_status = "Absent";
			$attheadid = 2;
		} elseif ($workedMinutes >= $halfWorkingMinutes && $workedMinutes < $minimumRequiredMinutes) {
			$attendance_status = "Half Day";
			$attheadid = 3;
		} else {
			$attendance_status = "Present";
			$attheadid = 1;
		}

		return [
			'working_hours' => $working_hours,   // ✅ 24:15:00
			'worked_minutes' => $workedMinutes,  // ✅ 1455 minutes
			'attendance_status' => $attendance_status,
			'attheadid' => $attheadid
		];
	}

	 
	function getWorkingDuration($start_date, $end_date)
	{
		if (empty($start_date) || empty($end_date)) return '';

		$start = new DateTime($start_date);
		$end = new DateTime($end_date);

		$diff = $start->diff($end);

		$result = '';

		if ($diff->y > 0) {
			$result .= $diff->y . ' year ';
		}
		if ($diff->m > 0) {
			$result .= $diff->m . ' month ';
		}
		if ($diff->d > 0) {
			$result .= $diff->d . ' day';
		}

		return trim($result);
	}

	function getCustomCode($time)
	{

		// agar full time string aaye (08:30:00)
		if (strpos($time, ':') !== false) {
			$hours = (int) explode(':', $time)[0];
		} else {
			$hours = (int)$time;
		}

		// 0–9 → Single Letter
		if ($hours < 10) {
			return chr(64 + $hours);
		}

		// 10+ → Double Letter
		$first = intdiv($hours, 10);
		$second = $hours % 10;

		return chr(64 + $first) . chr(64 + $second);
	}
	function applyEmployeePromotion($unit_id)
	{
		$currentMonth = date('n');
		$currentYear = date('Y');


		$getPromotion = $this->executequery("select * from emp_promotion where status=1 and effected_month='$currentMonth' and effected_year='$currentYear' and is_effected='0' and unit_id='$unit_id'");

		if (!empty($getPromotion)) {

			foreach ($getPromotion as $row) {
				// employee update
				$this->update_record(
					"employee_master",
					array(
						"emp_id" => $row['emp_id'],
						"unit_id" => $unit_id
					),
					array(
						"department_id" => $row['department_id'],
						"designation_id" => $row['designation_id'],
						"basic_salary" => $row['basic_salary']
					)
				);

				// promotion effected
				$this->update_record(
					"emp_promotion",
					array(
						"emp_promotion_id" => $row['emp_promotion_id']
					),
					array(
						"is_effected" => 1
					)
				);
			}
		}
	}

	function getClosingBalance($emp_id, $leave_type, $month = '', $year = '', $sessionid = '')
	{

		if($leave_type == 'eoff'){
			$closing = $this->getExtraOffBalance($emp_id, $month, $year);
			return $closing['balance'];
		}

		if($leave_type == 'weekly'){
			$closing = $this->getEmpCoffLeave($emp_id,$sessionid, $month, $year);
			return $closing;
		}
		$creditCond = "
			emp_id='$emp_id'
			AND leave_type='$leave_type'
		";

		if($sessionid != ''){
			$creditCond .= " AND sessionid='$sessionid'";
		}

		if($year != ''){
			$creditCond .= " AND year='$year'";
		}

		if($month != ''){
			$creditCond .= " AND month <= '$month'";
		}

		$credit = (float)$this->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(total_leave),0)",
			$creditCond
		);

		$debit = 0;

		$whereAtt = "emp_id='$emp_id'";

		if($year != ''){
			$whereAtt .= " AND YEAR(attendance_date)='$year'";
		}

		if($month != ''){
			$whereAtt .= " AND MONTH(attendance_date) <= '$month'";
		}

		$attRes = $this->executequery("
			SELECT attendance_status
			FROM attendance_entry
			WHERE $whereAtt
		");

		foreach($attRes as $row){

			switch($leave_type){

				case 'weekly':
					if($row['attendance_status']=='C Off'){
						$debit += 1;
					}
					elseif($row['attendance_status']=='Half C Off'){
						$debit += 0.5;
					}
				break;
	

				case 'earning':
					if(in_array($row['attendance_status'],['Leave','Earning Leave'])){
						$debit += 1;
					}
					elseif(in_array($row['attendance_status'],['Half Leave','Half Earning Leave'])){
						$debit += 0.5;
					}
				break;
			}
		}

		return $credit - $debit;
	}


	function getOpeningBalance($emp_id, $leave_type, $month, $year, $sessionid = '')
	{
		$month = (int)$month;
		$year  = (int)$year;

		$creditCond = "
			emp_id='$emp_id'
			AND leave_type='$leave_type'
		";

		if($sessionid != ''){
			$creditCond .= " AND sessionid='$sessionid'";
		}

		$creditCond .= "
			AND (
				year < '$year'
				OR (year = '$year' AND month < '$month')
			)
		";

		$credit = (float)$this->getvalfield(
			"emp_monthly_leave",
			"IFNULL(SUM(total_leave),0)",
			$creditCond
		);

		$debit = 0;

		$attRes = $this->executequery("
			SELECT attendance_status
			FROM attendance_entry
			WHERE emp_id='$emp_id'
			AND (
				YEAR(attendance_date) < '$year'
				OR (
					YEAR(attendance_date) = '$year'
					AND MONTH(attendance_date) < '$month'
				)
			)
		");

		if($leave_type == 'eoff'){ 
			$current_date = date("Y-m-d", strtotime("$year-$month-01"));
			$prev_month = (int)date("m", strtotime("$current_date -1 month"));
			$prev_year  = (int)date("Y", strtotime("$current_date -1 month")); 
			$opening = $this->getExtraOffBalance($emp_id, $prev_month, $prev_year); 
			return $opening['balance'];
		}

		if($leave_type == 'weekly'){ 
			$current_date = date("Y-m-d", strtotime("$year-$month-01"));
			$prev_month = (int)date("m", strtotime("$current_date -1 month"));
			$prev_year  = (int)date("Y", strtotime("$current_date -1 month")); 
			$opening = $this->getEmpCoffLeave($emp_id,$sessionid, $prev_month, $prev_year);
			return $opening;
		}

		foreach($attRes as $row){

			switch($leave_type){

				// case 'weekly':
				// 	if($row['attendance_status']=='C Off'){
				// 		$debit += 1;
				// 	}
				// 	elseif($row['attendance_status']=='Half C Off'){
				// 		$debit += 0.5;
				// 	}
				// break;

				case 'eoff':
					if($row['attendance_status']=='Extra Off'){
						$debit += 1;
					}
					elseif($row['attendance_status']=='Half Extra Off'){
						$debit += 0.5;
					}
				break;

				case 'earning':
					if(in_array($row['attendance_status'],['Leave','Earning Leave'])){
						$debit += 1;
					}
					elseif(in_array($row['attendance_status'],['Half Leave','Half Earning Leave'])){
						$debit += 0.5;
					}
				break;
			}
		}

		return $credit - $debit;
	}


	public function getEmpUsedCoff($empIdsStr, $sessionid, $month, $year)
	{
		return $this->executequery("
		SELECT 
			e.emp_id,
			SUM(
				CASE
					WHEN ae.attendance_status='C Off' THEN 1
					WHEN ae.attendance_status='Half C Off' THEN 0.5
					ELSE 0
				END
			) AS used_coff

		FROM employee_master e

		LEFT JOIN department_master d
			ON d.department_id = e.department_id

		LEFT JOIN attendance_entry ae
			ON ae.emp_id = e.emp_id
			AND ae.sessionid='$sessionid'
			AND (
				(
					d.c_off_check = 1
					AND (
						ae.year < '$year'
						OR (ae.year='$year' AND ae.month <= '$month')
					)
				)
				OR
				(
					d.c_off_check = 0
					AND ae.year='$year'
					AND ae.month='$month'
				)
			)

		WHERE e.emp_id IN ($empIdsStr)

		GROUP BY e.emp_id
	");
	}

	public function getEmpUploadedCoff($empIdsStr, $sessionid, $month, $year)
	{
		return $this->executequery("
			SELECT
				e.emp_id,
				IFNULL(SUM(eml.total_leave),0) AS total_leave

			FROM employee_master e

			LEFT JOIN department_master d
				ON d.department_id = e.department_id

			LEFT JOIN emp_monthly_leave eml
				ON eml.emp_id = e.emp_id
				AND eml.leave_type='weekly'
				AND eml.sessionid='$sessionid'
				AND (
					(
						d.c_off_check = 1
						AND (
							eml.year < '$year'
							OR (eml.year='$year' AND eml.month <= '$month')
						)
					)
					OR
					(
						d.c_off_check = 0
						AND eml.year='$year'
						AND eml.month='$month'
					)
				)

			WHERE e.emp_id IN ($empIdsStr)

			GROUP BY e.emp_id
		");
	}


}


$obj = new DataOperation;