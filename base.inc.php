<?php
	function checkSecurity($secret_key) {
		$settings = json_decode(file_get_contents('settings.json'), true);
		if($settings['secure_mode'] && $settings['secret_key'] != $secret_key) {
			http_response_code(501);
			die();
		}
	}
?>