<?php
require_once "../../core/init.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();

// $documentProtection = $phpWord->getSettings()->getDocumentProtection();
// $documentProtection->setEditing(\PhpOffice\PhpWord\SimpleType\DocProtect::READ_ONLY);
// $documentProtection->setPassword('PrMO');

// $file = $REQUEST[0].".docx";
// header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
// header('Content-Disposition: attachment; filename="'.$file.'"');


$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(11);

$section = $phpWord->addSection([
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 993.6,
	'marginRight' => 806.4,
	'headerHeight' => 360,
	'footerHeight' => 0,
	'pageSizeW' => 12240,
	'pageSizeH' => 18720
]);
$header = $section->addHeader();
$table = $header->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2]);
$table->addRow();
$cellImage = $table->addCell(1300, ['vMerge' => 'restart']);
$cellImage->addImage('../../assets/pics/Office logo.jpg', ['width' => 57.6,'height' => 55.44,'wrappingStyle' => 'square','positioning' => 'absolute','posHorizontal'    => 'inside','posHorizontalRel' => 'margin','posVerticalRel' => 'line']);
$table->addCell(8000);
$table->addCell(1300, ['vMerge' => 'restart'])->addTextBox(['width' => 120,'height' => 50,'borderColor' => '#FFFFFF']);
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

$section->addText("Request for Proposals", ['size' => 15, 'bold' => true], ['alignment' => 'center']);

$section->addTextBreak(1);

$section->addText("__________________");
$section->addText("__________________");

$section->addTextBreak(2);

$section->addText("Sir/Madam:");
$section->addText(htmlspecialchars("\tPlease quote your best offer for the item/s listed below, subject to the Terms & Conditions printed at the back.", ENT_QUOTES));
$textrun = $section->addTextRun();
$textrun->addText("Submit your proposals duly signed by your representative not later than ______________ 12NN, to ");
$textrun->addText("Bicol University - BAC Secretariat Office", ['italic' => true, 'underline' => 'single']);
$textrun->addText(".");

$section->addText("\tOpen quotations may be submitted manually or through facsimile or email at the address and contact numbers indicated above.");

$section->addTextBreak(1);


$section->addText("\t\t\t\t\t\t\t\t\t".strtoupper("Benigno O. Austero"), ['size' => 13, 'bold' => true]);
$section->addText("\t\t\t\t\t\t\t\t\t      Head, BAC Secretariat", ['size' => 12]);

$section->addTextBreak(1);

$section->addText(htmlspecialchars("\tAfter having carefully read and accepted the Terms & Conditions, I/We submit out quotations/s for the item/s as follows:", ENT_QUOTES));

$c = ['alignment' => 'center'];
$sC = ['valign' => 'center'];

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
$table->addRow(662.4);
$table->addCell(1900, ['vMerge' => 'restart', 'valign' => 'center'])->addText("Ref/ ABC/ Item GDS-2018-645(C) Php 95,351");
$table->addCell(518.4, $sC)->addText("Unit", null, $c);
$table->addCell(720, $sC)->addText("Qty", null, $c);
$table->addCell(3672, $sC)->addText("Description", null, $c);
$table->addCell(1238.4, $sC)->addText("Unit Price", null, $c);
$table->addCell(1800, $sC)->addText("Compliance to Technical Specifications", null, $c);
$table->addCell(1296, $sC)->addText("Offer", null, $c);

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$textrun = $table->addCell(null, ['gridSpan' => 6, 'valign' => 'center'])->addTextRun();
$textrun->addText("PROVIDE PRINTING SERVICES", ['bold' => true, 'italic' => true, 'underline' => 'single']);
$textrun->addText(" of Polo Shirts for use during the College Foundation Day Celebration 2018 of BUCN, with details as follows:", ['italic' => true]);

$table->addRow(600);
$table->addCell(null, $sC)->addText("1", null, $c);
$table->addCell(null, $sC)->addText("lot", null, $c);
$table->addCell(null, $sC)->addText("1", null, $c);
$cell = $table->addCell();
$cell->addText("48 Pieces Polo Shirt for Printing Services");
$cell->addTextBreak(1);
$cell->addText("***see attached design and other details", ['size' => 9]);
$table->addCell();
$table->addCell();
$table->addCell();

$table->addRow(790);
$textrun = $table->addCell(null, ['gridSpan' => 7])->addTextRun();
$textrun->addText("Service Delivery Conditions", ['size' => 9]);
$textrun->addTextBreak(1);
$textrun->addText("    ");
$textrun->addCheckBox("del1", "  Delivery of item is required:", ['size' => 9]);
$textrun->addTextBreak(1);
$textrun->addText("    ");
$textrun->addCheckBox("del2", "  Details related to implementation shall be communicated with ", ['size' => 9]);
$textrun->addText("Ms. Charina J. Cipcon;", ['size' => 9, 'bold' => true]);
$textrun->addText(" End-User", ['size' => 9, 'bold' => true]);

$table->addRow(43.2);
$table->addCell(null, ['valign' => 'center'])->addText("Price Validity", ['size' => 9]);
$table->addCell(null, ['gridSpan' => 3]);
$table->addCell()->addText("Payment Term:", ['size' => 9]);
$table->addCell(null, ['gridSpan' => 2]);

$section->addTextBreak(2);

$section->addText("\t\t\t\t\t\t\t\t\t\t  ___________________________");
$section->addText("\t\t\t\t\t\t\t\t\t\t             Printed Name / Signature", ['size' => 10]);

$section->addText("", ['size' => 8]);

$section->addText("\t\t\t\t\t\t\t\t\t\t  ___________________________");
$section->addText("\t\t\t\t\t\t\t\t\t\t\t             T.I.N. #", ['size' => 10]);

$section->addText("", ['size' => 8]);

$section->addText("\t\t\t\t\t\t\t\t\t\t  ___________________________");
$section->addText("\t\t\t\t\t\t\t\t\t\t      Contact Numbers/e-mail address", ['size' => 10]);

$section->addTextBreak(5);

$section->addText("Served by/Date: ______________________");


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('C:/Users/Denver/Desktop/Request for Proposal Canvas.docx');
// $objWriter->save("php://output");
?>
