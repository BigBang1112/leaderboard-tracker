<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	checkSecurity($input);

	$output = array();

	$titles = array_values(array_diff(scandir('trackings'), array('.', '..')));
	foreach($titles as $title) {
		$output[$title] = array_reverse(array_values(array_diff(scandir('trackings/'.$title), array('.', '..'))));
	}

	echo json_encode($output);
?>