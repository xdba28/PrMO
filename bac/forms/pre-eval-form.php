<?php
require_once "../../core/init.php";

$admin = new Admin();

if($admin->isLoggedIn());
else{
	Redirect::To('../../index');
	die();
}

$phpWord = new \PhpOffice\PhpWord\PhpWord();

// $documentProtection = $phpWord->getSettings()->getDocumentProtection();
// $documentProtection->setEditing(\PhpOffice\PhpWord\SimpleType\DocProtect::READ_ONLY);
// $documentProtection->setPassword('PrMO');


$REQ = $_GET['g'];
$data = $admin->get("projects", array('project_ref_no', '=', $REQ));

$file = $REQ." - Technical Working Group Pre-evaluation.docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$c = ['alignment' => 'center'];
$hPragr = ['indentation' => ['left' => 1296, 'right' => 0]];

$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(10);

$section = $phpWord->addSection([
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 576,
	'marginRight' => 576,
	'headerHeight' => 360,
	'footerHeight' => 0,
	'pageSizeW' => 12240,
	'pageSizeH' => 18720
]);
$header = $section->addHeader();

$table = $header->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2]);

$table->addRow(200);
$cellImage = $table->addCell(1300, ['vMerge' => 'restart']);
$cellImage->addImage('../../assets/pics/Office logo.jpg', [
	'width' => 57.6,
	'height' => 55.44,
	'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontal'    => 'inside',
    'posHorizontalRel' => 'margin',
	'posVerticalRel' => 'line'
]);

$table->addCell(8000);

$cellBox = $table->addCell(1300, ['vMerge' => 'restart']);
$textbox = $cellBox->addTextBox([
	'alignment' => 'center',
	'width' => 120,
	'height' => 50,
	'borderSize' => 2,
	'borderColor' => '#000000'
]);
$textbox->addText("Transaction no:", null, $c);
$textbox->addText("");
$textbox->addText(htmlspecialchars($REQ, ENT_QUOTES), ['size' => 11, 'bold' => true], $c);

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("Republic of the Philippines", null, ['alignment' => 'left']);
$table->addCell(null, ['vMerge' => 'continue']);

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("BICOL UNIVERSITY", ['size' => 11], ['alignment' => 'left']);
$table->addCell(null, ['vMerge' => 'continue']);

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("Legazpi City", null, ['alignment' => 'left']);
$table->addCell(null, ['vMerge' => 'continue']);

$section->addTextBreak(1);

$section->addText("PRE-PROCUREMENT EVALUATION", ['size' => 12, 'bold' => true], $c);
$section->addText("(Review by Technical/TWG Member)", ['size' => 9], $c);

$section->addtextBreak(1);

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2]);

$table->addRow(300);

$refno = json_decode($data->request_origin, true);
$p = '';
foreach($refno as $ref){
	$p = $p.$ref."\n";
}

$table->addCell(1700, ['vMerge' => 'restart'])->addText("Reference No.\n\n".$p);
$table->addCell(3000)->addText("Goods    Civil Words  ", null, $c);
$table->addCell(1300)->addText("Consultancy");
$table->addCell(3000, ['gridSpan' => 2])->addText("Mode of Procurement", null, $c);
$textrun = $table->addCell(4000, ['vMerge' => 'restart', 'gridSpan' => 2]);
$textrun->addText("Reviewed by:");
$textrun->addTextBreak(1);
$textrun->addText($data->evaluator);

$table->addRow(700);

