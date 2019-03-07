<?php
require_once "../../core/init.php";
$admin = new Admin();
$phpWord = new \PhpOffice\PhpWord\PhpWord();


$gds = base64_decode($_GET['rq']);
// canvass_forms id
$form_id = $_GET['f'];
// mop_index
$mop_index = $_GET['m'];

$file = $gds." - Resolution Mode of Procurement.docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$phpWord->addNumberingStyle("mult", [
	'type'   => 'multilevel',
    'levels' => [
		['format' => 'lowerLetter', 'text' => '%1.', 'left' => 993.6, 'hanging' => 0, 'tabPos' => 720, 'size' => 11]
	]
]);


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


$r = ['alignment' => 'right'];
$c = ['alignment' => 'center'];
$cc = ['valign' => 'center'];
$gs2 = ['gridSpan' => 2];
$fb = ['bold' => true];
$b = ['alignment' => 'both'];


// project details
$project = $admin->get('projects', array('project_ref_no', '=', $gds));
$canvass = $admin->get('canvass_forms', array('gds_reference', '=', $gds));
$prop = $admin->getPublication($gds, $form_id);



$mop = json_decode($prop->mop, true);

switch($mop[$mop_index]['mode']){
	case "SVP":
		$mop_name = "Small Value Procurement";
		break;
	case "PB":
		$mop_name = "Public Bidding";
		break;
	case "DC":
		$mop_name = "Direct Contracting";
		break;
	default:
		$mop_name = $mop[$mop_index]['mode'];
}


$textrun = $section->addTextRun(['alignment' => 'center', 'lineHeight' => 1, 'space' => ['before' => 0, 'after' => 72]]);
$textrun->addText("BAC RESOLUTION RECOMMENDING ", $fb);

if($mop[$mop_index]['mode'] === "SVP"){
	$textrun->addText("NEGOTIATED PROCUREMENT (".strtoupper($mop_name).")", $fb);
}else{
	$textrun->addText(strtoupper($mop_name), $fb);
}

$textrun->addText(" AS AN ALTERNATIVE MODE OF PROCUREMENT OF THE CONTRACT: ", $fb);
$textrun->addText(strtoupper($project->project_title), $fb);
$section->addText("Resolution No. AMP-{$gds}", ['size' => 10], $c);
$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("WHEREAS, the BICOL UNIVERSITY requested for the project: ");
$textrun->addText($project->project_title, ['italic' => true, 'bold' => true]);
$textrun->addText(" with an Approved Budget for the Contract (ABC): ");

$ABC = $project->ABC;
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

if($countABC === 1) $textrun->addText(ucwords($whole)." Pesos Only (Php".number_format($ABC, 2).");", ['italic' => true, 'bold' => true]);
elseif($countABC === 2) $textrun->addText(ucwords($whole)." and ".$decimal."/100 Pesos (Php ".number_format($ABC, 2).");", ['italic' => true, 'bold' => true]);

$section->addTextBreak(1);
$section->addText("WHEREAS, the item to be procured is needed in the performance of the requesting office, duly approved by the University President as reflected in the Annual Procurement Program for ".date('Y')." and the amount does not exceed the threshold set forth for the alternative mode of procurement;", null, $b);
$section->addTextBreak(1);

// check if pr or jo

if($mop[$mop_index]['mode'] === "SVP"){
	if($canvass->type === "PR"){
		$section->addText("WHEREAS, the procurement shall be in accordance with the conditions set forth in Section ".$mop[$mop_index]['no']." Negotiated Procurement (".$mop_name.") of the Revised IRR of R.A. 9184 and each item shall be evaluated separately for the purpose of bidding, evaluation, and contract award;", null, $b);
	}else{
		$section->addText("WHEREAS, the procurement shall be in accordance with the conditions set forth in Section ".$mop[$mop_index]['no']." Negotiated Procurement (".$mop_name.") of the Revised IRR of R.A. 9184;", null, $b);
	}
}else{
	if($canvass->type === "PR"){
		$section->addText("WHEREAS, the procurement shall be in accordance with the conditions set forth in Section ".$mop[$mop_index]['no']." ".$mop_name." of the Revised IRR of R.A. 9184 and each item shall be evaluated separately for the purpose of bidding, evaluation, and contract award;", null, $b);
	}else{
		$section->addText("WHEREAS, the procurement shall be in accordance with the conditions set forth in Section ".$mop[$mop_index]['no']." ".$mop_name." of the Revised IRR of R.A. 9184;", null, $b);
	}
}
$section->addTextBreak(1);

