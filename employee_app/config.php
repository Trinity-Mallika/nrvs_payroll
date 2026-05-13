<?php
date_default_timezone_set('Asia/Kolkata');
class Database
{
	public $con;
	public function __construct()
	{

		//echo $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_NAME"] == "localhost" || $_SERVER["SERVER_NAME"] == "trinityhome" || $_SERVER["SERVER_NAME"] == "trinity" || $_SERVER["SERVER_NAME"] == "192.168.1.8") {
			//echo "asdfasd";die;
			$dbhost = "localhost";
			$dbuser = "root";
			$dbpass = "";
			$db = "sudama_payroll";
			$this->con  = new mysqli($dbhost, $dbuser, $dbpass, $db);
			//$conn = mysqli_connect("$host_name","$db_user","$db_pwd","$db_name");
			return $this->con;
		} else {
			$dbhost = "localhost";
			$dbuser = "surem2wr_nipesh";
			$dbpass = "U%;ue,&uQ9Tn";
			$db = "sudama_payroll";
			$this->con  = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed");
			return $this->con;
		}
	}
}
