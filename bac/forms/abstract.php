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



$section->addTextBreak(1);
$section->addText("ABSTRACT OF BIDS", ['size' => 14, 'bold' => true, 'name' => 'Arial Black'], $c);
$section->addText("", ['size' => 5]);

if($canvass->CanvassDetails->type === "PR"){

	if($canvass->CanvassDetails->per_item){



		

		// Per item
		$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
		$table->addRow(43.2);
		$table->addCell(3500, ['gridSpan' => 3])
			->addText("GDS2019-1", $fb);
		$table->addCell(3000)
			->addText("ABC: ".Date::Translate(40000.00, 'php'), $fb, ['alignment' => 'right']);
		$table->addCell(8000, ['gridSpan' => (count($suppliers) * 2)])
			->addText("BIDDERS", $fb, $c);
		
		$table->addRow(100);
		$table->addCell(null, $cc)
			->addText("Item #", $fb, $c);
		$table->addCell(null, $cc)
			->addText("Qty.", $fb, $c);
		$table->addCell(null, $cc)
			->addText("Unit", $fb, $c);
		$table->addCell(null, $cc)
			->addText("PARTICULARS", array_merge($fb, ['italic' => true]), $c);
		
		foreach($suppliers as $supplier){
			$table->addCell(null, ['valign' => 'center', 'gridSpan' => 2])
				->addText($supplier->name, $fb, $c);
		}
	
		$table->addRow(100);
		$table->addCell(4500, ['gridSpan' => 4])
			->addText(htmlspecialchars($project->project_title), ['italic' => true]);

		foreach($suppliers as $key => $supplier){
			$table->addCell(null, $cc)
				->addText("U/Price", ['underline' => 'single'], $c);
			$table->addCell(null, $cc)
				->addText("Total", ['underline' => 'single'], $c);
			$supplier_total[$key] = 0;
		}

				
		foreach($canvass->items as $key => $item){
			// one item
			$table->addRow(43.2);
			$table->addCell()
				->addText($key + 1, null, $c);
			$table->addCell()
				->addText($item->quantity, null, $c);
			$table->addCell()
				->addText($item->unit, null, $c);
			$table->addCell()
				->addText($item->item_description, null, $c);

			$item_offered = null;
			foreach($admin->getOffered($gds, $lot, $item->item_id) as $key => $offer){
				if($offer->price === "0.00"){
					$table->addCell(null, $cc)
						->addText("N/A", null, $r);
					$table->addCell(null, $cc)
						->addText("N/A", null, $r);
				}else{
					$table->addCell(null, $cc)
						->addText(Date::translate($offer->price, 'php'), null, $r);
					$table->addCell(null, $cc)
						->addText(Date::translate($offer->price * $item->quantity, 'php'), null, $r);
					$supplier_total[$key] += $offer->price * $item->quantity;
				}
				$item_offered[$key]['offered'] = $offer->offered;
				$item_offered[$key]['remark'] = $offer->item_remark;
			}

			$table->addRow(42.3);
			$table->addCell(null, ['gridSpan' => 4])
				->addText("Offered:", $fb, $r);
			foreach($item_offered as $offer_remark){
				$table->addCell(null, array_merge($cc, $gs2))
					->addText($offer_remark['offered'], ['italic' => true], $c);
			}

			$table->addRow(42.3);
			$table->addCell(null, ['gridSpan' => 4])
				->addText("Remark:", $fb, $r);
			foreach($item_offered as $offer_remark){
				$table->addCell(null, array_merge($cc, $gs2))
					->addText($offer_remark['remark'], ['italic' => true], $c);
			}


		}
		
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 4])
			->addText("Grand Total:", $fb, $r);

		foreach($supplier_total as $total){
			$table->addCell(null, $gs2)
				->addText(Date::translate($total, 'php'), $fb, $r);
		}






	}else{

	




		// Per Lot 

		$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
		$table->addRow(43.2);
		$table->addCell(3500, ['gridSpan' => 2])
			->addText("GDS2019-1", $fb);
		$table->addCell(3000)
			->addText("ABC: ".Date::Translate(40000.00, 'php'), $fb, ['alignment' => 'right']);
		$table->addCell(8000, ['gridSpan' => (count($suppliers) * 2)])
			->addText("BIDDERS", $fb, $c);
		
		$table->addRow(100);
		$table->addCell(null, $cc)
			->addText("Item #", $fb, $c);
		$table->addCell(null, $cc)
			->addText("Unit", $fb, $c);
		$table->addCell(null, $cc)
			->addText("PARTICULARS", array_merge($fb, ['italic' => true]), $c);
		
		foreach($suppliers as $supplier){
			$table->addCell(null, ['valign' => 'center', 'gridSpan' => 2])
				->addText($supplier->name, $fb, $c);
		}
	
		$table->addRow(100);
		$table->addCell(4500, ['gridSpan' => 3])
			->addText(htmlspecialchars($project->project_title), ['italic' => true]);

		foreach($suppliers as $key => $supplier){
			$table->addCell(null, $cc)
				->addText("U/Price", ['underline' => 'single'], $c);
			$table->addCell(null, $cc)
				->addText("Total", ['underline' => 'single'], $c);
			$supplier_total[$key] = 0;
			$supplier_remark[$key] = $supplier->remark;
		}

				
		foreach($canvass->items as $key => $item){
			// one item
			$table->addRow(43.2);
			$table->addCell()
				->addText($key + 1, null, $c);
			$table->addCell()
				->addText("lot", null, $c);
			$table->addCell()
				->addText($item->item_description, null, $c);

			$item_offered = null;
			foreach($admin->getOffered($gds, $lot, $item->item_id) as $key => $offer){
				if($offer->price === "0.00"){
					$table->addCell(null, $cc)
						->addText("N/A", null, $r);
					$table->addCell(null, $cc)
						->addText("N/A", null, $r);
					$table->addCell(null, $cc)
						->addText("N/A", null, $r);
				}else{
					$table->addCell(null, $cc)
						->addText(Date::translate($offer->price, 'php'), null, $r);
					$table->addCell(null, $cc)
						->addText(Date::translate($offer->price * $item->quantity, 'php'), null, $r);
					// $table->addCell(null, $cc)
					// 	->addText($offer->offered, null, $r);
					$supplier_total[$key] += $offer->price * $item->quantity;
				}
				$item_offered[$key]['offered'] = $offer->offered;
			}

			$table->addRow(42.3);
			$table->addCell(null, ['gridSpan' => 3])
				->addText("Offered:", $fb, $r);
			foreach($item_offered as $offer_remark){
				$table->addCell(null, array_merge($cc, $gs2))
					->addText($offer_remark['offered'], ['italic' => true], $c);
			}

			// $table->addRow(42.3);
			// $table->addCell(null, ['gridSpan' => 4])
			// 	->addText("Remark:", $fb, $r);
			// foreach($item_offered as $offer_remark){
			// 	$table->addCell(null, array_merge($cc, $gs2))
			// 		->addText($offer_remark['remark'], ['italic' => true], $c);
			// }

		}

		
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 3])
			->addText("Remarks:", $fb, $r);
		foreach($supplier_remark as $remark){
			$table->addCell(null, $gs2)
				->addText($remark, $fb, $c);
		}
		
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 3])
			->addText("Grand Total:", $fb, $r);

		foreach($supplier_total as $total){
			$table->addCell(null, $gs2)
				->addText(Date::translate($total, 'php'), $fb, $r);
		}

		

	}






}else if($canvass->CanvassDetails->type === "JO"){

	if($canvass->CanvassDetails->per_item){


		// per item

		$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
		$table->addRow(43.2);
		$table->addCell(3500, ['gridSpan' => 2])
			->addText("GDS2019-1", $fb);
		$table->addCell(3000)
			->addText("ABC: ".Date::Translate(40000.00, 'php'), $fb, ['alignment' => 'right']);
		$table->addCell(8000, ['gridSpan' => count($suppliers)])
			->addText("BIDDERS", $fb, $c);
		
		$table->addRow(100);
		$table->addCell(null, $cc)
			->addText("Item #", $fb, $c);
		$table->addCell(null, $cc)
			->addText("Unit", $fb, $c);
		$table->addCell(null, $cc)
			->addText("PARTICULARS", array_merge($fb, ['italic' => true]), $c);
		
		foreach($suppliers as $key => $supplier){
			$table->addCell(null, ['valign' => 'center'])
				->addText($supplier->name, $fb, $c);
			$supplier_remark[$key] = $supplier->remark;
			$supplier_total[$key] = 0;
		}
	
		$table->addRow(100);
		$table->addCell(4500, ['gridSpan' => 3])
			->addText($project->project_title, ['italic' => true]);
	
		foreach($suppliers as $key => $supplier){
			// $table->addCell(null, $cc)
			// 	->addText("U/Price", ['underline' => 'single'], $c);
			$table->addCell(null, $cc)
				->addText("Total", ['underline' => 'single'], $c);
		}
	
				
		foreach($canvass->items as $key => $item){
			// one item
			$table->addRow(43.2);
			$table->addCell()
				->addText($key + 1, null, $c);
			$table->addCell()
				->addText("lot", null, $c);
			$textrun = $table->addCell()->addTextRun($cStyle);
			$textrun->addText($item->header, ['bold' => true], $c);
	
			foreach($admin->getOffered($gds, $lot, $item->item_id) as $key => $offer){
				if($offer->price === "0.00"){
					$table->addCell(null, $cc)
						->addText("N/A", null, $r);
				}else{
					$table->addCell(null, $cc)
						->addText(Date::translate($offer->price, 'php'), null, $c);
					$supplier_total[$key] += $offer->price;
				}
			}
	
	
		}
	
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 3])
			->addText("Remarks:", $fb, $r);
		foreach($supplier_remark as $remark){
			$table->addCell()
				->addText($remark, $fb, $c);
		}
		
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 3])
			->addText("Grand Total:", $fb, $r);
		foreach($supplier_total as $total){
			$table->addCell()
				->addText(Date::translate($total, 'php'), $fb, $r);
		}






	}else{

		// per lot

		$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);
		$table->addRow(43.2);
		$table->addCell(3500, ['gridSpan' => 2])
			->addText("GDS2019-1", $fb);
		$table->addCell(3000)
			->addText("ABC: ".Date::Translate(40000.00, 'php'), $fb, ['alignment' => 'right']);
		$table->addCell(8000, ['gridSpan' => count($suppliers)])
			->addText("BIDDERS", $fb, $c);
		
		$table->addRow(100);
		$table->addCell(null, $cc)
			->addText("Item #", $fb, $c);
		$table->addCell(null, $cc)
			->addText("Unit", $fb, $c);
		$table->addCell(null, $cc)
			->addText("PARTICULARS", array_merge($fb, ['italic' => true]), $c);
		
		foreach($suppliers as $key => $supplier){
			$table->addCell(null, ['valign' => 'center'])
				->addText($supplier->name, $fb, $c);
			$supplier_remark[$key] = $supplier->remark;
			$supplier_total[$key] = 0;
		}
	
		$table->addRow(100);
		$table->addCell(4500, ['gridSpan' => 3])
			->addText($project->project_title, ['italic' => true]);
	
		foreach($suppliers as $key => $supplier){
			// $table->addCell(null, $cc)
			// 	->addText("U/Price", ['underline' => 'single'], $c);
			$table->addCell(null, $cc)
				->addText("Total", ['underline' => 'single'], $c);
		}
	
				
		foreach($canvass->items as $key => $item){
			// one item
			$table->addRow(43.2);
			$table->addCell()
				->addText($key + 1, null, $c);
			$table->addCell()
				->addText("lot", null, $c);
			$textrun = $table->addCell()->addTextRun($cStyle);
			$textrun->addText($item->header, ['bold' => true], $c);
	
			foreach($admin->getOffered($gds, $lot, $item->item_id) as $key => $offer){
				if($offer->price === "0.00"){
					$table->addCell(null, $cc)
						->addText("N/A", null, $c);
				}else{
					$table->addCell(null, $cc)
						->addText(Date::translate($offer->price, 'php'), null, $c);
					$supplier_total[$key] += $offer->price;
				}
			}
	
	
		}
	
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 3])
			->addText("Remarks:", $fb, $r);
		foreach($supplier_remark as $remark){
			$table->addCell()
				->addText($remark, $fb, $c);
		}
		
		$table->addRow(43.2);
		$table->addCell(null, ['gridSpan' => 3])
			->addText("Grand Total:", $fb, $r);
		foreach($supplier_total as $total){
			$table->addCell()
				->addText(Date::translate($total, 'php'), $fb, $c);
		}




	}







}

