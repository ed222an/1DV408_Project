<?php

require_once("./view/masterView.php");
require_once("controller/mainController.php");
require_once("controller/computerGameController.php");
require_once("controller/multiplayerGameController.php");

class MasterController
{
	// Kontrollerar vilken del av applikationen som skall visas.
	public function navigate()
	{
		try
		{
			switch(MasterView::getAction())
			{
				case MasterView::$actionMultiplayerGame:
					
					$actualURL = MasterView::getActualURL();
					$controller = new MultiplayerGameController(MasterView::$actionMultiplayerGame, $actualURL);
					$result = $controller->doMultiplayerGameControl();
					return $result;
					break;
					
				case MasterView::$actionContinueMultiplayerGame:
					
					$isPlayerTwo = TRUE;
					$actualURL = MasterView::getActualURL();
					$controller = new MultiplayerGameController(MasterView::$actionContinueMultiplayerGame, $actualURL, $isPlayerTwo);
					$result = $controller->doMultiplayerGameControl();
					return $result;
					break;
				
				case MasterView::$actionComputerGame:
					
					$controller = new ComputerGameController(MasterView::$actionComputerGame);
					$result = $controller->doComputerGameControl();
					return $result;
					break;
					
				case MasterView::$actionMain:
				default:
					
					$controller = new MainController();
					$result = $controller->doMainControl(); 
					return $result;
					break;
			}	
		}
		catch (Exception $e)
		{
			$controller = new MainController();
			$result = $controller->doMainControl();
			return $result;
		}	
	}
}

?>