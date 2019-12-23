<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	$data = json_decode($input, true);
	checkSecurity($data['SecretKey']);

	$output = array();

	$title_id = $data['TitleId'];
	$timestamp = $data['Timestamp'];
	$campaign = $data['Campaign'];
	$map_group = $data['MapGroup'];

	$directory = "trackings/$title_id/$timestamp/campaigns/$campaign";

	if(!file_exists($directory)
	|| !file_exists($directory.'/campaign.json')
	|| !file_exists($directory.'/records.json')) {
		http_response_code(501);
		die();
	}

	$campaign_info = json_decode(file_get_contents($directory.'/campaign.json'), true);
	$records = json_decode(file_get_contents($directory.'/records.json'), true);

	$output = array();

	$group = $campaign_info['maps'][$map_group];
	foreach($group as $map) {
		$output[$map['map_uid']] = $records[$map['map_uid']];
	}

	echo json_encode($output);
?>