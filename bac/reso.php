<?php
require_once "../vendor/autoload.php";
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// $file = "RESO.docx";
// header("Content-Description: File Transfer");
// header('Content-Disposition: attachment; filename="'.$file.'"');
// header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
// header('Content-Transfer-Encoding: binary');
// header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
// header('Expires: 0');

$Docs = "ALT";
$ABC = 2250.30;
$RESO_NO = htmlspecialchars("LCRB1-GDS-2018-253");
$project = htmlspecialchars("Purchase of Office Supplies to be used for he Project Development of Innovative Scallop Mariculture Techniques (DIVSMART)");
$supplier = htmlspecialchars("Legazpi General Merchandise");
$DATE_POST = htmlspecialchars("May 18-23, 2018");
$END_USER = htmlspecialchars("Reina O. Habalo");
$bidders[0] = [
	'name' => "Legazpi General Merchandise", 
	'bid' => "PhP 4580.00", 
	'rmk' => 'Rand of bid 1: compliant and in order'
];
$bidders[1] = [
	'name' => "New Silahis Education Supply & Gen. MDSE.", 
	'bid' => "PhP 2400.00" , 
	'rmk' => "DQ: Incomplete Bid"
];
$bidders[2] = [
	'name' => "TCL Merchandise Brokerage INC.", 
	'bid' => "PhP 2899.25" ,
	'rmk' => "DQ: Incomplete Bid"
];
$OFF_HEAD = ['name' => 'Ronnel R. Dioneda Jr.', 'pos' => 'OIC-VP for RD&E'];
$BAC_MEM = ['chair' => 'Jerry S. Bigornia', 'vice' => 'Norly P. Reyes', 'member' => 'Loyd P. Casasis', 'twg' => 'Angelo P. Candelaria'];

$Setting = [
	'PB' => [
		'orientation' => 'portrait',
		'marginTop' => 1440,
		'marginLeft' => 1440,
		'marginBottom' => 432,
		'marginRight' => 1036.8,
		'headerHeight' => 705.6,
		'footerHeight' => 705.6
	],
	'ALT' => [
		'orientation' => 'portrait',
		'marginTop' => 230.4,
		'marginLeft' => 561.6,
		'marginBottom' => 1843.2,
		'marginRight' => 619.2,
		'headerHeight' => 144,
		'footerHeight' => 0,
		'pageSizeW' => 12240,
		'pageSizeH' => 18720
	],
	'FL' => [
		'orientation' => 'portrait',
		'marginTop' => 720,
		'marginLeft' => 1080,
		'marginBottom' => 720,
		'marginRight' => 921.6,
		'headerHeight' => 360,
		'footerHeight' => 705.6
	]
];

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


list($whole, $decimal) = explode('.', $ABC);
if($decimal <= 10)
{
	$decimal = sprintf("%d0", $decimal);
	$ABC = sprintf("%s0", $ABC);
}
$format = new NumberFormatter("en", NumberFormatter::SPELLOUT);
$whole = htmlspecialchars($format->format($whole));


$HEADER = "BAC RESOLUTION DECLARING ".strtoupper($supplier)." WITH THE LOWEST CALCULATED AND RESPONSIVE BID (LCRB) " .
		"FOR THE CONTRACT: ".strtoupper($project);

$section->addText($HEADER, [
	'name' => 'Arial', 
	'size' => 11, 
	'bold' => true],
	['alignment' => 'center', 'space' => ['after' => 20]]
);

$textrun = $section->addTextRun(['alignment' => 'center']);
$textrun->addText("Resolution No. ", ['name' => 'Arial Narrow', 'size' => 12, 'italic' => true]);
$textrun->addText(htmlspecialchars($RESO_NO), ['name' => 'Arial Narrow', 'size' => 11, 'italic' => true]);

$bStyle = ['size' => 11];
$bPragr = ['alignment' => 'both'];
$bSpace = ['space' => ['before' => 20 ,'after' => 20]];

//
$section->addTextBreak(1, ['size' => 11], $bSpace);
//


$whole = ucwords($whole);
$textrun = $section->addTextRun($bPragr);
$textrun->addText("WHEREAS, the BICOL UNIVERSITY requested for the project: ", $bStyle);
$textrun->addText($project, ['italic' => true, 'size' => 11]);
$textrun->addText(" with an Approved Budget for the Contract (ABC): ", $bStyle);
$textrun->addText($whole." and ".$decimal."/100 Pesos (PhP ". $ABC .");", ['italic' => true, 'size' => 11]);

$section->addTextBreak(1, ['size' => 11], $bSpace);

$MODE = "Shopping (Section 52.1(b) of the Revised IRR of RA 9184) as an alternative mode" .
		" and posted the project in the BAC Bulletin Board for the period {$DATE_POST}";

$section->addText("WHEREAS, the BAC resorted to ".htmlspecialchars($MODE), $bStyle, $bPragr);
$section->addTextBreak(1, ['size' => 11], $bSpace);
$section->addText("WHEREAS, the committee waived the formalities of competitive bidding procedures, " . 
"the evaluation shall be by lot, and issued Request for Quotation from suppliers of known qualifications;", $bStyle, $bPragr);
$section->addTextBreak(1, ['size' => 11], $bSpace);
$section->addText("WHEREAS, ".$format->format(count($bidders))." (".count($bidders).") suppliers provided quotations with the following results:", $bStyle, $bPragr);
$section->addTextBreak(1, ['size' => 11], $bSpace);


$thStyle = ['size' => 10, 'bold' => true];
$thPragr = ['alignment' => 'center'];


