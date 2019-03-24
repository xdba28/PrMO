<?php
require_once '../../core/init.php';
$admin = new Admin();
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$date = explode("/", $_GET['m']);
$projects = $admin->monthlyReport($date[0]);
$currentMonth = date("F", mktime(0, 0, 0, $date[0], 10));

$file = $currentMonth." Procurement Report.docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(11);

$pagestyle = [
	'orientation' => 'landscape',
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 360,
	'marginRight' => 360,
	'headerHeight' => 360,
	'footerHeight' => 0,
	'pageSizeH' => 12240,
	'pageSizeW' => 18720
];

$section = $phpWord->addSection($pagestyle);
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

$section->addText($currentMonth." Procurement Report", ['size' => 14, 'bold' => true], $c);
$section->addTextBreak(1);

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow();
$table->addCell(null, $cc)->addText("#", null, $c);
$table->addCell(null, $cc)->addText("Project Name", null, $c);
$table->addCell(null, $cc)->addText("Reference #", null, $c);
$table->addCell(null, $cc)->addText("Implementing Office/End-User", null, $c);
$table->addCell(null, $cc)->addText("Category", null, $c);
$table->addCell(null, $cc)->addText("ABC", null, $c);
$table->addCell(null, $cc)->addText("Project Registration Date", null, $c);
$table->addCell(null, $cc)->addText("Contract Period Requirement", null, $c);
$table->addCell(null, $cc)->addText("Mode of Procurement", null, $c);



// $projects = $admin->selectAll('projects');
// $projects = $admin->getAll('projects', array('accomplished', '=', '10'));
foreach($projects as $key => $project){
	$count = $key + 1;
	$table->addRow();
	$table->addCell(null, $cc)->addText($count, null, $c);
	$table->addCell(null, $cc)->addText($project->project_title, null, $c);
	$table->addCell(null, $cc)->addText($project->project_ref_no, null, $c);

	foreach(json_decode($project->end_user, true) as $end_user){
		$end_users[] = $admin->get('enduser', array('edr_id', '=', $end_user)); 
	}

	$reference_form = json_decode($project->request_origin, true);
	$form = $admin->get('project_request_forms', array('form_ref_no', '=', $reference_form[0]));

	$table->addCell(null, $cc)->addText($end_users[0]->current_specific_office, null, $c);

	if($form->type === "PR"){
		$table->addCell(null, $cc)->addText("Goods", null, $c);
	}else{
		$table->addCell(null, $cc)->addText("Services", null, $c);		
	}

	$table->addCell(null, $cc)->addText(Date::translate($project->ABC, 'php'), null, $c);
	$table->addCell(null, $cc)->addText(Date::translate($project->date_registered, '2'), null, $c);
	$table->addCell(null, $cc)->addText(Date::translate($project->implementation_date, '2'), null, $c);

	switch($project->MOP){
		case "SVP":
			$mop_name = "Small Value Procurement";
			break;
		case "PB":
			$mop_name = "Public Bidding";
			break;
		case "DC":
			$mop_name = "Direct Contracting";
			break;
		case "TBE":
			$mop_name = "N/A";
			break;
		default:
			$mop_name = $project->MOP;
	}
	
	$table->addCell(null, $cc)->addText($mop_name, null, $c);
}



$section->addPageBreak();
$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow();
$table->addCell(null, $cc)->addText("#", null, $c);
$table->addCell(null, $cc)->addText("Current Workflow");
$table->addCell(null, $cc)->addText("Percent Accomplished", null, $c);
$table->addCell(null, $cc)->addText("PhilGEPS Soliictation No.", null, $c);
$table->addCell(null, $cc)->addText("PhilGEPS Bid Notic Ref. No", null, $c);
$table->addCell(null, $cc)->addText("Total Number of Bidders", null, $c);
$table->addCell(null, $cc)->addText("Winning Bidder", null, $c);
$table->addCell(null, $cc)->addText("Abstract Date", null, $c);
$table->addCell(null, $cc)->addText("BAC Resolution", null, $c);
$table->addCell(null, $cc)->addText("Notice of Award", null, $c);
$table->addCell(null, $cc)->addText("Finish Date", null, $c);

foreach($projects as $key => $project){
	$count = $key + 1;

	$project_suppliers = '';
	$awarded_suppliers = '';
	$abstract_date = '';
	$bac_reso_date = '';
	$noa_date = '';
	$finish_date = '';

	$lots = $admin->getAll('canvass_forms', array('gds_reference', '=', $project->project_ref_no));
	foreach($lots as $lot){

		$suppliers = $admin->abstractSuppliers($project->project_ref_no, $lot->id);

		foreach($suppliers as $supplier){
			$project_suppliers .= $supplier->name." ";
		}

		$suppliersAward = $admin->findAwardedSuppliers($project->project_ref_no, $lot->id);
		foreach($suppliersAward as $award){
			$awarded_suppliers .= $award->name." ";
		}
	}

	$logs = $admin->getAll('project_logs', array('referencing_to', '=', $project->project_ref_no));
	foreach($logs as $log){
		$ex_detail = explode("^", $log->remarks);
		$identifier = substr($log->remarks, 0, 5);

		if($identifier === 'AWARD' && $ex_detail[1] === 'Abstract') $abstract_date = Date::translate($log->logdate, '2');
		if($identifier === 'AWARD' && $ex_detail[1] === 'BAC Resolution') $bac_reso_date = Date::translate($log->logdate, '2');
		if($identifier === 'AWARD' && $ex_detail[1] === 'Notice of Award') $noa_date = Date::translate($log->logdate, '2');
		if($identifier === 'DECLA' && $ex_detail[1] === 'FINISH') $finish_date = Date::translate($log->logdate, '2');
		
	}


	if($project_suppliers === '') $project_suppliers = 'N/A';
	if($awarded_suppliers === '') $awarded_suppliers = 'N/A';
	if($abstract_date === '') $abstract_date = 'N/A';
	if($bac_reso_date === '') $bac_reso_date = 'N/A';
	if($noa_date === '') $noa_date = 'N/A';
	if($finish_date === '') $finish_date = 'N/A';

	$accomplishment = number_format(($project->accomplished / $project->steps) * 100, 1);

	$table->addRow();
	$table->addCell(null, $cc)->addText($count, null, $c);
	$table->addCell(null, $cc)->addText($project->workflow, null, $c);
	$table->addCell(null, $cc)->addText($accomplishment." %", null, $c);
	$table->addCell(null, $cc)->addText("N/A", null, $c);
	$table->addCell(null, $cc)->addText("N/A", null, $c);
	$table->addCell(null, $cc)->addText($project_suppliers, null, $c);
	$table->addCell(null, $cc)->addText($awarded_suppliers, null, $c);
	$table->addCell(null, $cc)->addText($abstract_date, null, $c);
	$table->addCell(null, $cc)->addText($bac_reso_date, null, $c);
	$table->addCell(null, $cc)->addText($noa_date, null, $c);
	$table->addCell(null, $cc)->addText($finish_date, null, $c);
}





$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");
?>