<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	$data = json_decode($input, true);
	checkSecurity($data['SecretKey']);

	$output = array();

	$title_id = $data['TitleId'];
	$timestamp = $data['Timestamp'];
	$campaign = $data['Campaign'];

	$directory = "trackings/$title_id/$timestamp/campaigns/$campaign";

	if(!file_exists($directory)) {
		http_response_code(501);
		die();
	}

	$campaign_info = json_decode(file_get_contents($directory.'/campaign.json'), true);

	echo json_encode($campaign_info['maps']);
?>