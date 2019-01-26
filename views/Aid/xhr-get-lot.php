<?php
require_once('../../core/init.php');
header("Content-type:application/json");

$user = new Admin();

if($user->isLoggedIn());
else{
	Redirect::To('../../blyte/acc3ss');
	die();
}

try
{
	if(!empty($_GET['id'])){
		$get_lot = explode("-", $_GET['lot']);
		$lot_details = $user->selectCanvassForm($_GET['id'], $get_lot[1], $get_lot[0]);
		$lot_details->CanvassDetails->cost = Date::translate($lot_details->CanvassDetails->cost, 'php');

		$suppliers = $user->getSuppliers($_GET['id'], $lot_details->CanvassDetails->id);

		foreach($lot_details->items as $item){
			if($item->item_fail !== "1"){
				$offered[] = $user->getOffered($_GET['id'], $lot_details->CanvassDetails->id, $item->item_id);
			}
		}

		echo json_encode([
			'success' => 'true',
			'lot' => $lot_details,
			'canvass_returns' => $offered,
			'suppliers' => $suppliers
		]);
	}else{
		echo json_encode(['success' => 'error']);
	}
}
catch(Exception $e)
{
	echo json_encode(['success' => false, 'error' => $e]);
}

?>