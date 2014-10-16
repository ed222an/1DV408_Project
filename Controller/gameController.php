<?php
	
require_once("./view/gameView.php");
require_once("./model/handModel.php");

/*
 * TODO: PRESENT RESULT & OPTIONS IN THE VIEWCLASS.
 * TODO: SAVE SCORE.
 * TODO: ERROR HANDLING (TRY CATCH).
*/

class GameController
{
	private $gameView;
	
	public function __construct()
	{
		$this->gameView = new GameView();
	}
	
	public function doGameControl()
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
				$resultHTML = $this->gameView->getResultHTML($outcome, $playerHand->getHandType(), $computerHand->getHandType());
				
				// Adds the players current score to the resultHTML.
				$resultHTML .= $this->gameView->getPlayerScoreHTML($playerHand);
				
				// Show the resultpage.
				return $this->gameView->showContents($resultHTML);
			}
			catch(Exception $e)
			{
				$this->gameView->addMessage($e->getMessage());
				return $this->gameView->showContents();
			}
		}
		
		// If the user clicked the play button...
		if($this->gameView->userClickedPlay())
		{
			// Return the play-HTML.
			return $this->gameView->showContents($this->gameView->getPlayHTML());
		}
		
		// If the user clicked the instructions button...
		if($this->gameView->userClickedInstructions())
		{
			// Return the instructions HTML.
			return $this->gameView->showContents($this->gameView->getInstructionsHTML());
		}
		
		// Return the startmenu per default.
		return $this->gameView->showContents();
	}
}

?>