<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	if(empty($input)) die();
	$data = json_decode($input, true);
	checkSecurity($data['SecretKey']);

	$title_id = $data['TitleId'];
	$timestamp1 = $data['Timestamp1'];
	$timestamp2 = $data['Timestamp2'];

	if($timestamp2 > $timestamp1) { // currently forced so that it always compares newer to older
		$temp = $timestamp2;
		$timestamp2 = $timestamp1;
		$timestamp1 = $temp;
	}

	$comparison = array();
	$timestamps = array($timestamp1, $timestamp2);
	$difference = array();
	$output = array();

	for($i=0; $i < count($timestamps); $i++) {
		$timestamp = $timestamps[$i];

		$campaigns = array();

		$timestamp_campaigns = array_values(array_diff(scandir("trackings/$title_id/$timestamp/campaigns"), array('.', '..')));

		foreach($timestamp_campaigns as $c) {
			$records_file = "trackings/$title_id/$timestamp/campaigns/$c/records.json";
			if(!file_exists($records_file)) {
				http_response_code(502);
				die();
			}
			$campaigns[$c] = json_decode(file_get_contents($records_file), true);
		}

		$comparison[] = $campaigns;
	}

	if(count($comparison) != 2) {
		http_response_code(501);
		die();
	}

	foreach($comparison[0] as $campaign1=>$records1) {
		foreach($records1 as $uid=>$recs1) {
			if(array_key_exists($campaign1, $comparison[1]) && array_key_exists($uid, $comparison[1][$campaign1])) {
				for($i=0; $i < count($recs1); $i++) {
					$rec1 = $recs1[$i];
					if(array_key_exists($i, $comparison[1][$campaign1][$uid])) {
						$rec2 = $comparison[1][$campaign1][$uid][$i];
						if($rec1 != $rec2) {
							$difference[$campaign1][$uid] = $comparison[1][$campaign1][$uid];
						}
					}
					else {
						$difference[$campaign1][$uid] = $comparison[1][$campaign1][$uid];
					}
				}
			}
		}
	}

	foreach($difference as $c => $maps) {
		$campaign_file = "trackings/$title_id/$timestamp/campaigns/$c/campaign.json";
		if(!file_exists($campaign_file)) {
			http_response_code(502);
			die();
		}
		$campaign = json_decode(file_get_contents($campaign_file), true);
		$output[$c] = $campaign['maps'];

		foreach($output[$c] as &$group) {
			foreach($group as &$map) {
				if(array_key_exists($map['map_uid'], $maps)) {
					foreach($maps[$map['map_uid']] as $rec)
						$map['records'][] = $rec;
				}
				else
					$map = null;
			}
		}
	}

	echo json_encode($output);
?>