if(!$data->mop_peritem){

	if($data->MOP == "PB"){
		$table->addCell(null, ['vMerge' => 'continue']);
		$table->addCell();
		$table->addCell();
		$table->addCell(850)->addText("&#10003;");
		$table->addCell()->addText("Public Bidding");
		$table->addCell(null, ['vMerge' => 'continue', 'gridSpan' => 2]);
	
		$table->addRow(1000);
	
		$ProjName = $table->addCell(5000, ['gridSpan' => 3]);
		$ProjName->addtext(htmlspecialchars("Project Name:", ENT_QUOTES), ['bold' => true]);
		$ProjName->addtext($data->project_title, null, ['alignment' => 'both', 'indentation' => ['left' => 144, 'right' => 80], 'space' => ['before' => 70, 'after' => 70]]);
		$textrun = $ProjName->addTextRun();
		$textrun->addText("ABC: ", ['bold' => true]);
		$textrun->addText(Date::translate($data->ABC, 'php'));
		$table->addCell();
		$table->addCell()->addText("Alternative Mode, specify:");

	}else{
		$table->addCell(null, ['vMerge' => 'continue']);
		$table->addCell();
		$table->addCell();
		$table->addCell(850);
		$table->addCell()->addText("Public Bidding");
		$table->addCell(null, ['vMerge' => 'continue', 'gridSpan' => 2]);
	
		$table->addRow(1000);
	
		$ProjName = $table->addCell(5000, ['gridSpan' => 3]);
		$ProjName->addtext(htmlspecialchars("Project Name:", ENT_QUOTES), ['bold' => true]);
		$ProjName->addtext($data->project_title, null, ['alignment' => 'both', 'indentation' => ['left' => 144, 'right' => 80], 'space' => ['before' => 70, 'after' => 70]]);
		$textrun = $ProjName->addTextRun();
		$textrun->addText("ABC: ", ['bold' => true]);
		$textrun->addText(Date::translate($data->ABC, 'php'));
		$table->addCell()->addText("&#10003;");;
		$textrun = $table->addCell();
		$textrun->addText("Alternative Mode, specify:");
		$textrun->addText($data->MOP);
	}
	

}else{

	$mops = json_decode($data->mop_peritem, true);
	$table->addCell(null, ['vMerge' => 'continue']);
	$table->addCell();
	$table->addCell();
	$table->addCell(850);
	$table->addCell()->addText("Public Bidding");
	$table->addCell(null, ['vMerge' => 'continue', 'gridSpan' => 2]);

	$table->addRow(1000);

	$ProjName = $table->addCell(5000, ['gridSpan' => 3]);
	$ProjName->addtext(htmlspecialchars("Project Name:", ENT_QUOTES), ['bold' => true]);
	$ProjName->addtext($data->project_title, null, ['alignment' => 'both', 'indentation' => ['left' => 144, 'right' => 80], 'space' => ['before' => 70, 'after' => 70]]);
	$textrun = $ProjName->addTextRun();
	$textrun->addText("ABC: ", ['bold' => true]);
	$textrun->addText(Date::translate($data->ABC, 'php'));
	$table->addCell()->addText("&#10004;");;
	$textrun = $table->addCell();
	$textrun->addText("Alternative Mode, specify:");
	foreach($mops as $key => $mop){
		$textrun->addText($key);
	}

}

// $table->addCell(null, ['vMerge' => 'continue']);
// $table->addCell();
// $table->addCell();
// $table->addCell(850)->addText("&#10004;");
// $table->addCell()->addText("Public Bidding");
// $table->addCell(null, ['vMerge' => 'continue', 'gridSpan' => 2]);

// $table->addRow(1000);

// $ProjName = $table->addCell(5000, ['gridSpan' => 3]);
// $ProjName->addtext(htmlspecialchars("Project Name:", ENT_QUOTES), ['bold' => true]);
// $ProjName->addtext($data->project_title, null, ['alignment' => 'both', 'indentation' => ['left' => 144, 'right' => 80], 'space' => ['before' => 70, 'after' => 70]]);
// $textrun = $ProjName->addTextRun();
// $textrun->addText("ABC: ", ['bold' => true]);
// $textrun->addText(Date::translate($data->ABC, 'php'));
// $table->addCell();
// $table->addCell()->addText("Alternative Mode, specify:");

$textrun = $table->addCell();
$date = $admin->pre_evalDate($REQ);
$textrun->addText("Date received:", ['size' => 9]);
if(count($date) !== 0){
	$textrun->addText(Date::translate($date[0]->logdate, 2), ['size' => 9]);
}else{
	$textrun->addText("");
}
$table->addCell()->addText("Date returned:", ['size' => 9]);


$table->addRow(900);

