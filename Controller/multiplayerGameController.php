<?php
	
require_once("./view/gameView.php");
require_once("./model/urlList.php");
require_once("./model/handModel.php");

// TODO: FIX SO THAT WHEN USER CHOSES HAND, GENERATE UNIQUE URL TO SEND TO OPPONENT.

class MultiplayerGameController
{
	private $gameView;
	private $urlList;
	
	public function __construct($gameType)
	{
		$this->gameView = new GameView($gameType);
		$this->urlList = new URLList();
	}
	
	public function doMultiplayerGameControl()
	{		
		// If user chose a hand...
		if($this->gameView->userChoseHand())
		{
			try
			{	
				// Get the name of that hand and creates a playerHand object.
				$chosenHand = $this->gameView->getChosenHand();
				$playerHand = new HandModel($chosenHand);
				
				// Get the players selected username.
				$playername = $this->gameView->getPlayername();
				
				// Generate a new URL for the player to send to his/her opponent.
				$uniqueURL = $this->urlList->generateUniqueURL($playername, $chosenHand);
				
				// Save the URL to textfile.
				$this->urlList->saveURLToFile($uniqueURL);
				
				// Present the URL to the player.
				return $this->gameView->showGame($uniqueURL);
				
				// Other player does his shit...
				
				// Compare results.
				
				
				// Show outcome.
			}
			catch(Exception $e)
			{
				$this->gameView->addMessage($e->getMessage());
				return $this->gameView->showGame();
			}
		}
		
		// Return the computergamepage per default.
		return $this->gameView->showGame();
	}
}

?>