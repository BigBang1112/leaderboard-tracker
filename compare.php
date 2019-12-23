<?php

	// Just an idea that didn't work out

	require_once('base.inc.php');

	function check_diff_multi($array1, $array2){
		$result = array();
		foreach($array1 as $key => $val) {
			if(isset($array2[$key])){
				if(is_array($val) && $array2[$key]){
					$result[$key] = check_diff_multi($val, $array2[$key]);
				}
			} else {
				$result[$key] = $val;
			}
		}
	
		return $result;
	}

	function array_filter_recursive($array, $callback = null, $remove_empty_arrays = true) {
		foreach ($array as $key => & $value) { // mind the reference
			if (is_array($value)) {
				$value = array_filter_recursive($value, $callback);
				if ($remove_empty_arrays && ! (bool) $value) {
					unset($array[$key]);
				}
			}
			else {
				if ( ! is_null($callback) && ! $callback($value)) {
					unset($array[$key]);
				}
				elseif ( ! (bool) $value) {
					unset($array[$key]);
				}
			}
		}
		unset($value); // kill the reference
		return $array;
	}

	$input = file_get_contents('php://input');
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

	$timestamps = array($timestamp1, $timestamp2);
	
	$compare1 = array();
	$compare2 = array();

	for($i=0; $i < count($timestamps); $i++) {
		$timestamp = $timestamps[$i];

		$timestamp_campaigns = array_values(array_diff(scandir("trackings/$title_id/$timestamp/campaigns"), array('.', '..')));

		$campaigns = array();

		foreach($timestamp_campaigns as $c) {
			$campaign_files = array_values(array_diff(scandir("trackings/$title_id/$timestamp/campaigns/$c"), array('.', '..')));

			$campaign = json_decode(file_get_contents("trackings/$title_id/$timestamp/campaigns/$c/campaign.json"), true);
			$campaigns[$c] = $campaign['maps'];

			$records = json_decode(file_get_contents("trackings/$title_id/$timestamp/campaigns/$c/records.json"), true);

			foreach($campaigns[$c] as &$group) {
				foreach($group as &$map) {
					$map['records'] = $records[$map['map_uid']];
				}
			}
		}

		if($i==0) $compare1 = $campaigns;
		else if($i==1) $compare2 = $campaigns;
	}

	echo json_encode(array_filter_recursive(check_diff_multi($compare1, $compare2)));
?>