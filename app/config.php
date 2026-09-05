<?php
date_default_timezone_set('Asia/Kolkata');
class Database
{
	public $con;
	public function __construct()
	{
		// echo $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_NAME"] == "localhost"  || $_SERVER["SERVER_NAME"] == "trinity") {
			//echo "asdfasd";die;
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpass = "";
			$db = "nrvs_payroll";
			$this->con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
		} else {
			$dbhost = "p:127.0.0.1";
			$dbuser = "u612877078_nrvs";
			$dbpass = "4h!Ybg00";
			$db = "u612877078_nrvs";
			$this->con = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
		}
		if (!$this->con) {
			die('Could not connect:' . mysqli_connect_error());
		}
	}
}
 