$table = $section->addTable(['borderColor' => '#000000', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);
$table->addRow(43.2);
$table->addCell(4896)->addText("Bidders", $thStyle, $thPragr);
$table->addCell(1886)->addText("Bid Amount", $thStyle, $thPragr);
$table->addCell(3556)->addText("Remarks", $thStyle, $thPragr);

$trStyle = ['size' => 10];

for($r = 0 ; $r < count($bidders) ; $r++)
{
	$table->addRow(43.2);
	$table->addCell(4896, ['valign' => 'center'])->addText(htmlspecialchars(strtoupper($bidders[$r]['name'])), $trStyle, ['alignment' => 'left']);
	$table->addCell(1886, ['valign' => 'center'])->addText(htmlspecialchars($bidders[$r]['bid']), $trStyle, $thPragr);
	$table->addCell(3556, ['valign' => 'center'])->addText(htmlspecialchars($bidders[$r]['rmk']), $trStyle, $thPragr);
}

$section->addTextBreak(1, ['size' => 11], $bSpace);

$textrun = $section->addTextRun($bPragr);
$textrun->addText("WHEREAS, upon examination, validation and verification of all the eligibility, technical and " .
					"financial requirements submitted by ", $bStyle);
// bidder won
$textrun->addText(htmlspecialchars(strtoupper($bidders[0]['name'])), ['size' => 11, 'italic' => true]);
$textrun->addText(" it was found to be responsive;", $bStyle);

$section->addTextBreak(1, ['size' => 11], $bSpace);


$section->addText("\tNOW, THEREFORE, We, the Members of the Bids and Awards Committee, hereby RESOLVE as it is hereby RESOLVED:", ['size' => 11]);

$section->addTextBreak(1, ['size' => 11], $bSpace);

$HEAD_SIG = "Vp for RD&E";

$listrun = $section->addListItemRun(0, 'mult', $bPragr);
$listrun->addText("To declare ", $bStyle, $bPragr);
$listrun->addText(strtoupper($supplier), ['size' => 11, 'bold' => true], $bPragr);
$listrun->addText(" as the bidder with the lowest calculated and responsive bid for the contract: ", $bStyle);
$listrun->addText($project, ['size' => 11, 'bold' => true, 'italic' => true]);
$listrun->addText(" the amount equivalent to ", $bStyle);
$listrun->addText($whole." and ".$decimal."/100 Pesos (Php ".$ABC.");", ['size' => 11, 'bold' => true, 'italic' => true]);

$listrun->addTextBreak(1, ['size' => 11], $bSpace);

$listrun = $section->addListItemRun(0, 'mult', $bPragr);
$listrun->addText("To recommend for approval by ".htmlspecialchars($HEAD_SIG)." of Bicol University the foregoing findings.", $bStyle);

$section->addTextBreak(1, ['size' => 11], $bSpace);

$section->addText("\tRESOLED, at the Bicol University, Legazpi City, this ".date("jS \of F, Y"), $bStyle, $bPragr);

$section->addTextBreak(3, ['size' => 8], $bSpace);


$cSpan = ['gridSpan' => 3];
$cConSty = ['valign' => 'bottom'];
// $cConti = ['vMerge' => 'continue'];

$NclStyle = ['size' => 10, 'bold' => true];
$TXclStyle = ['size' => 10];


$table = $section->addTable(['borderColor' => '#FFFFFF', 'borderSize' => 6, 'alignment' => 'center', 'cellMarginLeft'  => 115.2]);

$table->addRow(172);
$table->addCell(9950.4, $cSpan)->addText(htmlspecialchars(strtoupper($BAC_MEM['chair'])), $NclStyle, $thPragr);

$table->addRow(489.6);
$table->addCell(9950.4, $cSpan)->addText("BAC Chairperson", $TXclStyle, $thPragr);

$table->addRow(374.4);
$table->addCell(3316.8, $cConSty)->addText(htmlspecialchars(strtoupper($BAC_MEM['vice'])), $NclStyle, $thPragr);
$table->addCell(3316.8, $cConSty)->addText(htmlspecialchars(strtoupper($BAC_MEM['member'])), $NclStyle, $thPragr);
$table->addCell(3316.8, $cConSty)->addText(htmlspecialchars(strtoupper($BAC_MEM['twg'])), $NclStyle, $thPragr);

$table->addRow(172);
$table->addCell(3316.8)->addText("Vice Chairperson", $TXclStyle, $thPragr);
$table->addCell(3316.8)->addText("BAC Member", $TXclStyle, $thPragr);
$table->addCell(3316.8)->addText("Technical Member", $TXclStyle, $thPragr);

$table->addRow(500);
$table->addCell(9950.4, $cSpan);

$table->addRow(172);
$table->addCell(9950.4, $cSpan)->addText(strtoupper($END_USER), $NclStyle, $thPragr);
$table->addRow(172);
$table->addCell(9950.4, $cSpan)->addText("End-User/Member", $TXclStyle, $thPragr);

$table->addRow(700);
$table->addCell(3316.8, $cConSty)->addText("Approved:", $TXclStyle, ['alignment' => 'right']);
$table->addCell(3316.8)->addText("", $TXclStyle, $thPragr);
$table->addCell(3316.8)->addText("", $TXclStyle, $thPragr);

$table->addRow(172);
$table->addCell(9950.4, $cSpan)->addText(htmlspecialchars(strtoupper($OFF_HEAD['name'])), $NclStyle, $thPragr);
$table->addRow(172);
$table->addCell(9950.4, $cSpan)->addText(htmlspecialchars(strtoupper($OFF_HEAD['pos'])), $TXclStyle, $thPragr);

// \PhpOffice\PhpWord\Settings::loadConfig();
// $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
// $objWriter->save('C:/Users/Denver/Desktop/JO.pdf');

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('C:/Users/Denver/Desktop/BAC RESO.docx');
// $objWriter->save("php://output");
?>