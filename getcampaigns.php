<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	$data = json_decode($input, true);
	checkSecurity($data['SecretKey']);

	$output = array();

	$title_id = $data['TitleId'];
	$timestamp = $data['Timestamp'];

	$directory = "trackings/$title_id/$timestamp/campaigns";

	if(!file_exists($directory)) {
		http_response_code(501);
		die();
	}

	$output = array_values(array_diff(scandir($directory), array('.', '..')));

	echo json_encode($output);
?>