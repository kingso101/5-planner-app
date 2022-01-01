<?php
// core configuration
include_once "../config/core.php";
 
// destroy session, it will remove ALL session settings
session_start();
unset($_SESSION["client_id"]);
unset($_SESSION["_id"]);
unset($_SESSION["firstname"]);
unset($_SESSION["lastname"]);
unset($_SESSION["email"]);
// unset($_SESSION["address"]);
// unset($_SESSION["gender"]);
// unset($_SESSION["balance"]);
// unset($_SESSION["dob"]);
// unset($_SESSION["balance"]);
// unset($_SESSION["account_num"]);
// unset($_SESSION["acc_type"]);
// unset($_SESSION["sort_code"]);
// unset($_SESSION["routing_code"]);
// unset($_SESSION["contact_number"]);
// unset($_SESSION["profile_img"]);

session_destroy();
if (isset($_SERVER['HTTP_REFERER'])) {
	$url = $_SERVER['HTTP_REFERER'];
	header("Location: {$account_url}auth/login");

}

?>