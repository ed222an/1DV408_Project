<?php

class MasterView
{
	public static $actionMain = "main";
	public static $actionMultiplayerGame = "multiplayerGame";
	public static $actionComputerGame = "computerGame";
	
	// Checks what the GET contains to direct the user to the correct page.
	public static function getAction()
	{
		if(isset($_GET[self::$actionMultiplayerGame]))
		{
			return self::$actionMultiplayerGame;
		}
		
		if(isset($_GET[self::$actionComputerGame]))
		{
			return self::$actionComputerGame;
		}
		
		return self::$actionMain;
	}
}

?>