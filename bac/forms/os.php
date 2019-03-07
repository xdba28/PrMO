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


$phpWord = new \PhpOffice\PhpWord\PhpWord();

$file = $gds." - Obligation Slip of".$supplier->name.".docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Times New Roman');
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
$table = $header->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow();
$cellImage = $table->addCell(1300, ['vMerge' => 'restart']);
$cellImage->addImage('../../assets/pics/Office logo.jpg', ['width' => 57.6,'height' => 55.44,'wrappingStyle' => 'square','positioning' => 'absolute','posHorizontal' => 'inside', 'posHorizontalRel' => 'margin', 'posVerticalRel' => 'line']);
$table->addCell(3500);$table->addCell(2000);$table->addCell(2000);

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("Republic of the Philippines", ['size' => 10, 'name' => 'Arial'], ['alignment' => 'left']);
$table->addCell()->addText("BU BAC Form No. 008", ['size' => 8]);$table->addCell();

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("BICOL UNIVERSITY", ['size' => 11, 'name' => 'Arial'], ['alignment' => 'left']);
$table->addCell()->addText("REFERENCE NO.");
$table->addCell()->addText($gds, ['bold' => true, 'underline' => 'single']);

$table->addRow();
$table->addCell(null, ['vMerge' => 'continue']);
$table->addCell()->addText("Legazpi City", ['size' => 10, 'name' => 'Arial'], ['alignment' => 'left']);
$table->addCell();$table->addCell();


$r = ['alignment' => 'right'];
$c = ['alignment' => 'center'];
$cc = ['valign' => 'center'];
$gs2 = ['gridSpan' => 2];
$fb = ['bold' => true];
$cStyle = ['indentation' => ['left' => 200, 'right' => 300]];
$vm = ['vMerge' => 'continue'];
$i = ['italic' => true];
$u = ['underline' => 'single'];

$section->addTextBreak(1);
$section->addText('REQUEST FOR ISSUANCE OF OBLIGATION SLIP', $fb, $c);
$section->addTextBreak(1);

$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 50, 'cellMarginTop'  => 120]);
$table->addRow();
$table->addCell(2000)->addTextBox([
	'alignment' => 'right',
	'width' => 60,
	'height' => 20,
	'borderSize' => 1,
	'borderColor' => '#000000'
])->addText();
$cell = $table->addCell(5000);
$cell->addText(".", ['size' => 3]);
$cell->addText("Infrastructure");
$cell = $table->addCell(6000, ['vMerge' => 'restart']);
$textbox = $cell->addTextBox([
	'alignment' => 'center',
	'width' => 280,
	'height' => 125,
	'borderSize' => 1,
	'borderColor' => '#000000'
]);
$textbox->addText("Forwarded to Budget Office with the following attachments:", $i);
$textrun = $textbox->addTextRun();
$textrun->addText("    &#10003;    ", $u);
$textrun->addText("Notice of Award");
$textrun = $textbox->addTextRun();
$textrun->addText("    &#10003;    ", $u);
$textrun->addText(htmlspecialchars("JO/PR (Goods & Cons)/AAE(Infra)"));
$textrun = $textbox->addTextRun();
$textrun->addText("    &#10003;    ", $u);
$textrun->addText("Cert. of Availability of Fund");
$textrun = $textbox->addTextRun();
$textrun->addText("    &#10003;    ", $u);
$textrun->addText("Bid Proposals");

$prnl = $admin->get('personnel', array('prnl_id', '=', Session::get(Config::get('session/session_name'))));

if($prnl->prnl_ext_name !== "XXXXX"){
	$fullname = $prnl->prnl_fname." ".$prnl->prnl_mname." ".$prnl->prnl_lname." ".$prnl->prnl_ext_name;
}else{
	$fullname = $prnl->prnl_fname." ".$prnl->prnl_mname." ".$prnl->prnl_lname;
}

$textrun = $textbox->addTextRun();
$textrun->addText("           Prepared by: ");
$textrun->addText($fullname, $u);
$textrun->addText(" Date: ".Date::translate(Date::translate('test', 'now'), '2'));



$table->addRow();$table->addCell();$table->addCell();$table->addCell(null, $vm);

$table->addRow();
$table->addCell()->addTextBox([
	'alignment' => 'right',
	'width' => 60,
	'height' => 20,
	'borderSize' => 1,
	'borderColor' => '#000000'
])->addText("X", ['size' => 9], $c);
$cell = $table->addCell();
$cell->addText(".", ['size' => 3]);
$cell->addText("Supplies/Services");
$table->addCell(null, $vm);

$table->addRow();$table->addCell();$table->addCell();$table->addCell(null, $vm);

$table->addRow();
$table->addCell()->addTextBox([
	'alignment' => 'right',
	'width' => 60,
	'height' => 20,
	'borderSize' => 1,
	'borderColor' => '#000000'
])->addText();
$cell = $table->addCell();
$cell->addText(".", ['size' => 3]);
$cell->addText("Consulting");
$table->addCell(null, $vm);

$table->addRow();$table->addCell();$table->addCell();$table->addCell(null, $vm);


$section->addTextBreak(1);
$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
$table->addRow(43.2);
$table->addCell(3000)->addText("Project", null, $c);
$table->addCell(2000)->addText("ABC/CAF/Source", null, $c);
$table->addCell(2000)->addText("Contract Cost", null, $c);
$table->addCell(2000)->addText("Supplier", null, $c);
$table->addCell(2000)->addText("Address", null, $c);

$table->addRow(1200);
$table->addCell(3000, $cc)->addText($project->project_title, null, $c);
$table->addCell(2000, $cc)->addText(Date::translate($project->ABC, 'php'), null, $c);
$table->addCell(2000, $cc)->addText(Date::translate($canvass->cost, 'php'), null, $c);
$table->addCell(2000, $cc)->addText($supplier->name, null, $c);
$table->addCell(2000, $cc)->addText($supplier->address, null, $c);

$section->addTextBreak(1);

$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginRight' => 115.2]);
$table->addRow();
$cell = $table->addCell(6000);
$textbox = $cell->addTextBox([
	'alignment' => 'left',
	'width' => 180,
	'height' => 50,
	'borderSize' => 1,
	'borderColor' => '#000000'
]);
$textbox->addText("Returned from Budget Office:", $i);
$textbox->addText("Transmitted by: ________________");
$textbox->addText("Date:____________ Time:________");

$cell = $table->addCell(5000);
$cell->addText("Received by:______________________");
$cell->addText("Date:______________________");
$cell->addText("Time:______________________");


$section->addText();
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");
?>