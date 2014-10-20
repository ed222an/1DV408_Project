<?php
	session_start();
	
	// Basic session security.
	session_regenerate_id();
	ini_set('session.cookie_httponly', true);
	
	// Swedish format for the page.
	setlocale(LC_ALL , "swedish");
	
	require_once("common/HTMLView.php");
	require_once("controller/masterController.php");
	//require_once("controller/gameController.php");
	//require_once("controller/multiplayerController.php");
	
	$mc = new MasterController();
	$htmlBody = $mc->navigate();
	
	/*
	$gc = new GameController();
	$htmlBody = $gc->doGameControl();
	 * 
	 */
	
	$view = new HTMLView();
	$view->echoHTML($htmlBody);
?>