$section->addTextBreak(1);
$section->addText("WE HEREBY CERTIFY that the foregoing abstract is true and correct and were based on sealed canvass submitted to the committee.", ['size' => 12], $c);
$section->addTextBreak(3);





$chair = $admin->get('commitee', array('position', '=', 'BAC Chairperson'));
$vicechair = $admin->get('commitee', array('position', '=', 'Vice Chairman'));
$bacmember = $admin->get('commitee', array('position', '=', 'BAC Member'));
$technical = $project->evaluator;

$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'left', 'cellMarginLeft'  => 115.2, 'cellMarginRight' => 115.2]);

$table->addRow(100);
$cell = $table->addCell(4933.3, $gs2);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($chair->name), $fb);
$textrun->addTextBreak(1);
$textrun->addText($chair->position, ['size' => 9]);

$cell = $table->addCell(4933.3, $gs2);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($vicechair->name), $fb);
$textrun->addTextBreak(1);
$textrun->addText($vicechair->position, ['size' => 9]);

$cell = $table->addCell(4933.3, $gs2);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($bacmember->name), $fb);
$textrun->addTextBreak(1);
$textrun->addText($bacmember->position, ['size' => 9]);


$table->addRow(500);$table->addCell();$table->addCell();$table->addCell();$table->addCell();$table->addCell();$table->addCell();


$table->addRow(100);
$table->addCell(null, ['vMerge' => 'continue']);

$cell = $table->addCell(null, $gs2);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($technical), $fb);
$textrun->addTextBreak(1);
$textrun->addText("Technical Member", ['size' => 9]);

foreach(json_decode($project->end_user, true) as $end_user){
	$end_users[] = $admin->get('enduser', array('edr_id', '=', $end_user)); 
}

if($end_users[0]->edr_ext_name !== "XXXXX"){
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname." ".$end_users[0]->edr_ext_name;
}else{
	$userFullName = $end_users[0]->edr_fname." ".$end_users[0]->edr_mname." ".$end_users[0]->edr_lname;
}

$cell = $table->addCell(null, $gs2);
$textrun = $cell->addTextRun($c);
$textrun->addText(strtoupper($userFullName), $fb);
$textrun->addTextBreak(1);
$textrun->addText("End-User/Member", ['size' => 9]);
$table->addCell(null, ['vMerge' => 'continue']);






$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
// $objWriter->save('C:/Users/Denver/Desktop/Abstract.docx');
$objWriter->save("php://output");
?>