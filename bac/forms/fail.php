<?php
require_once "../../core/init.php";
$admin = new Admin();
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$gds = base64_decode($_GET['q']);
$title = base64_decode($_GET['t']);
$canvass_id = $_GET['l'];
$mop_index = $_GET['m'];

$project = $admin->get('projects', array('project_ref_no', '=', $gds));
$canvass = $admin->selectCanvassForm($gds, $title, $canvass_id);
$prop = $admin->getPublication($gds, $canvass_id);

$file = $gds." - Resolution Declairing failure of bidding.docx";
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
$textrun->addText("BAC RESOLUTION DECLAIRING FAILURE OF BIDDING FOR THE PROJECT: ", $fb);
$textrun->addText(strtoupper($project->project_title), $fb);


if($canvass->CanvassDetails->per_item){

	$fail_item_no = [];
	foreach($canvass->items as $key => $item){
		$count = $key + 1;
		if($item->item_fail){
			array_push($fail_item_no, $count);
		}
	}

	$textrun->addText(" (ITEM # ", $fb);

	$fail_item_length = count($fail_item_no);
	if($fail_item_length === 1){
		$textrun->addText($fail_item_no[0].")", $fb);
	}else{
		for($i = 0; $i < $fail_item_length; $i++){ 
			if($i < $fail_item_length){
				$textrun->addText(htmlspecialchars("& ".$fail_item_no[$i].")"), $fb);
			}else{
				$textrun->addText($fail_item_no[$i].", ", $fb);
			}
		}
	}
}

$section->addText("FB-".$gds, ['size' => 10], $c);
$section->addTextBreak(1);

$textrun = $section->addTextRun($b);
$textrun->addText("WHEREAS, the Bicol University, through the Bids and Awards Committee requested for the project: ");
$textrun->addText($project->project_title);
$textrun->addText(" with an Approved Budget for the Contract (ABC) of ");

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

if($mop[$mop_index]['mode'] === "SVP"){
	$section->addText("WHEREAS, the BAC resorted to Negotiated Procurement under ".$mop_name." (Section ".$mop[$mop_index]['no'].") of the Revised IRR of R.A. 9184, and issued Request for Quotations to suppliers known qualifications;", null, $b);
	$section->addTextBreak(1);
}else{
	$section->addText("WHEREAS, the BAC resorted to ".$mop_name." (Section ".$mop[$mop_index]['no'].") of the Revised IRR of R.A. 9184, and issued Request for Quotations to suppliers known qualifications;", null, $b);
	$section->addTextBreak(1);
}


$bidders = $admin->getSupplierTotal($gds, $canvass_id);

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
	if($canvass->CanvassDetails->per_item){
		
		$textrun = $table->addCell(null, $cc)->addTextRun($c);
		// get each item and their remarks
		$remarks = $admin->getPerItemRemark($gds, $canvass_id, $bidder->s_id);
		$remarks_count = count($remarks);

		foreach($remarks as $key => $remark){
			$count = $key + 1;
			if($count < $remarks_count){
				$textrun->addText("Item # ".$count." ".$remark->q_remark.", ", ['size' => 9]);
			}else{
				$textrun->addText(htmlspecialchars("& Item #. ".$count." ".$remark->q_remark), ['size' => 9]);
			}
		}

	}else{
		$table->addCell(null, $cc)->addText($bidder->remark, null, $c);
	}
}

$section->addTextBreak(1);
$section->addText("WHEREAS, the suppliers failed to pass the evaluation for the said project;");
$section->addTextBreak(1);

$section->addText("NOW, THEREFORE, WE, the Members of the Bids and Award Committee, hereby RESOLVE as it is hereby RESOLVED:", null, $b);
$section->addTextBreak(1);

$listrun = $section->addListItemRun(0, 'mult', $b);
$listrun->addText("To declare failure of bidding for the project ");
$listrun->addText($project->project_title);
$listrun->addText(" (Item # ");

if($fail_item_length === 1){
	$listrun->addText($fail_item_no[0].")");
}else{
	for($i = 0; $i < $fail_item_length; $i++){ 
		if($i < $fail_item_length){
			$listrun->addText(htmlspecialchars("& ".$fail_item_no[$i].")"));
		}else{
			$listrun->addText($fail_item_no[$i].", ");
		}
	}
}

$listrun->addText(" and to recommend review of requirements by the end-user;");
$section->addTextBreak(1);

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