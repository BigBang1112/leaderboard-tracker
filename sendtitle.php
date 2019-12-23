<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	$data = json_decode($input, true);
	checkSecurity($data['SecretKey']);

	$title_directory = 'trackings/'.$data['TitleId'];

	$timestamp = $data['Timestamp'];
	$timestamp_directory = $title_directory . '/' . $timestamp;

	if(!file_exists($title_directory)) mkdir($title_directory, 0777, true);
	if(!file_exists($timestamp_directory)) mkdir($timestamp_directory, 0777, true);

	$output = array();
	$output['title_id'] = $data['TitleId'];
	$output['author_login'] = $data['AuthorLogin'];
	$output['author_name'] = $data['AuthorName'];
	$output['name'] = $data['Name'];
	$output['desc'] = $data['Desc'];
	$output['info_url'] = $data['InfoUrl'];
	$output['download_url'] = $data['DownloadUrl'];
	$output['title_version'] = $data['TitleVersion'];
	$output['maker_title_id'] = $data['MakerTitleId'];
	$output['base_title_id'] = $data['BaseTitleId'];

	file_put_contents($timestamp_directory.'/title.json', json_encode($output, JSON_PRETTY_PRINT));
?>