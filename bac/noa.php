<?php
require_once "../vendor/autoload.php";
$phpWord = new \PhpOffice\PhpWord\PhpWord();


$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 72, 'after' => 72]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(12);

$section = $phpWord->addSection([
	'marginTop' => 1281.6,
	'marginBottom' => 849.6,
	'marginLeft' => 1411.2,
	'marginRight' => 1468.8,
	'headerHeight' => 288,
	'footerHeight' => 446.4
]);

$header = $section->addHeader();


$section->addText(date("F j, Y"));

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('C:/Users/Denver/Desktop/BAC Forms/noa.docx');

?>