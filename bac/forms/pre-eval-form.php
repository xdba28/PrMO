<?php
require_once "../../core/init.php";
require_once "../../vendor/autoload.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$admin = new Admin();

if($admin->isLoggedIn());
else{
	Redirect::To('../../index');
	die();
}



$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 0, 'after' => 0]]);
$phpWord->addNumberingStyle("mult", [
	'type'   => 'multilevel',
    'levels' => [
		['format' => 'lowerLetter', 'text' => '%1.', 'left' => 993.6, 'hanging' => 0, 'tabPos' => 720, 'size' => 11]
	]
]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(8);

$section = $phpWord->addSection($Setting[$Docs]);
$header = $section->addHeader();
$header->addImage('../Office logo.jpg', [
	'width' => 57.6,
	'height' => 55.44,	
	'wrappingStyle' => 'square',
    'positioning' => 'absolute',
    'posHorizontal'    => 'inside',
    'posHorizontalRel' => 'margin',
	'posVerticalRel' => 'line'
]);

$hPragr =  ['indentation' => ['left' => 1296, 'right' => 0]];
$header->addText("Republic of the Philippines", ['name' => 'Arial', 'size' => 10], $hPragr);
$header->addText("BICOL UNIVERSITY", ['name' => 'Arial', 'size' => 11, 'bold' => true], $hPragr);
$header->addText("Legazpi City", ['name' => 'Arial', 'size' => 10, 'bold' => true], $hPragr);
$section->addTextBreak(2);


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
ob_clean();
$objWriter->save('C:/Users/Denver/Desktop/EVAL.docx');
// $objWriter->save("php://output");

?>