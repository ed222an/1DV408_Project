<?php
	
require_once("./view/gameView.php");
require_once("./model/dataList.php");
require_once("./model/handModel.php");

// TODO: FIX SO THAT PLAYER 1 CAN SEE THE RESULTS.

class MultiplayerGameController
{
	private $gameView;
	private $dataList;
	private $isPlayerOne;
	private $isPlayerTwo;
	private $actualURL;
	
	public function __construct($gameType, $actualURL, $isPlayerTwo = FALSE)
	{
		$this->gameView = new GameView($gameType);
		$this->dataList = new DataList($actualURL);
		$this->actualURL = $actualURL;
		
		if($isPlayerTwo === TRUE)
		{
			$this->isPlayerOne = FALSE;
			$this->isPlayerTwo = TRUE;
		}
		else
		{
			$this->isPlayerOne = TRUE;
			$this->isPlayerTwo = FALSE;
		}
	}
	
	public function doMultiplayerGameControl()
	{	
		// If user chose a hand...
		if($this->gameView->userChoseHand() && $this->isPlayerOne === TRUE)
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
				
				$dataToSave = $this->dataList->appendToData($uniqueURL, $playerOneHand->getHandType());
				
				// Save the URL to textfile.
				$this->dataList->saveDataToFile($dataToSave);
				
				// Present the URL to the player.
				return $this->gameView->showGame($this->gameView->getURLHTML($uniqueURL));
			}
			catch(Exception $e)
			{
				$this->gameView->addMessage($e->getMessage());
				return $this->gameView->showGame();
			}
		}
		
		if($this->isPlayerTwo === TRUE)
		{
			if($this->gameView->userChoseHand())
			{
				try
				{	
					// Get the name of that hand and creates a playerHand object.
					$chosenHand = $this->gameView->getChosenHand();
					$playerTwoHand = new HandModel($chosenHand);
					
					// Get the players selected username.
					$playerTwoName = $this->gameView->getPlayername();
					$playerTwoHand->setPlayerName($playerTwoName);
					
					// Validate player name.
					$this->dataList->validatePlayerInput($playerTwoName);
					
					//Gets the first player's hand from the data file.
					$handFromFile = $this->dataList->dataExists($this->actualURL, $getHandType = TRUE);
					
					// Creates a new hand-object based on the filedata.
					$playerOneHand = new HandModel($handFromFile);
					
					// Sets the playername for the new object.
					$playerOneName = $this->dataList->getNameFromURL();
					$playerOneHand->setPlayerName($playerOneName);
					
					// Compares the hands and saves the outcome.
					// Will be 1 if player won, 2 if player lost or 3 if its a draw.
					$outcome = $playerTwoHand->compareHands($playerOneHand, $playerTwoHand);
					
					// Get the result HTML.
					$resultHTML = $this->gameView->getResult($outcome, $playerTwoHand, $playerOneHand);
					
					// Adds the players current score to the resultHTML.
					$resultHTML .= $this->gameView->getPlayerScore($playerTwoHand);
					
					// Show the resultpage.
					return $this->gameView->showGame($resultHTML);
				}
				catch(Exception $e)
				{
					$this->gameView->addMessage($e->getMessage());
					return $this->gameView->showGame();
				}
			}
		}

		// Return the gamepage per default.
		return $this->gameView->showGame();
	}
}

?>