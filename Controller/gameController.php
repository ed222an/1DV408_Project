<?php
	
	require_once("./view/gameView.php");
	require_once("./model/gameModel.php");
	
	/* TODO: CREATE A MODEL OBJECT WITH THE CHOSEN HAND.
	 * TODO: CREATE GAMERULES FOR THE HANDS.
	 * TODO: CREATE RANDOMIZED HAND FOR THE COMPUTER.
	 * TODO: USER VS. COMPUTER, CALCULATE RESULT.
	 * TODO: PRESENT RESULT & OPTIONS.
	 * TODO: SAVE SCORE.
 	*/
	
	class GameController
	{
		private $gameView;
		private $gameModel;
		
		public function __construct()
		{
			$this->gameModel = new GameModel();
			$this->gameView = new GameView($this->gameModel);
		}
		
		public function doGameControl()
		{
			// If user chose a hand...
			if($this->gameView->userChoseHand())
			{
				// Get the name of that hand and create a model object of it.
				$chosenHand = $this->gameView->getChosenHand();
				var_dump($chosenHand);
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