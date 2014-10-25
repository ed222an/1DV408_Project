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
		// Check for multiplayerGame in the $_GET.
		if(isset($_GET[self::$actionMultiplayerGame]))
		{
			return self::$actionMultiplayerGame;
		}
		
		// Check for continueMultiplayerGame in the $_GET.
		if(isset($_GET[self::$actionContinueMultiplayerGame]))
		{
			// Gets the actual url.
			$actualURL = self::getActualURL();
			
			// Creates a ne DataHandler with the actual URL as a parameter.
			$dataHandler = new DataHandler($actualURL, self::$actionContinueMultiplayerGame);
			
			// Checks if the textfile contains the actual url (for when a second player continues a started game).
			if($dataHandler->handleData($actualURL))
			{
				return self::$actionContinueMultiplayerGame;
			}
		}
		
		// Check for computerGame in the $_GET.
		if(isset($_GET[self::$actionComputerGame]))
		{
			return self::$actionComputerGame;
		}
		
		// Return to the main-page per default.
		return self::$actionMain;
	}
	
	// Gets the actual URL.
	public static function getActualURL()
	{
		return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}
}

?>