<?php 

require_once '../../core/init.php';

$admin = new Admin();

$gds = base64_decode($_GET['q']);
// canvass id
$cv_id = $_GET['id'];
// canvass supplier id
$sp_id = $_GET['spid'];
// lot name
$lot_title = $_GET['l'];


$project = $admin->get('projects', array('project_ref_no', '=', $gds));
$canvass = $admin->get('canvass_forms', array('id', '=', $cv_id));
$supplier = $admin->awardSupplier($sp_id);
$delivery = $admin->getDeliveryDetails($gds, $sp_id);


$phpWord = new \PhpOffice\PhpWord\PhpWord();

$orderName = ($canvass->type === "PR") ? "Purchase" : "Letter";

$file = $gds." - ".$orderName." Order for ".$supplier->name.".docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(10);

$section = $phpWord->addSection([
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 360,
	'marginRight' => 360,
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

foreach(json_decode($project->end_user, true) as $end_user){
	$end_users[] = $admin->get('enduser', array('edr_id', '=', $end_user)); 
}

if($end_users[0]->edr_ext_name !== "XXXXX"){
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname." ".$end_users[0]->edr_ext_name;
}else{
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname;
}

$unit = $admin->get('units', array('ID', '=', $end_users[0]->edr_designated_office));


if($canvass->type === "PR"){
	$section->addText("PURCHASE ORDER", array_merge($fb, ['size' => 12]), $c);
}else{
	$section->addText("LETTER ORDER", array_merge($fb, ['size' => 12]), $c);
}

$section->addText("Bicol University - ".$unit->acronym, null, $c);
$section->addTextBreak(1);

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);

$table->addRow(43.2);
$table->addCell(1400)
	->addText("Supplier:");
$table->addCell(5700, $gs2)
	->addText($supplier->name, $fb);
$table->addCell(1800)
	->addText("PO No.");
$table->addCell(2200, $gs2)
	->addText($gds, $fb, $c);


$table->addRow();
$table->addCell()
	->addText("Address:");
$table->addCell(null, $gs2)
	->addText($supplier->address);
$table->addCell()
	->addText("Date:");
$table->addCell(null, $gs2)
	->addText(Date::translate(Date::translate('test', 'now'), '2'), null, $c);


$table->addRow();
$table->addCell()
	->addText("TIN:");
$table->addCell(null, $gs2)
	->addText($supplier->tin);
$table->addCell()
	->addText("Mode of Payment");
$table->addCell(null, $gs2)
	->addText($delivery->payment, null, $c);


if($canvass->type === "PR"){
	$table->addRow();
	$table->addCell(null, ['gridSpan' => 6])
		->addText("Please furnish this office the articles subject to the terms and conditions contained herein", null, $c);
}else{
	$table->addRow();
	$cell = $table->addCell(null, ['gridSpan' => 6]);
	$textrun = $cell->addTextRun($c);
	$textrun->addText("Please be informed that you are recommended for the ", ['size' => 11]);
	$textrun->addText($project->project_title, ['size' => 11, 'bold' => true, 'italic' => true]);
}


$table->addRow();
$table->addCell(null, $gs2)
	->addText("Implementing Office");
$table->addCell()
	->addText($unit->acronym);
$table->addCell()
	->addText("End-User");
$table->addCell(null, $gs2)
	->addText($userFullName, null, $c);


$table->addRow();
$table->addCell(null, $gs2)
	->addText("Place of Delivery");
$table->addCell()
	->addText($delivery->place);
$table->addCell()
	->addText("Delivery Term");
$table->addCell(null, $gs2)
	->addText($delivery->delivery_term, null, $c);


$table->addRow();
$table->addCell(null, $gs2)
	->addText("Date of Delivery");
$table->addCell();
$table->addCell()
	->addText("Payment Term:");
$table->addCell(null, $gs2)
	->addText($delivery->payment, null, $c);




if($canvass->type === "PR"){
	// PR
	$table->addRow();
	$table->addCell()
		->addText("Item No.", $fb, $c);
	$table->addCell()
		->addText("Unit", $fb, $c);
	$table->addCell()
		->addText("Description", $fb, $c);
	$table->addCell()
		->addText("Quantity", $fb, $c);
	$table->addCell()
		->addText("Unit Cost", $fb, $c);
	$table->addCell()
		->addText("Amount", $fb, $c);
	
	$total = 0;
	foreach($admin->getallawardedItems($gds, $sp_id, $canvass->per_item) as $key => $item){
	// foreach($admin->docOrderItems($gds, $sp_id, $canvass->per_item) as $key => $item){
		$count = $key + 1;
		$table->addRow();
		$table->addCell()
			->addText($count, $fb, $c);
		$table->addCell()
			->addText($item->unit, null, $c);
		$table->addCell()
			->addText($item->item_description, null, $c);
		$table->addCell()
			->addText($item->quantity, null, $c);
		$table->addCell()
			->addText(Date::translate($item->unit_cost, 'php'), null, $c);
		$table->addCell()
			->addText(Date::translate($item->total_cost, 'php'), null, $c);
		$total += $item->total_cost;
	}

}else{

	// JO
	$table->addRow();
	$table->addCell()
		->addText("Item No.", $fb, $c);
	$table->addCell()
		->addText("Unit", $fb, $c);
	$table->addCell(null, $gs2)
		->addText("Description", $fb, $c);
	$table->addCell(null, $gs2)
		->addText("Amount", $fb, $c);

	$count = 1;
	$total = 0;
	foreach($admin->getawardedJO($gds, $sp_id, $canvass->per_item) as  $jo){
		$table->addRow();
		$table->addCell()
			->addText($count, null, $c);
		$table->addCell()
			->addText("lot", $fb, $c);

		$cell = $table->addCell(null, $gs2);
		$textrun = $cell->addTextRun();
		$textrun->addText($jo->title.":", $fb);
		$textrun->addTextBreak(1);

		$i = 0;
		$tags = explode(",", $jo->tags);
		$tags_count = count($tags);
		foreach($tags as $tag){
			if(++$i === $tags_count) $textrun->addText($tag);
			else $textrun->addText($tag.", ");
		}

		$textrun->addTextBreak(1);
		$textrun->addText($jo->notes);

		$table->addCell(null, $gs2)
			->addText(Date::translate($jo->cost, 'php'), $fb, $c);
		$total += $jo->cost;
		$count++;
	}


}

$ABC = $total;
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

if($countABC === 1){
	$table->addRow();
	$table->addCell(null, ['gridSpan' => 5])
		->addText("***".ucwords($whole)." Pesos Only (Php".number_format($ABC, 2).")***", $fb, $c);
}elseif($countABC === 2){
	$table->addRow();
	$table->addCell(null, ['gridSpan' => 5])
		->addText("***".ucwords($whole)." and ".$decimal."/100 Pesos (Php ".number_format($ABC, 2).")***", $fb, $c);
}

$table->addCell()
	->addText(Date::translate($total, 'php'), $fb, $c);

	// PR title here
$table->addRow();
$table->addCell(null, ['gridSpan' => 6])
	->addText($project->project_title, ['italic' => true], $c);


if($canvass->type === "PR"){

	$table->addRow();
	$cell = $table->addCell(null, ['gridSpan' => 6]);
	$textrun = $cell->addTextRun();
	$textrun->addText(htmlspecialchars("Supply & Delivery Condition:"), ['size' => 8, 'italic' => true]);
	$textrun->addTextBreak(1);
	$textrun->addText("1. Delivery of goods is required by ", ['size' => 8, 'italic' => true]);
	$textrun->addText($delivery->delivery_term.";", ['size' => 8, 'italic' => true, 'bold' => true, 'underline' => 'single']);
	$textrun->addTextBreak(1);
	$textrun->addText("2. Details related to implementation shall be communicated with ", ['size' => 8, 'italic' => true]);
	$textrun->addText($userFullName, ['underline' => 'single', 'italic' => true, 'size' => 8]);	

}else{

	$table->addRow();
	$cell = $table->addCell(null, ['gridSpan' => 6]);
	$textrun = $cell->addTextRun();
	$textrun->addText(htmlspecialchars("Service Delivery Condition:"), ['size' => 8, 'italic' => true]);
	$textrun->addTextBreak(1);
	$textrun->addText("1. Services provider shall provide sufficient manpower and materials needed for the delivery of services;", ['size' => 8, 'italic' => true]);
	$textrun->addTextBreak(1);
	$textrun->addText("2. Details related to implementation shall be communicated with ", ['size' => 8, 'italic' => true]);
	$textrun->addText($userFullName, ['underline' => 'single', 'italic' => true, 'size' => 8]);

}






$table->addRow(2000);
$cell = $table->addCell(null, ['gridSpan' => 6]);
$textrun = $cell->addTextRun();	
$textrun->addText("In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent from every day of delay shall be imposed.", ['size' => 8]);
$textrun->addTextBreak(1);
$textrun->addText(htmlspecialchars("Terms & conditions of this procurement shall be in accordance with the provisions of the Revised IRR of R.A 9184, otherwise known as the Government Procurement Reform Act."), ['size' => 8]);

$textrun->addTextBreak(2);

$textrun->addText("\t\t\t\t\t\t\t\t\t\tVery truly yours,");
$textrun->addTextBreak(2);
$textrun->addText("\t\t\t\t\t\t\t\t\t\t".$unit->approving, ['size' => 11, 'bold' => true]);
$textrun->addTextBreak(1);
$textrun->addText("\t\t\t\t\t\t\t\t\t\t".$unit->approving_position.", ".$unit->acronym);
$textrun->addTextBreak(2);

$textrun->addText("Conforme:");
$textrun->addTextBreak(2);
$textrun->addText("_______________________");
$textrun->addTextBreak(1);
$textrun->addText("Signature over Printed Name", ['size' => 9]);
$textrun->addTextBreak(1);
$textrun->addText("Date: _________________");
$textrun->addTextBreak(1);


$table->addRow(300);
$cell = $table->addCell(null, ['vMerge' => 'restart', 'gridSpan' => 2]);
$cell->addText("Funds Available:");
$cell->addTextBreak(1);
$cell->addText("", ['bold' => true], $c);
$cell->addText("Designated Budget Officer", null, $c);

$table->addCell(null, ['valign' => 'bottom'])
	->addText("BURS No:");
$cell = $table->addCell(null, ['gridSpan' => 3]);
$cell->addText("Supply Office Receipt");
$cell->addText("by");

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue', 'gridSpan' => 2]);
$table->addCell()
	->addText("Amount:");
$table->addCell(null, ['gridSpan' => 3])
	->addText("Date: \t\t\t\t Time:");

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('C:/Users/Denver/Desktop/Abstract.docx');
$objWriter->save("php://output");
?>