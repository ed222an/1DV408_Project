<?php
	
require_once("./view/gameView.php");
require_once("./model/handModel.php");

// TODO: FIX SO THAT WHEN USER CHOSES HAND, GENERATE UNIQUE URL TO SEND TO OPPONENT.

class MultiplayerGameController
{
	private $gameView;
	
	public function __construct($gameType)
	{
		$this->gameView = new GameView($gameType);
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
				
				// Creates a randomly generated hand for the computer.
				$computerHand = new HandModel(NULL, FALSE);
				
				// Compares the hands and saves the outcome.
				// Will be 1 if player won, 2 if player lost or 3 if its a draw.
				$outcome = $playerHand->compareHands($computerHand);
				
				// Get the result HTML.
				$resultHTML = $this->gameView->getResult($outcome, $playerHand->getHandType(), $computerHand->getHandType());
				
				// Adds the players current score to the resultHTML.
				$resultHTML .= $this->gameView->getPlayerScore($playerHand);
				
				// Show the resultpage.
				return $this->gameView->showGame($resultHTML);
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