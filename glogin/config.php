<?php
	session_start();
	require_once "GoogleAPI/vendor/autoload.php";

	$gClient = new Google_Client();
	$gClient->setClientId("429390673281-r8reg1jh3ackd3bvo0r4tktj8vrnd3fn.apps.googleusercontent.com");
	$gClient->setClientSecret("DWOQh9OFXdy6D9nClwq9XQ7O");
	$gClient->setApplicationName("iShuffle");
	$gClient->setRedirectUri("http://vmedu152.mtacloud.co.il/glogin/g-callback.php");
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>
