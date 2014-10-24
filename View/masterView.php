<?php

require_once("./model/dataHandler.php");

class MasterView
{
	public static $actionMain = "main";
	public static $actionMultiplayerGame = "multiplayerGame";
	public static $actionContinueMultiplayerGame = "continueMultiplayerGame";
	public static $actionComputerGame = "computerGame";
	
	// Checks what the GET contains to direct the user to the correct page.
	public static function getAction()
	{
		if(isset($_GET[self::$actionMultiplayerGame]))
		{
			return self::$actionMultiplayerGame;
		}
		
		if(isset($_GET[self::$actionContinueMultiplayerGame]))
		{
			$actualURL = self::getActualURL();
			$dataHandler = new DataHandler($actualURL, self::$actionContinueMultiplayerGame);
			
			if($dataHandler->handleData($actualURL))
			{
				return self::$actionContinueMultiplayerGame;
			}
		}
		
		if(isset($_GET[self::$actionComputerGame]))
		{
			return self::$actionComputerGame;
		}
		
		return self::$actionMain;
	}
	
	public static function getActualURL()
	{
		return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}
}

?>