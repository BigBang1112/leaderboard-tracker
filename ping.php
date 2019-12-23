<?php
	require_once('base.inc.php');

	$input = file_get_contents('php://input');
	checkSecurity($input);
?>