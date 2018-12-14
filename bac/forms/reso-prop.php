<?php
require_once "../../core/init.php";
$admin = new Admin();
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$id = base64_decode($_GET['rq']);

$req_details = $admin->projectDetails($id);

echo "<pre>".print_r($req_details)."</pre>";
die();


// $documentProtection = $phpWord->getSettings()->getDocumentProtection();
// $documentProtection->setEditing(\PhpOffice\PhpWord\SimpleType\DocProtect::READ_ONLY);
// $documentProtection->setPassword('PrMO');


$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(11);

$section = $phpWord->addSection([
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 1440,
	'marginRight' => 1152,
	'headerHeight' => 360,
	'footerHeight' => 0,
	'pageSizeW' => 12240,
	'pageSizeH' => 18720
]);
$header = $section->addHeader();
$table = $header->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2]);
$table->addRow(200);
$cellImage = $table->addCell(1300, ['vMerge' => 'restart']);
$cellImage->addImage('../../assets/pics/Office logo.jpg', ['width' => 57.6,'height' => 55.44,'wrappingStyle' => 'square','positioning' => 'absolute','posHorizontal'    => 'inside','posHorizontalRel' => 'margin','posVerticalRel' => 'line']);
$table->addCell(8000);
$cellBox = $table->addCell(1300, ['vMerge' => 'restart']);
$textbox = $cellBox->addTextBox(['width' => 120,'height' => 50,'borderColor' => '#FFFFFF']);
$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("Republic of the Philippines", ['size' => 10, 'name' => 'Arial'], ['alignment' => 'left']);
$table->addCell(null, ['vMerge' => 'continue']);
$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("BICOL UNIVERSITY", ['size' => 11, 'name' => 'Arial'], ['alignment' => 'left']);
$table->addCell(null, ['vMerge' => 'continue']);
$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("Legazpi City", ['size' => 10, 'name' => 'Arial'], ['alignment' => 'left']);
$table->addCell(null, ['vMerge' => 'continue']);
$section->addTextBreak(1);


$Project = "Provide Catering Services, Printing Services of Streamers and Printing Service of Polo Shirts to be used during the College Foundation Day Celebration 2018 of BUCN";

$section->addText("REQUEST FOR PROPOSAL", ['bold' => true], ['alignment' => 'center', 'lineHeight' => 1, 'space' => ['before' => 0, 'after' => 72]]);
$section->addText(htmlspecialchars($Project, ENT_QUOTES), ['bold' => true], ['alignment' => 'center']);
$section->addTextBreak(1);

$ABC = 57440.00;
$ex = explode('.', $ABC);
$countABC = count($ex);
if($countABC === 1)
{
	$ABC = sprintf("%s0", $ABC);
	$whole = $ex[0];
}
elseif($countABC === 2)
{
	list($whole, $decimal) = $ex;
	if($decimal <= 10)
	{
		$decimal = sprintf("%d0", $decimal);
		$ABC = sprintf("%s0", $ABC);
	}
}

$format = new NumberFormatter("en", NumberFormatter::SPELLOUT);
$whole = htmlspecialchars($format->format($whole));

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("The Bicol University, through the Corporate Budget for the contract approved by the Board of Regents intends to apply the sum of ");

if($countABC === 1) $textrun->addText(ucwords($whole)." Pesos Only (Php".$ABC.")", ['bold' => true, 'italic' => true]);
elseif($countABC === 2) $textrun->addText(ucwords($whole)." and ".$decimal."/100 Pesos (Php ".$ABC.")", ['bold' => true, 'italic' => true]);

$textrun->addText(" being the Approved Budget for the Contract to payment for the contract: ");
$textrun->addText($Project, ['bold' => true, 'italic' => true]);

$section->addTextBreak(1);

$c = ['alignment' => 'center'];
$tbStyle = ['italic' => true, 'bold' => true, 'size' => 10];
$tbc = ['valign' => 'center'];

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 144]);
$table->addRow(43.2);
$table->addCell(921.6, $tbc)->addText("Item", $tbStyle, $c);
$table->addCell(4320, $tbc)->addText("Title", $tbStyle, $c);
$table->addCell(2160, $tbc)->addText("ABC", $tbStyle, $c);

$table->addRow(43.2);
$table->addCell(null, $tbc)->addText("A", $tbStyle, $c);
$table->addCell(null, $tbc)->addText("Catering Service", $tbStyle);
$table->addCell(null, $tbc)->addText("Php 39,000.00", $tbStyle);

$table->addRow(43.2);
$table->addCell(null, $tbc)->addText("B", $tbStyle, $c);
$table->addCell(null, $tbc)->addText("Printing Services of Streamers", $tbStyle);
$table->addCell(null, $tbc)->addText("Php 5,000.00", $tbStyle);

$table->addRow(43.2);
$table->addCell(null, $tbc)->addText("C", $tbStyle, $c);
$table->addCell(null, $tbc)->addText("Printing Services of Polo Shirt", $tbStyle);
$table->addCell(null, $tbc)->addText("Php 13,440.00", $tbStyle);

$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("The Bicol University now requests proposals from bonafide suppliers to submit proposals for the ");
$textrun->addText(htmlspecialchars($Project, ENT_QUOTES), ['italics' => true]);

$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("Procurement will be conducted through ");
$textrun->addText("Negotiated Procurement", ['italic' => true]);
$textrun->addText(" - an alternative method of procurement specified and prescribed under rule XVI - Alternative Modes of Procurement, Section 53.9 - Negotiated Procurement (Small Value Procurement), of the Implementing Rules and Regulations Part-A (IRR-A) of Republic Act No. 9184, otherwise known as Government Procurement Reform Act.");

$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("Supplier shall submit proposals on or before __________________, ");
$textrun->addText("12:00 NN ", ['italic' => true]);
$textrun->addText("to the BAC Secretariat, G/F General Administration Bldg., Bicol University, Legazpi City.");

$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("Bicol University reserves the right to reject any or all the bids, waive defect or informality therein, accept the bid and award the contract to the most advantageous offer to the Bicol University, for and in behalf of the project. Bicol University assumes no responsibility to compensate or indemnify the bidder for any expense or loss that may be incurred for the preparation of bids, nor does it guarantee that an award will be made.");

$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'left']);
$textrun->addText("For further information, please contact ");
$textrun->addText("The BAC Secretariat Office, Bicol University, Legazpi City 4500, and Telefax: (052) 480-3264, ", ['italic' => true]);
$textrun->addText("bu_bacsecretariat@yahoo.com", ['underline' => 'single']);
$textrun->addText(" / ");
$textrun->addText("bubacsecretariat@gmail.com", ['underline' => 'single']);
$textrun->addText(".");

$section->addTextBreak(4);

$section->addText("Approved:");

$section->addTextBreak(2);

$section->addText("Prof. JERRY S. BIGORNIA", ['bold' => true, ['size' => 11]]);
$section->addText("BAC Chairperson");


$section->addTextBreak(10);

$section->addText("\t\t\t\t\t\t\t\tTransaction Reference No. _______________", ['size' => 9]);



$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('C:/Users/Denver/Desktop/PROP.docx');
// $objWriter->save("php://output");

?>