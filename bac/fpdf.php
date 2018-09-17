<?php
require_once "../vendor/autoload.php";

class PDF extends FPDF
{
	function Header()
	{
		$this->SetFont("Arial", "", 10);
		$this->Cell(0, 5, "Republic of the Philippines", 0, 1, "C");

		$this->SetFont("Arial", "B", 10);
		$this->Cell(0, 5, "BICOL UNIVERSITY", 0, 1, "C");

		$this->SetFont("Arial", "", 10);
		$this->Cell(0, 5, "College of Science", 0, 0, "C");
		$this->Ln(15);
	}
}

$pdf = new PDF("P", "mm", "A4");
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 5, "Job Order", 0, 1, "C");
// $pdf->Ln(18);

$pdf->SetFont("Arial", "", 11);
$pdf->Cell(0, 9, "Title:", 0, 0, "C");
$pdf->Ln(15);

// $pdf->SetFont("Arial", "", 11);
$pdf->Write(0, "Provision of catering services during the pre- orientation for all College/Program departments deputized to offer respective degrees through ETEEAP on August 16, 2018 @GASS Conference Room");
// $pdf->Ln(10);

// $pdf->Cell(40, 10, "Hello Word", 0, 1);
// $pdf->Cell(40, 10, "Hello Word", 0, 1);

$pdf->Output();

?>