$section->addText("WHEREAS, the technical member evaluated the requirement for each item and found it to be in order and sufficient;", null, $b);
$section->addTextBreak(1);

$section->addText("WHEREAS, the BAC ensures that the most advantageous price for the government shall be obtained wherein a minimum of three (3) previously qualified suppliers shall be requested to submit quotations, duly signed and sealed, on the dates as specified in the publications;", null, $b);
$section->addTextBreak(1);

$section->addText("\tNOW THEREFORE, We, the members of the Bids and Awards Committee, hereby RESOLVE as it hereby resolved:");
$section->addTextBreak(1);

$listrun = $section->addListItemRun(0, 'mult', $b);
$listrun->addText("To recommend ");
if($mop[$mop_index]['mode'] === "SVP"){
	$listrun->addText("Negotiated Procurement (".$mop_name.") under Section ".$mop[$mop_index]['no']." as an alternative mode of procurement for the contract: ");
}else{
	$listrun->addText($mop_name." under Section ".$mop[$mop_index]['no']." as an alternative mode of procurement for the contract: ");
}
$listrun->addText($project->project_title, ['bold' => true, 'italic' => true]);

foreach(json_decode($project->end_user, true) as $end_user){
	$end_users[] = $admin->get('enduser', array('edr_id', '=', $end_user)); 
}

if($end_users[0]->edr_ext_name !== "XXXXX"){
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname." ".$end_users[0]->edr_ext_name;
}else{
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname;
}


$designation = $admin->get('units', array('ID', '=', $end_users[0]->edr_designated_office));

$listrun = $section->addListItemRun(0, 'mult', $b);
$listrun->addText("To recommend for approval by the ".$designation->approving_position);



$section->addTextBreak(1);
$section->addText("\tRESOLVED, at the Bicol University, Legazpi City, this ".date("jS \of F, Y"), null, $b);
$section->addTextBreak(2);


$chair = $admin->get('commitee', array('position', '=', 'BAC Chairperson'));
$vicechair = $admin->get('commitee', array('position', '=', 'Vice Chairman'));
$bacmember = $admin->get('commitee', array('position', '=', 'BAC Member'));
$technical = $project->evaluator;



$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow(500);
$cell = $table->addCell(9950.4, ['gridSpan' => 3]);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($chair->name), $fb);
$textrun->addTextBreak(1);
$textrun->addText($chair->position, ['size' => 9]);

$table->addRow(700);
$table->addCell(null, ['gridSpan' => 3]);

$table->addRow();
$cell = $table->addCell();
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($vicechair->name), $fb);
$textrun->addTextBreak(1);
$textrun->addText($vicechair->position, ['size' => 9]);

$cell = $table->addCell();
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($bacmember->name), $fb);
$textrun->addTextBreak(1);
$textrun->addText($bacmember->position, ['size' => 9]);

$cell = $table->addCell();
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($technical), $fb);
$textrun->addTextBreak(1);
$textrun->addText("Technical Member", ['size' => 9]);

$table->addRow(700);
$table->addCell(null, ['gridSpan' => 3]);

$table->addRow(500);
$cell = $table->addCell(9950.4, ['gridSpan' => 3]);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($userFullName), $fb);
$textrun->addTextBreak(1);
$textrun->addText("End-User/Member", ['size' => 9]);

$table->addRow(700);
$table->addCell(null, ['gridSpan' => 3]);

$table->addRow(500);
$table->addCell(null, ['vAlign' => 'top'])->addText("Approved:", ['size' => 9], $r);

$cell = $table->addCell();
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($designation->approving), $fb);
$textrun->addTextBreak(1);
$textrun->addText($designation->approving_position, ['size' => 9]);

$table->addCell();



$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");
?>