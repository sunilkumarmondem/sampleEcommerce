<?php


$filepath = realpath(dirname(__FILE__));
 include_once ($filepath.'/../lib/Session.php');
 Session::checkLogin();
 
include_once ($filepath.'/../lib/database.php');
include_once ($filepath.'/../helper/format.php');

?>



<?php

class Adminlogin{

	private $db;
	private $fmt;

	public function __construct(){

		$this->db=new database();
		$this->fmt=new format();
	}

	public function adminlogin($adminUser,$adminPass){
		# Here we are checking format
		$adminUser=$this->fmt->validate($adminUser);
		$adminPass=$this->fmt->validate($adminPass);

		# Here we are sending data to db
		$adminUser=mysqli_real_escape_string($this->db->link,$adminUser);
		$adminPass=mysqli_real_escape_string($this->db->link,$adminPass);

		#writing query here
		if(empty($adminUser) || empty($adminPass) ){
			$loginmsg="Username or pasword cant be empty";
			return $loginmsg;
		}else{
			$query="select * from tbl_admin where adminUser='$adminUser' and adminPass='$adminPass' ";
			$result=$this->db->select($query);
			if($result !=false){
				$value=$result->fetch_assoc();
				session::set("adminlogin",true);
				session::set("adminId",$value['adminId']);
				session::set("adminName",$value['adminName']);
				session::set("adminUser",$value['adminUser']);
				header("Location:dashboard.php");

			}else{
				$loginmsg="Username or pasword did not match";
				return $loginmsg;
			}
		}
	}
}

?>