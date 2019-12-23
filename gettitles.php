<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	checkSecurity($input);

	$titles = array_values(array_diff(scandir('trackings'), array('.', '..')));

	echo json_encode($titles);
?>