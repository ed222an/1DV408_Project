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
			// Get the name of that hand and creates a playerHand object.
			$chosenHand = $this->gameView->getChosenHand();
			$playerHand = new HandModel($chosenHand);
			
			// Creates a randomly generated hand for the computer.
			$computerHand = new HandModel(NULL, FALSE);
			
			// Compares the hands and saves the outcome.
			$outcome = $playerHand->compareHands($playerHand, $computerHand);
			
			switch($outcome)
			{
				case TRUE:
					// Present player as winner.
					break;
					
				case FALSE:
					// Present player as looser.
					break;
					
				case NULL:
					// Present draw.
					break;
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