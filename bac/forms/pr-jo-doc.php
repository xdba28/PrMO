<?php
require_once "../../vendor/autoload.php";
require_once "../../core/init.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$user = new User();

if($user->isLoggedIn());
else{
	Redirect::To('../../index');
	die();
}

$REQ = Session::flash('Request');
$REQUEST = explode(":", $REQ);
// $REQ = "JO2018-D9DFGF:JO";
// $REQUEST = explode(":", "JO2018-D9DFGF:JO");
$ProjectData = $user->Doc_projData($REQ);
$UserData = $user->user_data(Session::get(Config::get('session/session_name')));
$NumLots = $user->PRJO_num_lots($REQ);

$file = $REQUEST[0].".docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

$OFFICE = htmlspecialchars(htmlspecialchars_decode($UserData->office_name, ENT_QUOTES));

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
$header->firstPage();
$hPragr =  ['alignment' => 'center'];
$header->addText("Republic of the Philippines", ['name' => 'Arial', 'size' => 10], $hPragr);
$header->addText("BICOL UNIVERSITY", ['name' => 'Arial', 'size' => 10, 'bold' => true], $hPragr);
$header->addText($OFFICE, ['name' => 'Arial', 'size' => 9], $hPragr);
$section->addTextBreak(1);
if($REQUEST[1] === 'PR') $section->addText("Purchase Request", ['size' => 12, 'bold' => true], ['alignment' => 'center']);
elseif($REQUEST[1] === 'JO') $section->addText("Job Order", ['size' => 12, 'bold' => true], ['alignment' => 'center']);
$section->addText("Title:", ['size' => 11], $hPragr);
$section->addText($ProjectData->title, ['size' => 11], $hPragr);
$section->addTextBreak(1);

$textrun = $section->addTextRun(['indentation' => ['left' => 288, 'right' => 0]]);
$textrun->addText("Reference No. ");
$textrun->addText($REQUEST[0], ['bold' => true, 'underline' => 'single']);

$section->addText("Date Created: " . date('F j, o', strtotime($ProjectData->date_created)), null, ['indentation' => ['left' => 288, 'right' => 0]]);

$thStyle = ['bold' => true, 'size' => 10];
$thPragr = ['alignment' => 'center'];
$trStyle = ['size' => 10];

