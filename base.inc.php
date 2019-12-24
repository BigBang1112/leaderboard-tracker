<?php
	include('settings.inc.php');

	function checkSecurity($key) {
		if($GLOBALS['secure_mode'] && $GLOBALS['secret_key'] != $key) {
			http_response_code(501);
			die();
		}
	}
?>