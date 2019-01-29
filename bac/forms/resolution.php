<?php
require_once "../../core/init.php";
$admin = new Admin();
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$gds = base64_decode($_GET['q']);
$lot = $_GET['l'];
$title = base64_decode($_GET['t']);
$sp_id = $_GET['spid'];
$mop_index = $_GET['m'];

$file = $gds." - BAC Resolition Declaration.docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

// $documentProtection = $phpWord->getSettings()->getDocumentProtection();
// $documentProtection->setEditing(\PhpOffice\PhpWord\SimpleType\DocProtect::READ_ONLY);
// $documentProtection->setPassword('PrMO');


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
// $cStyle = ['indentation' => ['left' => 200, 'right' => 300]];

// project details
$project = $admin->get('projects', array('project_ref_no', '=', $gds));
// canvass details
$canvass = $admin->selectCanvassForm($gds, $title, $lot);
// suppliers that bid on this project
$suppliers = $admin->abstractSuppliers($gds, $lot);
// awarded supplier
$supplier = $admin->awardSupplier($sp_id);


$textrun = $section->addTextRun(['alignment' => 'center', 'lineHeight' => 1, 'space' => ['before' => 0, 'after' => 72]]);
$textrun->addText("BAC RESOLUTION DECLARING ", $fb);
$textrun->addText(strtoupper($supplier->name), $fb);
$textrun->addText(" AS THE BIDDER WITH THE SINGLE/LOWEST CALCULATED AND RESPONSIDE BID (SCRB/LCRB) FOR THE CONTRACT: ", $fb);
$textrun->addText(strtoupper($project->project_title), $fb);
$section->addText("Resolution No. LCRB-{$gds}", ['size' => 10], $c);
$section->addTextBreak(1);

$textrun = $section->addTextRun(['alignment' => 'both']);
$textrun->addText("WHEREAS, the BICOL UNIVERSITY requested for the project: ");
$textrun->addText($project->project_title, ['italic' => true]);
$textrun->addText(" with an Approved Budget for the Contract (ABC): ");
$section->addTextBreak(1);

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

if($countABC === 1) $textrun->addText(ucwords($whole)." Pesos Only (Php".number_format($ABC, 2).");", ['italic' => true]);
elseif($countABC === 2) $textrun->addText(ucwords($whole)." and ".$decimal."/100 Pesos (Php ".number_format($ABC, 2).");", ['italic' => true]);


$mop = json_decode($canvass->CanvassDetails->mop, true);

switch($mop[$mop_index]['mode']){
	case "SVP":
		$mop_name = "Small Value Procurement";
		break;
	default:
		$mop_name = $mop[$mop_index]['mode'];
}

$section->addText("WHEREAS, the BAC resorted to Negotiated Procurement under ".$mop_name." (Section ".$mop[$mop_index]['no'].") as an alternative mode and posted the project in the Bulletin Board for the period ".date('F')." ".date('d')."-".date('d', strtotime('+7 days')).", ".date('Y').";", null, $b);
$section->addTextBreak(1);

$section->addText("WHEREAS, the committee waived the formalities of competitive bidding procedures and issued Request for Quotation from supplies providers of known qualifications;", null, $b);
$section->addTextBreak(1);


$bidders = $admin->getSupplierTotal($gds, $lot);


$section->addText("WHEREAS, ".$format->format(count($bidders))." (".count($bidders).") supplies providers provided quotation with the following results");
$section->addTextBreak(1);

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow(43.2);
$table->addCell(5000, $cc)->addText("Bidders", $fb, $c);
$table->addCell(2000, $cc)->addText("Bid Amount", $fb, $c);
$table->addCell(4000, $cc)->addText("Remarks", $fb, $c);

foreach($bidders as $bidder){
	$table->addRow(43.2);
	$table->addCell(null, $cc)->addText($bidder->name);
	$table->addCell(null, $cc)->addText(Date::translate($bidder->total, 'php'), null, $c);
	$table->addCell(null, $cc)->addText($bidder->remark, null, $c);
}

$section->addTextBreak(1);
$textrun = $section->addTextRun(['alignment' => 'both', 'lineHeight' => 1, 'space' => ['before' => 0, 'after' => 72]]);
$textrun->addText("WHEREAS, upon examination, validation, and verification of all the eligibility, technical and financial requirements submitted by the ");
$textrun->addText(strtoupper($supplier->name), null, $fb);
$textrun->addText(", its bids was found to be responsive;");

$section->addTextBreak(1);
$section->addText("\tNOW, THEREFORE, We, the Members of the Bids and Awards Committee, hereby RESOLVE as it is hereby RESOLVED:");
$section->addTextBreak(1);


$listrun = $section->addListItemRun(0, 'mult', $b);
$listrun->addText("To declare ");
$listrun->addText(strtoupper($supplier->name), $fb);
$listrun->addText(" as the bidder with the single/lowest calculated and responsive bid for the contract: ");
$listrun->addText($project->project_title, ['bold' => true, 'italic' => true]);
$listrun->addText(" in the amount equivalent to ");

if($countABC === 1) $listrun->addText(ucwords($whole)." Pesos Only (Php".number_format($ABC, 2).");", ['italic' => true, 'bold' => true]);
elseif($countABC === 2) $listrun->addText(ucwords($whole)." and ".$decimal."/100 Pesos (Php ".number_format($ABC, 2).");", ['italic' => true, 'bold' => true]);

foreach(json_decode($project->end_user, true) as $end_user){
	$end_users[] = $admin->get('enduser', array('edr_id', '=', $end_user)); 
}

if($end_users[0]->edr_ext_name !== "XXXXX"){
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname." ".$end_users[0]->edr_ext_name;
}else{
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname;
}


$designation = $admin->get('units', array('ID', '=', $end_users[0]->edr_designated_office));

$section->addTextBreak(1);

$listrun = $section->addListItemRun(0, 'mult', $b);
$listrun->addText("To recommend for approval by the ".$designation->approving_position." the foregoing findings.");


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
// $objWriter->save('C:/Users/Denver/Desktop/PROP.docx');
$objWriter->save("php://output");

?>