if($REQUEST[1] === 'PR')
{
	if($NumLots->lot_no == '101' && $NumLots->lot_title == 'static lot')
	{
		$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
		$table->addRow(43.2);
		$table->addCell(1152)->addText("Stock No.", $thStyle, $thPragr);
		$table->addCell(864)->addText("Unit of Issue", $thStyle, $thPragr);
		$table->addCell(4320)->addText("Item Description", $thStyle, $thPragr);
		$table->addCell(864)->addText("Quantity", $thStyle, $thPragr);
		$table->addCell(1800)->addText("Estimated Unit Cost", $thStyle, $thPragr);
		$table->addCell(1800)->addText("Estimated Cost", $thStyle, $thPragr);

		$LOT_ITEMS = $user->PRJO_itemsPerLot($NumLots->form_ref_no, $NumLots->lot_no);
		foreach($LOT_ITEMS as $ITEM)
		{
			$table->addRow(43.2);
			$table->addCell(1152)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->stock_no, ENT_QUOTES)), $trStyle, $thPragr);
			$table->addCell(864)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->unit, ENT_QUOTES)), $trStyle, $thPragr);
			$table->addCell(4320)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->item_description, ENT_QUOTES)), $trStyle, $thPragr);
			$table->addCell(864)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->quantity, ENT_QUOTES)), $trStyle, $thPragr);
			$table->addCell(1800)->addText("&#8369; ".htmlspecialchars(htmlspecialchars_decode($ITEM->unit_cost, ENT_QUOTES)), $trStyle, $thPragr);
			$table->addCell(1800)->addText("&#8369; ".htmlspecialchars(htmlspecialchars_decode($ITEM->total_cost, ENT_QUOTES)), $trStyle, $thPragr);
		}
		$section->addTextBreak(2);
	}
	else
	{
		foreach($NumLots as $lot)
		{
			$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
			$table->addRow(43.2);
			$table->addCell(10800, ['gridSpan' => 6])->addText("Lot ".$lot->lot_no.": ".$lot->lot_title, ['bold' => true, 'size' => 10], $thPragr);
			$table->addRow(43.2);
			$table->addCell(1152)->addText("Stock No.", $thStyle, $thPragr);
			$table->addCell(864)->addText("Unit of Issue", $thStyle, $thPragr);
			$table->addCell(4320)->addText("Item Description", $thStyle, $thPragr);
			$table->addCell(864)->addText("Quantity", $thStyle, $thPragr);
			$table->addCell(1800)->addText("Estimated Unit Cost", $thStyle, $thPragr);
			$table->addCell(1800)->addText("Estimated Cost", $thStyle, $thPragr);
	
			$LOT_ITEMS = $user->PRJO_itemsPerLot($lot->form_ref_no, $lot->lot_no, $REQUEST[1]);
			foreach($LOT_ITEMS as $ITEM)
			{
				$table->addRow(43.2);
				$table->addCell(1152)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->stock_no, ENT_QUOTES)), $trStyle, $thPragr);
				$table->addCell(864)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->unit, ENT_QUOTES)), $trStyle, $thPragr);
				$table->addCell(4320)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->item_description, ENT_QUOTES)), $trStyle, $thPragr);
				$table->addCell(864)->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->quantity, ENT_QUOTES)), $trStyle, $thPragr);
				$table->addCell(1800)->addText("&#8369; ".htmlspecialchars(htmlspecialchars_decode($ITEM->unit_cost, ENT_QUOTES)), $trStyle, $thPragr);
				$table->addCell(1800)->addText("&#8369; ".htmlspecialchars(htmlspecialchars_decode($ITEM->total_cost, ENT_QUOTES)), $trStyle, $thPragr);
			}
			$table->addRow(43.2);
			$table->addCell(10800, ['gridSpan' => 6])->addText("Total Lot Cost: &#8369; ".$lot->lot_cost, ['size' => 10, 'bold' => true], ['alignment' => 'right', 'indentation' => ['left' => 0, 'right' => 410]]);
			$section->addTextBreak(2);
		}
	}
}
elseif($REQUEST[1] === 'JO')
{
	$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
	$table->addRow(43.2);
	$table->addCell(1800)->addText("Lot No.", $thStyle, $thPragr);
	$table->addCell(7200)->addText("Lot Description", $thStyle, $thPragr);
	$table->addCell(1800)->addText("Estimated Cost", $thStyle, $thPragr);

	$cStyle = ['indentation' => ['left' => 200, 'right' => 300]];

	foreach($NumLots as $lot)
	{
		$table->addRow(43.2);
		$table->addCell(1800, ['valign' => 'center'])->addText(htmlspecialchars(htmlspecialchars_decode($lot->lot_no, ENT_QUOTES)), $trStyle, $thPragr);

		$tableCell = $table->addCell(7200);
		$tableCell->addTextBreak(1);
		$tableCell->addText(htmlspecialchars(htmlspecialchars_decode($lot->lot_title, ENT_QUOTES)), ['size' => 11], $cStyle);
		$tableCell->addTextBreak(1);

		$LOT_ITEMS = $user->PRJO_itemsPerLot($lot->form_ref_no, $lot->lot_no, $REQUEST[1]);
		
		foreach($LOT_ITEMS as $ITEM)
		{
			$textrun = $tableCell->addTextRun($cStyle);
			$textrun->addText(htmlspecialchars(htmlspecialchars_decode($ITEM->header, ENT_QUOTES) . " "), ['size' => 10, 'bold' => true]);
			
			$i = 0;
			$LIST_ITEMS = explode(",", htmlspecialchars(htmlspecialchars_decode($ITEM->tags, ENT_QUOTES)));
			$TAG_COUNT = count($LIST_ITEMS);
			
			foreach($LIST_ITEMS as $tag)
			{
				if(++$i === $TAG_COUNT) $textrun->addText(htmlspecialchars(htmlspecialchars_decode($tag, ENT_QUOTES)));
				else $textrun->addText(htmlspecialchars(htmlspecialchars_decode($tag.", ", ENT_QUOTES)));
			}
			$tableCell->addTextBreak(1);
		}
		if($lot->note !== "")
		{
			$tableCell->addText("Note:", $trStyle, $cStyle);
			$tableCell->addText(htmlspecialchars(htmlspecialchars_decode($lot->note, ENT_QUOTES)), $trStyle, ['indentation' => ['left' => 300, 'right' => 300]]);
			$tableCell->addTextBreak(1);
		}
		$table->addCell(1800, ['valign' => 'center'])->addText("&#8369; ".htmlspecialchars(htmlspecialchars_decode($lot->lot_cost, ENT_QUOTES)), $trStyle, $thPragr);
	}
	$section->addTextBreak(2);
}

$section->addTextBreak(1);

$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
$table->addRow(43.2);
$table->addCell(3600)->addText($UserData->edr_fname." ".$UserData->edr_mname." ".$UserData->edr_lname, ['size' => 11, 'bold' => true], $thPragr);
$table->addCell(3600);
$table->addCell(3600)->addText($ProjectData->noted_by, ['size' => 11, 'bold' => true], $thPragr);

$table->addRow(43.2);
$table->addCell(3600)->addText("Requested By", ['size' => 10], $thPragr);
$table->addCell(3600);
$table->addCell(3600)->addText("Noted By", ['size' => 10], $thPragr);

$section->addTextBreak(3);

$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
$table->addRow(43.2);
$table->addCell(3600)->addText($ProjectData->verified_by, ['size' => 11, 'bold' => true], $thPragr);
$table->addCell(3600);
$table->addCell(3600)->addText($ProjectData->approved_by, ['size' => 11, 'bold' => true], $thPragr);

$table->addRow(43.2);
$table->addCell(3600)->addText("Verified By", ['size' => 10], $thPragr);
$table->addCell(3600);
$table->addCell(3600)->addText("Approved By", ['size' => 10], $thPragr);


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
ob_clean();
// $objWriter->save('C:/Users/Denver/Desktop/PR-JO.docx');
$objWriter->save("php://output");
// Redirect::To('../../views/User/my-forms');
?>