<?php
require_once "../../core/init.php";
require_once "../../vendor/autoload.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$user = new User();

// $REQUEST = Session::flash('Request');
$data = $user->pr_data("PR2018-64IE0H");

die($data);

$REQUEST = "PR";

$UNIT = htmlspecialchars("General Administration, Support and Services");
$OFFICE = htmlspecialchars("Procurement Management Office");

if($REQUEST === 'PR')
{
	$items[0] = [
		'qty' => 15,
		'unit' => 'pcs',
		'desc' => '3/4" Plastic Molding',
		'eUC' => 85.00,
		'UC' => 1275.00
	];
	
	$items[1] = [
		'qty' => 2,
		'unit' => 'box',
		'desc' => '3.5mm2 THHN stranded wire',
		'eUC' => 3985.00,
		'UC' => 7970.00
	];
}
elseif($REQUEST === 'JO')
{
	$order[0] = [
		'unit' => 'lot',
		'desc' => 'SOMETHING THAT INVOLVES EATING ALOT OF FOOD AND DOING NOTHING',
		'EC' => 15000
	];

	$order[1] = [
		'unit' => 'lot',
		'desc' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim voluptatum, eos velit ex neque, atque porro modi, nulla est veritatis quia similique minus adipisci? Veniam repudiandae repellendus aspernatur omnis eveniet.
				Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem temporibus laborum voluptatibus sequi aspernatur iste nobis, amet quas eos repellat. htmlspecialchars htmlspecialcharshtmlspecialcharshtmlspecialcharshtmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars htmlspecialchars',
		'EC' => 60000
	];
}
$phpWord->setDefaultParagraphStyle(['lineHeight' => 1, 'space' => ['before' => 72, 'after' => 72]]);
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(10);

$section = $phpWord->addSection([
	'marginTop' => 720,
	'marginBottom' => 720,
	'marginLeft' => 720,
	'marginRight' => 720,
	'headerHeight' => 360,
	'footerHeight' => 0
]);

$header = $section->addHeader();
$hPragr =  ['alignment' => 'center'];
$header->addText("Republic of the Philippines", ['name' => 'Arial', 'size' => 10], $hPragr);
$header->addText("BICOL UNIVERSITY", ['name' => 'Arial', 'size' => 10, 'bold' => true], $hPragr);
$header->addText($UNIT, ['name' => 'Arial', 'size' => 9], $hPragr);
$header->addText($OFFICE, ['name' => 'Arial', 'size' => 9], $hPragr);

$section->addTextBreak(1);
if($REQUEST === 'PR') $section->addText("Purchase Request", ['size' => 12, 'bold' => true], ['alignment' => 'center']);
elseif($REQUEST === 'JO') $section->addText("Job Order", ['size' => 12, 'bold' => true], ['alignment' => 'center']);
$section->addText("Title:", null, $hPragr);
$section->addText("Provide catering services during the College Foundation Day on July 10, 2018 at BU College of Nursing", null, $hPragr);
$section->addTextBreak(1);

$section->addText("Date: " . date('F j, o'), null, ['indentation' => ['left' => 288, 'right' => 0]]);

$thStyle = ['bold' => true, 'size' => 9];
$thPragr = ['alignment' => 'center'];

$trStyle = ['size' => 9];

$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
if($REQUEST === 'PR')
{
	$table->addRow(43.2);
	$table->addCell(1440)->addText("Quantity", $thStyle, $thPragr);
	$table->addCell(1440)->addText("Unit of Issue", $thStyle, $thPragr);
	$table->addCell(4320)->addText("Item Description", $thStyle, $thPragr);
	$table->addCell(1800)->addText("Estimated Unit Cost", $thStyle, $thPragr);
	$table->addCell(1800)->addText("Estimated Cost", $thStyle, $thPragr);

	for($r = 0; $r < count($items); $r++)
	{
		$table->addRow(43.2);
		$table->addCell(1440)->addText($items[$r]['qty'], $trStyle, $thPragr);
		$table->addCell(1440)->addText($items[$r]['unit'], $trStyle, $thPragr);
		$table->addCell(4320)->addText($items[$r]['desc'], $trStyle, $thPragr);
		$table->addCell(1800)->addText("&#8369; ".$items[$r]['eUC'], $trStyle, $thPragr);
		$table->addCell(1800)->addText("&#8369; ".$items[$r]['UC'], $trStyle, $thPragr);
	}
}
elseif($REQUEST === 'JO')
{
	$table->addRow(43.2);
	$table->addCell(1440)->addText("Unit", $thStyle, $thPragr);
	$table->addCell(1440)->addText("Particulars", $thStyle, $thPragr);
	$table->addCell(1800)->addText("Estimated Cost", $thStyle, $thPragr);

	for($r = 0; $r < count($order); $r++)
	{
		$table->addRow(43.2);
		$table->addCell(1440)->addText($order[$r]['unit'], $trStyle, $thPragr);
		$table->addCell(7970)->addText($order[$r]['desc'], $trStyle, $thPragr);
		$table->addCell(1440)->addText("&#8369; ".$order[$r]['EC'], $trStyle, $thPragr);
	}
}
// echo "<pre>" . print_r($items) . "</pre>";

// \PhpOffice\PhpWord\Settings::loadConfig();
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
// $objWriter->save('C:/Users/Denver/Desktop/JO.pdf');

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('C:/Users/Denver/Desktop/JO.docx');

?>