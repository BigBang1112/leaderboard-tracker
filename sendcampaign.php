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
	$output['id'] = $data['CampaignId'];
	$output['player_count'] = $data['PlayerCount'];
	$output['maps'] = array();

	foreach($data['Maps'] as $g) {
		$group = array();
		foreach($g as $uid => $m) {
			$map = array();
			$map['name'] = $m['Name'];
			$map['map_uid'] = $m['MapUid'];
			$map['comments'] = $m['Comments'];
			$map['copper_price'] = $m['CopperPrice'];
			$map['collection_name'] = $m['CollectionName'];
			$map['author_login'] = $m['AuthorLogin'];
			$map['author_nickname'] = $m['AuthorNickName'];
			$map['author_zone_path'] = $m['AuthorZonePath'];
			$map['map_type'] = $m['MapType'];
			$map['is_playable'] = $m['IsPlayable'];
			$map['author_time'] = $m['TMObjective_AuthorTime'];
			$map['gold_time'] = $m['TMObjective_GoldTime'];
			$map['silver_time'] = $m['TMObjective_SilverTime'];
			$map['bronze_time'] = $m['TMObjective_BronzeTime'];
			$map['laps'] = $m['TMObjective_NbLaps'];
			$map['is_lap_race'] = $m['TMObjective_IsLapRace'];
			$map['record_count'] = $m['RecordCount'];

			$group[] = $map;
		}
		$output['maps'][] = $group;
	}

	file_put_contents("$campaign_directory/campaign.json", json_encode($output, JSON_PRETTY_PRINT));
?>