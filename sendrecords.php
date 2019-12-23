<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	$data = json_decode($input, true);
	checkSecurity($data['SecretKey']);

	$title_directory = 'trackings/'.$data['TitleId'];

	$timestamp = $data['Timestamp'];
	$timestamp_directory = $title_directory . '/' . $timestamp;
	$campaigns_directory = $timestamp_directory . '/campaigns';
	$campaign_directory = $campaigns_directory . '/' . $data['CampaignId'];

	if(!file_exists($title_directory)) mkdir($title_directory, 0777, true);
	if(!file_exists($timestamp_directory)) mkdir($timestamp_directory, 0777, true);
	if(!file_exists($campaigns_directory)) mkdir($campaigns_directory, 0777, true);
	if(!file_exists($campaign_directory)) mkdir($campaign_directory, 0777, true);

	$output = array();

	foreach($data['Records'] as $uid => $recs) {
		$records = array();

		foreach($recs as $r) {
			$record['rank'] = $r['Rank'];
			$record['login'] = $r['Login'];
			$record['nickname'] = $r['Nickname'];
			$record['score'] = $r['Score'];
			$record['replay_url'] = $r['ReplayUrl'];
			$record['file_name'] = $r['FileName'];
			$records[] = $record;
		}

		$output[$uid] = $records;
	}

	file_put_contents("$campaign_directory/records.json", json_encode($output, JSON_PRETTY_PRINT));
?>