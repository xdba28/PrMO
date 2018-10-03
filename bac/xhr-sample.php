<?php

$str_json = file_get_contents('php://input');

$data = [
	'date' => date('r'),
	'post' => json_decode($str_json, true)
];

header("Content-type:application/json");
echo json_encode($data);

?>