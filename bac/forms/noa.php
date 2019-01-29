<?php 

require_once '../../core/init.php';

$admin = new Admin();

$gds = base64_decode($_GET['q']);
// canvass id
$cv_id = $_GET['id'];
// canvass supplier id
$sp_id = $_GET['spid'];


$project = $admin->get('projects', array('project_ref_no', '=', $gds));
$canvass = $admin->get('canvass_forms', array('id', '=', $cv_id));


$supplier = $admin->awardSupplier($sp_id);


$phpWord = new \PhpOffice\PhpWord\PhpWord();

$file = $gds." - Notice of Award to ".$supplier->name.".docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(11);

$section = $phpWord->addSection([
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 993.6,
	'marginRight' => 806.4,
	'headerHeight' => 360,
	'footerHeight' => 0
]);
$header = $section->addHeader();
$table = $header->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow();
$cellImage = $table->addCell(1300, ['vMerge' => 'restart']);
$cellImage->addImage('../../assets/pics/Office logo.jpg', ['width' => 57.6,'height' => 55.44,'wrappingStyle' => 'square','positioning' => 'absolute','posHorizontal' => 'inside', 'posHorizontalRel' => 'margin', 'posVerticalRel' => 'line']);
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



$r = ['alignment' => 'right'];
$c = ['alignment' => 'center'];
$cc = ['valign' => 'center'];
$gs2 = ['gridSpan' => 2];
$fb = ['bold' => true];
$cStyle = ['indentation' => ['left' => 200, 'right' => 300]];


$section->addTextBreak(1);
$section->addText(Date::translate(Date::translate('test', 'now'), '2'));
$section->addTextBreak(3);

$section->addText("NOTICE OF AWARD", ['bold' => true, 'size' => 15], $c);
$section->addTextBreak(3);

$section->addText("The Manager");
$section->addText($supplier->name, $fb);
$section->addText($supplier->address);
$section->addTextBreak(3);

$section->addText("Sir/Madam:");
$section->addTextBreak(1);


$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("We are happy to notify you that your proposal for ");
$textrun->addText($project->project_title, ['bold' => true, 'italic' => true]);
$textrun->addText(" is hereby awarded to you as a Single Calculated Bid at the Contract Price of Equivalent to ");
// spell out cost
$ABC = $canvass->cost;
$ex = explode('.', $ABC);
$countABC = count($ex);
if($countABC === 1)
{
	// $ABC = sprintf("%s0", $ABC);
	$whole = $ex[0];
}
elseif($countABC === 2)
{
	list($whole, $decimal) = $ex;
	if($decimal <= 10)
	{
		$decimal = sprintf("%d0", $decimal);
		// $ABC = sprintf("%s0", $ABC);
	}
}

$format = new NumberFormatter("en", NumberFormatter::SPELLOUT);
$whole = $format->format($whole);

if($countABC === 1) $textrun->addText(ucwords($whole)." Pesos Only (Php".$ABC.")", ['bold' => true, 'italic' => true]);
elseif($countABC === 2) $textrun->addText(ucwords($whole)." and ".$decimal."/100 Pesos (Php ".$ABC.")", ['bold' => true, 'italic' => true]);

if($canvass->type === "PR"){
	$section->addTextBreak(1);
	// print all items awarded
	$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
	$table->addRow(43.2);
	$table->addCell(1000, $cc)->addText("Item No.", $fb, $c);
	$table->addCell(1000, $cc)->addText("Qty", $fb, $c);
	$table->addCell(1000, $cc)->addText("Unit", $fb, $c);
	$table->addCell(5000, $cc)->addText("Particulars", $fb, $c);
	$table->addCell(1400, $cc)->addText("Unit Price", $fb, $c);
	$table->addCell(1400, $cc)->addText("Total", $fb, $c);

	$total = 0;
	foreach($admin->getallawardedItems($gds, $sp_id, $canvass->per_item) as $key => $item){
		$count = $key + 1;
		$table->addRow();
		$table->addCell(null, $cc)->addText($count, $fb, $c);
		$table->addCell(null, $cc)->addText($item->quantity, null, $c);
		$table->addCell(null, $cc)->addText($item->unit, null, $c);
		$table->addCell(null, $cc)->addText($item->item_description);
		$table->addCell(null, $cc)->addText(Date::translate($item->unit_cost, 'php'), null, $c);
		$table->addCell(null, $cc)->addText(Date::translate($item->total_cost, 'php'), null, $c);
		$total += $item->total_cost;
	}

	$table->addRow();
	$table->addCell(null, array_merge($cc, ['gridSpan' => 5]))->addText("Grand Total", $fb, $r);
	$table->addCell(null, $cc)->addText(Date::translate($total, 'php'), $fb, $c);
}

$section->addTextBreak(1);

$section->addText("Terms and conditions relative to the implementation of the contract shall be in accordance with the Implementing Rules and Regulations of R.A. 9184.");
$section->addTextBreak(1);
$section->addText("Thank you.");
$section->addTextBreak(2);

$section->addText("Very truly yours.");

foreach(json_decode($project->end_user, true) as $end_user){
	$end_users[] = $admin->get('enduser', array('edr_id', '=', $end_user)); 
}


$designation = $admin->get('units', array('ID', '=', $end_users[0]->edr_designated_office));

$section->addTextBreak(2);
$section->addText($designation->approving, ['size' => 12, 'bold' => true]);
$section->addText($designation->approving_position);


$section->addTextBreak(2);
$section->addText("Conforme:");
$section->addTextBreak(1);
$section->addText("_____________________");
$section->addText("_____________________");
$section->addText("Date: ________________");

$section->addTextBreak(2);
$textrun = $section->addTextRun();
$textrun->addText("Trans. No. ");
$textrun->addText($gds, ['underline' => 'single']);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('C:/Users/Denver/Desktop/Abstract.docx');
$objWriter->save("php://output");
?>