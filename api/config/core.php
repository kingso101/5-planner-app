<?php
// start php session
// session_start();
include_once __DIR__ . '/../../vendor/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;
// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL); 
// home page url
$home_url="http://fiveplanner.com";
// account page url
$account_url= $home_url."/";
// admin page url
$admin_url= $home_url."/admin";
// app name
$app_name="Five Planner";  
// app contact
$app_contact="(+1) 501-550-3684"; 
$app_contact_raw="+15015503684"; 
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set number of records per page
$records_per_page = 5;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page; 
// set your default time-zone
date_default_timezone_set('Asia/Manila');
// variables used for jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;

$curr_year = date("Y");
$last_2 = substr($curr_year, -2);
// echo $last_2;

?>