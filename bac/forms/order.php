<?php 

require_once '../../core/init.php';

$admin = new Admin();

$gds = base64_decode($_GET['q']);
$lot = $_GET['l'];
$title = base64_decode($_GET['t']);

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$file = $gds." -  Abstract of Bids - ".$title.".docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->setDefaultFontName('Arial Narrow');
$phpWord->setDefaultFontSize(11);

$section = $phpWord->addSection([
	'orientation' => 'landscape',
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




$project = $admin->get('projects', array('project_ref_no', '=', $gds));
$canvass = $admin->selectCanvassForm($gds, $title, $lot);
$suppliers = $admin->abstractSuppliers($gds, $lot);




$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('C:/Users/Denver/Desktop/Abstract.docx');
$objWriter->save("php://output");
?>