<?php
require_once "../../core/init.php";

$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::loadConfig();

$user = new User();

$file = "Project request form - ".$_GET['id'].".docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="'.$file.'"');

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


// Styles
$cPragr =  ['alignment' => 'center'];
$bText = ['bold' => true];
$tsAlignCenter = ['valign' => 'center'];
$cStyle = ['indentation' => ['left' => 200, 'right' => 300]];


if(Session::exists("Request")){
	$project_ex = explode(":", Session::flash('Request'));
	$project = $user->requestDetails($project_ex[0]);
}else if(!empty($_GET)){
	$project = $user->requestDetails($_GET['id']);
}else{
	Redirect::To('../../index');
	exit("Exit");
}



if($project['end_user']['edr_ext_name'] !== "XXXXX"){
	$userFullName = $project['end_user']['edr_fname']." ".$project['end_user']['edr_mname']." ".$project['end_user']['edr_lname']." ".$project['end_user']['edr_ext_name'];
}else{
	$userFullName = $project['end_user']['edr_fname']." ".$project['end_user']['edr_mname']." ".$project['end_user']['edr_lname'];
}


if($project['type'] === "PR"){


	$header = $section->addHeader();
	$header->firstPage();
	$header->addText("PURCHASE REQUEST", ['bold' => true, 'size' => 12], $cPragr);
	$header->addText("BICOL UNIVERSITY", ['bold' => true, 'size' => 12], $cPragr);
	$header->addText("Legazpi City", ['bold' => true, 'size' => 12], $cPragr);

	$section->addTextBreak(1);

	$section->addText("Title:", ['size' => 12, 'bold' => true], $cPragr);
	$section->addText($project['title'], ['size' => 11], $cPragr);

	$section->addTextBreak(1);
	
	
	$table = $section->addTable(['borderColor' => '#000000',
		'borderSize' => 6,
		'alignment' => 'center',
		'cellMarginLeft'  => 115.2,
		'cellMarginRight'  => 115.2
	]);
	
	$table->addRow(43.2)
		->addCell(7000, ['gridSpan' => 3])->addText("College/Unit: ".$project['end_user']['office_name'], $bText);
	$table->addCell(2304, ['gridSpan' => 2])->addText("PR.No. ".$project['refno'], $bText);
	$table->addCell(2304)->addText("Date: ".date('F j, o', strtotime($project['date'])), $bText);

	$table->addRow(43.2)
		->addCell(7000, ['gridSpan' => 3])->addText("Office: ".$project['end_user']['current_specific_office'], $bText);
	$table->addCell(2304, ['gridSpan' => 2])->addText("SAI.No.", $bText);
	$table->addCell(2304)->addText("Date:", $bText);

	$table->addRow(43.2)
		->addCell(7000, ['gridSpan' => 3]);
	$table->addCell(2304, ['gridSpan' => 2])->addText("ALOBS No.", $bText);
	$table->addCell(2304)->addText("Date:", $bText);

	$table->addRow(43.2)
		->addCell(7000, ['gridSpan' => 3]);
	$table->addCell(4608, ['gridSpan' => 3])->addText("Issuance", $bText, $cPragr);


	$table->addRow(619.2)
		->addCell(1497.6, $tsAlignCenter)->addText("QTY", $bText, $cPragr);
	$table->addCell(993.6, $tsAlignCenter)->addText("Unit of Issue", $bText, $cPragr);
	$table->addCell(3844.8, $tsAlignCenter)->addText("Description", $bText, $cPragr);
	$table->addCell(1281.6, $tsAlignCenter)->addText("Stock No.", $bText, $cPragr);
	$table->addCell(1353.6, $tsAlignCenter)->addText("Estimate Unit Cost", $bText, $cPragr);
	$table->addCell(1612.8, $tsAlignCenter)->addText("Estimate Cost", $bText, $cPragr);

	$total = 0;

	foreach($project['lots'] as $lot){

		$table->addRow(43.2)
			->addCell(null, ['gridSpan' => 6])->addText($lot['l_title'], null, $cPragr);

		foreach($lot['lot_items'] as $item){

			$table->addRow(43.2)
				->addCell()->addText($item['qty'], null, $cPragr);
			$table->addCell()->addText($item['unit'], null, $cPragr);
			$table->addCell()->addText($item['desc'], null, $cPragr);
			$table->addCell()->addText($item['stock_no'], null, $cPragr);
			$table->addCell()->addText(Date::translate($item['uCost'], 'php'), null, $cPragr);
			$table->addCell()->addText(Date::translate($item['tCost'], 'php'), null, $cPragr);
			$total += $item['tCost'];
		}
		
	}


	$table->addRow(43.2)
		->addCell()->addText("Purpose: ");
	$table->addCell(null, ['gridSpan' => 3])->addText($project['purpose']);
	$table->addCell()->addText("Total", ['bold' => true], $cPragr);
	$table->addCell()->addText(Date::translate($total, 'php'), ['bold' => true], ['alignment' => 'center']);

	$table->addRow(500)
		->addCell(null, ['gridSpan' => 3, 'valign' => 'bottom'])
		->addText(
			"Requested by: ",
			['size' => 11, 'bold' => true],
			$cPragr
		);
	$table->addCell(null, ['gridSpan' => 3, 'valign' => 'bottom'])
		->addText(
			"Approved by: ",
			['size' => 11, 'bold' => true]
		);


	$table->addRow(400)
		->addCell(null, $tsAlignCenter)->addText("Signature: ", ['size' => 10]);
	$table->addCell(null, ['gridSpan' => 2, 'valign' => 'center']);
	$table->addCell(null, ['gridSpan' => 3, 'valign' => 'center']);

	$table->addRow(400)
		->addCell(null, $tsAlignCenter)->addText("Printed Name: ", ['size' => 10]);
	$table->addCell(null, ['gridSpan' => 2, 'valign' => 'center'])
		->addText(
			$userFullName,
			['bold' => true],
			$cPragr
		);
	$table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])
		->addText(
			$project['end_user']['signatories']['approving'],
			['bold' => true],
			$cPragr
		);

	$table->addRow(400)
		->addCell(null, $tsAlignCenter)
		->addText("Designation", ['size' => 10]);
	$table->addCell(null, ['gridSpan' => 2, 'valign' => 'center'])
		->addText(
			$project['end_user']['edr_job_title'],
			null,
			$cPragr
		);
	$table->addCell(null, ['gridSpan' => 3, 'valign' => 'center'])
		->addText(
			$project['end_user']['signatories']['approving_position'],
			null,
			$cPragr
		);

	$table->addRow(43.2)
		->addCell()
		->addText("BU-F-USO-06", ['size' => 8], $cPragr);
	$table->addCell(null, ['gridSpan' => 2])
		->addText("Rev.No.0", ['size' => 8], ['alignment' => 'right']);
	$table->addCell();
	$table->addCell(null, ['gridSpan' => 2])
		->addText("Effective: January 3, 2011", ['size' => 8], $cPragr);



}else{

	$header = $section->addHeader();
	$header->firstPage();
	$header->addText("Republic of the Philippines", ['size' => 12, 'bold' => true], $cPragr);
	$header->addText("BICOL UNIVERSITY", ['size' => 12, 'bold' => true], $cPragr);
	$header->addText("Legazpi City", ['size' => 12], $cPragr);

	$textrun = $section->addTextRun(['alignment' => 'right']);
	$textrun->addText("No.    ");
	$textrun->addText($project['refno'], ['underline' => 'single']);
	$section->addText("Date. ".date('F j, o', strtotime($project['date'])), null, ['alignment' => 'right']);

	$section->addTextBreak(1);

	$section->addText("Title:", ['size' => 12, 'bold' => true], $cPragr);
	$section->addText($project['title'], ['size' => 11], $cPragr);

	$section->addTextBreak(1);
	

	$table = $section->addTable(['borderColor' => '#000000',
		'borderSize' => 6,
		'alignment' => 'center',
		'cellMarginLeft'  => 115.2,
		'cellMarginRight'  => 115.2
	]);

	$table->addRow(43.2)
		->addCell(1800)
		->addText("LOT NO.", $bText, $cPragr);
	$table->addCell(7200)
		->addText("PARTICULARS", $bText, $cPragr);
	$table->addCell(1800)
		->addText("COST", $bText, $cPragr);

	$lot_count = 1;
	foreach($project['lots'] as $lot){

		$table->addRow(43.2)
			->addCell(null, $tsAlignCenter)
			->addText($lot_count, null, $cPragr);

		$particulars = $table->addCell(null, $tsAlignCenter);
		$particulars->addTextBreak(1);
		$particulars->addText($lot['l_title'], ['size' => 11], $cStyle);
		$particulars->addTextBreak(1);
		
		foreach($lot['lot_items'] as $item){

			$textrun = $particulars->addTextRun($cStyle);
			$textrun->addText($item['header']." ", $bText);

			$i = 0;
			$tags = explode(",", $item['tags']);
			$tag_count = count($tags);
			
			foreach($tags as $tag)
			{
				if(++$i === $tag_count) $textrun->addText($tag);
				else $textrun->addText($tag.", ");
			}
			
			$particulars->addTextBreak(1);

		}

		if($lot['l_note'] !== ""){

			$particulars->addText("Note:", null, $cStyle);
			$particulars->addText($lot['l_note'], null, ['indentation' => ['left' => 300, 'right' => 300]]);
			$particulars->addTextBreak(1);

		}

		$table->addCell(null, $tsAlignCenter)
			->addText("&#8369; ".$lot['l_cost'], null, $cPragr);

		$lot_count++;
	}

	$section->addTextBreak(1);

	$table = $section->addTable(['borderColor' => '#FFFFFF',
		'borderSize' => 6,
		'alignment' => 'center',
		'cellMarginLeft'  => 115.2,
		'cellMarginRight'  => 115.2
	]);

	$table->addRow(43.2)
		->addCell(1400);
	$table->addCell(4000)
		->addText("Requested by:");
	$table->addCell(1400);
	$table->addCell(4000)
		->addText("Noted by:");

	$table->addRow(43.2)
		->addCell();
	$table->addCell()
		->addText($userFullName , ['underline' => 'single', 'bold' => true, 'size' => 11]);
	$table->addCell();
	$table->addCell()
		->addText($project['end_user']['signatories']['note'], ['underline' => 'single', 'bold' => true, 'size' => 11]);	


	$table->addRow()
		->addCell();
	$table->addCell()
		->addText($project['end_user']['edr_job_title']);
	$table->addCell();
	$table->addCell()
		->addText($project['end_user']['signatories']['note_position']);


	$table->addRow()->addCell();$table->addCell();$table->addCell();$table->addCell();
	$table->addRow()->addCell();$table->addCell();$table->addCell();$table->addCell();


	$table->addRow()
		->addCell();
	$table->addCell()
		->addText("Verified by:");
	$table->addCell();
	$table->addCell()
		->addText("Approved by:");

	
	$table->addRow()
		->addCell();
	$table->addCell()
		->addText($project['end_user']['signatories']['verifier'], ['underline' => 'single', 'bold' => true, 'size' => 11]);
	$table->addCell();
	$table->addCell()
		->addText($project['end_user']['signatories']['approving'], ['underline' => 'single', 'bold' => true, 'size' => 11]);

	$table->addRow()
		->addCell();
	$table->addCell()
		->addText($project['end_user']['signatories']['verifier_position']);
	$table->addCell();
	$table->addCell()
		->addText($project['end_user']['signatories']['approving_position']);



}


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
$objWriter->save('../pdf/'.$project['refno'].'.pdf');

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('C:/Users/Denver/Desktop/Project Request.docx');
$objWriter->save("php://output");

?>