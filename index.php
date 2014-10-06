<?php
	session_start();
	
	// Adderar ytterliggare säkerhet gällande sessionen.
	session_regenerate_id();
	ini_set('session.cookie_httponly', true);
	
	// Ställer in sidans format så att månad, år, tid etc. visas på svenska.
	setlocale(LC_ALL , "swedish");
	
	require_once("common/HTMLView.php");
	require_once("controller/gameController.php");
	
	$gc = new GameController();
	$htmlBody = $gc->doHTMLBody();
	
	$view = new HTMLView();
	$view->echoHTML($htmlBody);
?>