<?php
require_once "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$style = new Style();
$spreadsheet = new Spreadsheet();
$drawing = new Drawing();

$w = $spreadsheet->getActiveSheet();

$drawing->setPath('../assets/pics//Office logo.jpg');
$drawing->setHeight(36);
$drawing->setWorksheet($w);

$w->getColumnDimension('A')->setAutoSize(true);

$w->getRowDimension(1)->setRowHeight(38);
$w->mergeCells('A2:P2')
	->setCellValue('A2', 'ABSTRACT OF BIDS')
	->getStyle('A2:P2')->applyFromArray([
	'font' => [
		'name' => 'Arial Black',
		'size' => 14
	],
	'borders' => [
		'bottom' => ['borderStyle' => Border::BORDER_THIN]
	],
	'alignment' => [
		'horizontal' => Alignment::HORIZONTAL_CENTER,
		'vertical' => Alignment::VERTICAL_CENTER
	]
]);


// Project ID
$w->mergeCells('A3:C3')
	->setCellValue('A3', 'GDS2018-1')
	->getStyle('A3:C3')->applyFromArray([
	'font' => [
		'name' => 'Arial Narrow',
		'size' => 11
	],
	'borders' => [
		'left' => ['borderStyle' => Border::BORDER_THIN],
		'bottom' => ['borderStyle' => Border::BORDER_THIN]
	],
	'alignment' => [
		'horizontal' => Alignment::HORIZONTAL_CENTER,
		'vertical' => Alignment::VERTICAL_CENTER
	]
]);


// $w->mergeCells('A2:A3');
// $w->setCellValue('A2', 'Item No.');
// $w->getRowDimension(2)->setRowHeight(33);
// $w->getStyle('A2')->applyFromArray([
	
// ]);


// $w->getStyle('')->applyFromArray([
// 	'borders' => [
// 		'outline' => [
// 			'borderStyle' => Border::BORDER_THIN
// 		]
// 	],
// 	'alignment' => [
// 		'horizontal' => Alignment::HORIZONTAL_CENTER,
// 		'vertical' => Alignment::VERTICAL_CENTER
// 	]
// ]);


// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="myfile.xlsx"');
// header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('C:/Users/Denver/Desktop/abstract.xlsx');

// $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');
?>