<?php 
// INCLUDE THE FPDF LIBRARY
require_once(__DIR__ .'/../assets/fpdf/fpdf.php');
// get database connection
require_once(__DIR__ .'/../../api/config/database.php');
// instantiate transaction object
require_once(__DIR__ .'/../../api/objects/transaction.php');

// INSTANTIATE DATABASE
$database = new Database();
$db = $database->connect();
$allData = "";
$transaction = new Transaction($db);
$data = $transaction->generate_file();
// var_dump($data);

$sn=0;
foreach ($data as $value) {
	$sn++;
	$credit = "";
	$debit = "";
	$created = strtotime($value->created);
	$date = date('M d Y', $created);
	$amount = $value->amount;
	$amount = number_format($value->amount);
	// $balance = number_format($value->balance);
	$transaction_note = ucfirst($value->transaction_note);
	
	switch ($amount) {
		case "credit":
			$credit = "Credit";
			$debit = '--';
			break;
		case "debit":
			$debit = "Debit";
			$credit = '--';
			break;
		default:
			$amount = "Null.";
			break;
	}
	$allData .= $sn .','. $date .','. $transaction_note .','. $debit .','. $credit .','. $transaction_note .','. $amount ."\n";
	
}
$csv_response = "data:text/csv;charset=utf-8,S/n,Date,Debit,Credit,Transaction Note,Amount\n";
$csv_response .= $allData;
// echo '<a href="'.$csv_response.'" download="applicants.csv">Save as CSV</a>';

?>