<?php
	
require_once("./view/gameView.php");
require_once("./model/dataList.php");
require_once("./model/handModel.php");


class MultiplayerGameController
{
	private $gameView;
	private $dataList;
	private $isPlayerTwo;
	
	public function __construct($gameType, $isPlayerTwo = FALSE)
	{
		$this->gameView = new GameView($gameType);
		$this->dataList = new DataList();
		$this->playerTwo = $isPlayerTwo;
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
				$playerOneHand = new HandModel($chosenHand);
				
				// Get the players selected username.
				$playerOneName = $this->gameView->getPlayername();
				
				// Validate player name.
				$this->dataList->validatePlayerInput($playerOneName);
				
				// Generate a new URL for the player to send to his/her opponent.
				$uniqueURL = $this->dataList->generateUniqueURL($playerOneName);
				
				// Save the URL to textfile.
				$this->dataList->saveDataToFile($uniqueURL);
				
				//TODO: SAVE PLAYER INFORMATION TO FILE.
				
				// Present the URL to the player.
				return $this->gameView->showGame($this->gameView->getURLHTML($uniqueURL));
				
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
		
		if($this->playerTwo === TRUE)
		{
			var_dump("TESTING");
			/*
			if($this->gameView->userChoseHand())
			{
				try
				{	
					// Get the name of that hand and creates a playerHand object.
					$chosenHand = $this->gameView->getChosenHand();
					$playerTwoHand = new HandModel($chosenHand);
					
					// Get the players selected username.
					$playerTwoName = $this->gameView->getPlayername();
					
					// Validate player name.
					$this->dataList->validatePlayerInput($playerOneName);
					
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
			 * 
			 */
		}
		
		// Return the gamepage per default.
		return $this->gameView->showGame();
	}
}

?>