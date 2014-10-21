<?php

require_once("./model/dataList.php");

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
			$dataList = new DataList();
			
			if($dataList->dataExists("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"))
			{
				var_dump("DATA EXISTS");
				return self::$actionContinueMultiplayerGame;
			}
		}
		
		if(isset($_GET[self::$actionComputerGame]))
		{
			return self::$actionComputerGame;
		}
		
		return self::$actionMain;
	}
}

?>