$textrun = $table->addCell(null, ['gridSpan' => 7])->addTextRun();
$textrun->addTextBreak(1);
$textrun->addText("     Procurement shall be", ['italic' => true, 'size' => 9]);
$textrun->addText("\t\t");
$textrun->addCheckBox("ev1", htmlspecialchars("  publish & evaluate as a single lot", ENT_QUOTES), ['size' => 9]);
$textrun->addText("\t\t");
$textrun->addCheckBox("ev2", htmlspecialchars("  publish & evaluate into ____ lots", ENT_QUOTES), ['size' => 9]);

$textrun->addTextBreak(1);
$textrun->addText("         (check the box)", ['italic' => true, 'size' => 9]);
$textrun->addText("\t\t");
$textrun->addCheckBox("ev3", htmlspecialchars("  publish & evaluate as a per category", ENT_QUOTES), ['size' =>9]);
$textrun->addText("\t\t");
$textrun->addCheckBox("ev4", htmlspecialchars("  publish & evaluate per item", ENT_QUOTES), ['size' => 9]);

$table->addRow(300);
$table->addCell(null, ['gridSpan' => 7, 'valign' => 'center'])->addText("REQUIREMENTS EVALUATION", ['size' => 11, 'bold' => true], $c);

$table->addRow(2000);
$textrun = $table->addCell(null, ['gridSpan' => 7]);
$textrun->addText("Evaluation of Requirements (Technical Specifications, Scope of Work, Terms of Reference)");
$textrun->addTextBreak(1);
$textrun->addText($data->evaluators_comment);

$span = ['gridSpan' => 2, 'valign' => 'center'];

$table->addRow(300);
$table->addCell(null, $span)->addText("Components", null, $c);
$table->addCell(null, ['valign' => 'center'])->addText("Sufficient", null, $c);
$table->addCell(null, ['gridSpan' => 4, 'valign' => 'center'])->addText("Insufficient \t\t      Recommended Details", null, ['indentation' => ['left' => 144, 'right' => 0]]);

$table->addRow(700);
$table->addCell(null, $span)->addText("Delivery Requirements (Contract Duration/Period)");
$table->addCell();
$table->addCell(null, ['gridSpan' => 4]);

$table->addRow(700);
$table->addCell(null, $span)->addText("Manpower Requirement/Key Personnel");
$table->addCell();
$table->addCell(null, ['gridSpan' => 4]);

$table->addRow(500);
$table->addCell(null, $span)->addText("Equipment/Material Requirement");
$table->addCell();
$table->addCell(null, ['gridSpan' => 4]);

$table->addRow(700);
$table->addCell(null, $span)->addText("Cost Estimate/Approved Budget for the Contract");
$table->addCell();
$table->addCell(null, ['gridSpan' => 4]);

$table->addRow(1500);
$table->addCell(null, ['gridSpan' => 7])->addText(htmlspecialchars("Delivery Terms & Conditions/Other Observation/s:", ENT_QUOTES));

$Fnine = ['size' => 9];

$section->addText("      Note:", ['size' => 9, 'italic' => true]);
$section->addText("            1. Fill out the applicable section only, place N/A for inapplicable portions;", $Fnine);
$section->addText("            2. Requirements evaluation shall be accomplished based on the documents submitted by the end-user, and the recommendations", $Fnine);
$section->addText("                may be provided as deemed necessary by the reviewing person;", $Fnine);
$section->addText("            3. Details provided shall be presented by BAC for approval. If necessary (Pre-Procurement Conference);", $Fnine);
$section->addText("            4. Specific details in the evaluation shall be reflected in the bidding document (Bid Forms);", $Fnine);
$section->addText("            5. Other materials as indicated in the category of goods include supplies that are not regularly used by the offices of the university;", $Fnine);
$section->addText("            6. Additional page may be added, if necessary.", $Fnine);

$section->addtextBreak(2);

$section->addText("      BU-F-BAC 004 \t\t\t\t\t\t\t\t\t\t\t    Revision: 2", $Fnine);
$section->addText("      Effective: January 29, 2016", $Fnine);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('C:/Users/Denver/Desktop/EVAL.docx');
$objWriter->save("php://output");
?>