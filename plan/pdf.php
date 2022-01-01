<?php 
// INCLUDE THE FPDF LIBRARY
require_once(__DIR__ .'/../assets/fpdf/fpdf.php');
// get database connection
require_once(__DIR__ .'/../../api/config/database.php');
// instantiate transaction object
require_once(__DIR__ .'/../../api/objects/transaction.php');

/**
 * 
 */
class myPDF extends FPDF
{
	
	function header()
	{
		// $this->Image('../../dist/img/logo/logo.png',10,6);
		$this->SetFont('Arial','B',14);
		$this->Cell(276,5,'LIST OF ALL TRANSACTIONS',0,0,'C');
		$this->Ln();
	}

	function footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function tableHeader()
	{	
		$this->Ln();
		$this->Ln();
		$this->Ln();
		$this->SetFont('Times','B',12);
		$this->Cell(10,10,'S/N',1,0,'C');
		$this->Cell(40,10,'Date',1,0,'C');
		$this->Cell(20,10,'Debit',1,0,'C');
		$this->Cell(20,10,'Credit',1,0,'C');
		$this->Cell(40,10,'Amount',1,0,'C');
		$this->Cell(100,10,'Transaction Note',1,0,'C');
		$this->Ln();
	}

	function viewTable()
	{	
		// INSTANTIATE DATABASE
		$database = new Database();
		$db = $database->connect();
		 
		$transaction = new Transaction($db);
		$data = $transaction->generate_file();
		// var_dump($data);
		$sn=0;
		foreach ($data as $value) {
			// var_dump( $value);
			$sn++;
			$credit = "";
			$debit = "";
			$created = strtotime($value->created);
			$date = date('M d Y', $created);
			$action = $value->action;
			$amount = "$".number_format($value->amount);
			$transaction_note = ucfirst($value->transaction_note);
			
			switch ($action) {
				case "credit":
					$credit = "Credit";
					$debit = '--';
					break;
				case "debit":
					$debit = "Debit";
					$credit = '--';
					break;
				default:
					$action = "Null.";
					break;
			}

			$this->Cell(10,10,$sn,1,0,'C');
			$this->Cell(40,10,$date,1,0,'L');
			$this->Cell(20,10,$debit,1,0,'L');
			$this->Cell(20,10,$credit,1,0,'L');
			$this->Cell(40,10,$amount,1,0,'L');
			// $this->Cell(40,10,$balance,1,0,'L');
			$this->Cell(100,10,$transaction_note,1,0,'L');
			$this->Ln();
			
		}
	}

}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L','A4',0);
$pdf->tableHeader();
$pdf->viewTable();
$pdf->Output();

?>