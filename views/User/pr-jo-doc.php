<?php
require_once "../../core/init.php";
require_once "../../vendor/autoload.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$user = new User();

$REQUEST = explode(":", Session::flash('Request'));
$ProjectData = $user->PRdoc_projData($REQUEST[1]);
$UserData = $user->user_data(Session::get(Config::get('session/session_name')));
$NumLots = $user->PR_num_lots($REQUEST[1]);

$file = $REQUEST[1].".docx";
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="'.$file.'"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

$REQUEST = "PR";

$OFFICE = htmlspecialchars($UserData->office_name);

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
$header->addText($OFFICE, ['name' => 'Arial', 'size' => 9], $hPragr);
$section->addTextBreak(1);
if($REQUEST === 'PR') $section->addText("Purchase Request", ['size' => 12, 'bold' => true], ['alignment' => 'center']);
elseif($REQUEST === 'JO') $section->addText("Job Order", ['size' => 12, 'bold' => true], ['alignment' => 'center']);
$section->addText("Title:", null, $hPragr);
$section->addText($ProjectData->title, null, $hPragr);
$section->addTextBreak(1);
$section->addText("Date: Requested " . date('F j, o', strtotime($ProjectData->date_created)), null, ['indentation' => ['left' => 288, 'right' => 0]]);

$thStyle = ['bold' => true, 'size' => 9];
$thPragr = ['alignment' => 'center'];
$trStyle = ['size' => 9];

if($REQUEST === 'PR')
{
	for($x = 1 ; $x <= $NumLots->lots ; $x++)
	{
		$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
		$table->addRow(43.2);
		$table->addCell(10800, ['gridSpan' => 6])->addText("Lot ".$x, $thStyle, $thPragr);
		$table->addRow(43.2);
		$table->addCell(1152)->addText("Stock No.", $thStyle, $thPragr);
		$table->addCell(864)->addText("Unit of Issue", $thStyle, $thPragr);
		$table->addCell(4320)->addText("Item Description", $thStyle, $thPragr);
		$table->addCell(864)->addText("Quantity", $thStyle, $thPragr);
		$table->addCell(1800)->addText("Estimated Unit Cost", $thStyle, $thPragr);
		$table->addCell(1800)->addText("Estimated Cost", $thStyle, $thPragr);

		$LOT_ITEMS = $user->PR_itemsPerLot($NumLots->form_ref_no, $x);
		foreach($LOT_ITEMS as $ITEM)
		{
			$table->addRow(43.2);
			$table->addCell(1152)->addText(htmlspecialchars($ITEM->stock_no), $trStyle, $thPragr);
			$table->addCell(864)->addText(htmlspecialchars($ITEM->unit), $trStyle, $thPragr);
			$table->addCell(4320)->addText(htmlspecialchars($ITEM->item_description), $trStyle, $thPragr);
			$table->addCell(864)->addText(htmlspecialchars($ITEM->quantity), $trStyle, $thPragr);
			$table->addCell(1800)->addText("&#8369; ".htmlspecialchars($ITEM->unit_cost), $trStyle, $thPragr);
			$table->addCell(1800)->addText("&#8369; ".htmlspecialchars($ITEM->total_cost), $trStyle, $thPragr);
		}
		$section->addTextBreak(2);
	}
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

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");

?>