<?php
require "config.php";

session_start();

if($_GET['oauth_verifier'] ) {
	try {
		// Request Token과 verifier로 Access Token 얻기
		$oauth->setToken($_GET['oauth_token'],$_SESSION["request_token_secret"]);
		$access_token_info = $oauth->getAccessToken($access_token_url, null, $_GET['oauth_verifier']);

		/*************************************************
		$access_token_info = Array
			(
				[oauth_token] => zzLnDqUv9dw3zRVPgQT_P4uIk6Xo5h
				[oauth_token_secret] => F7oRV4YtatYUIKak
				[userid] => fKG9GbyMJmRo6Q ncSaF
			)
		************************************************/

		// Access Token으로 교환 되었으므로 Request Token 삭제.
		unset($_SESSION["request_token_secret"]);
		
		// Access Token을 세션에 저장
		$_SESSION['access_token'] = $access_token_info['oauth_token'];
		$_SESSION['access_token_secret'] = $access_token_info['oauth_token_secret'];
		header("Location: ./index.php");

	} catch(OAuthException $E) {
		print_r($E);
		exit;
	}
}
// protected resource가 있는 페이지로
?>