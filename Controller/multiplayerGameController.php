<?php
	
require_once("./view/gameView.php");
require_once("./model/dataHandler.php");
require_once("./model/handModel.php");

class MultiplayerGameController
{
	private $gameView;
	private $dataHandler;
	private $isPlayerOne;
	private $isPlayerTwo;
	private $actualURL;
	
	// String dependencies.
	private $unresolved = "unresolved";
	private $resolved = "resolved";
	
	public function __construct($gameType, $actualURL, $isPlayerTwo = FALSE)
	{
		$this->gameView = new GameView($gameType);
		$this->dataHandler = new DataHandler($actualURL, $gameType);
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
		// Check if the user has a game currently unresolved.
		if($this->dataHandler->sessionExists())
		{
			// Gets the playername from the session.
			$playername = $this->dataHandler->getSessionPlayername();
			
			// Gets the status of the existing game from the gamefile.
			$statusFromFile = $this->dataHandler->handleData($playername, $getHandType = FALSE, $getPlayernameAndStatus = TRUE);
			
			// Shows the Unresolved-page.
			if($statusFromFile == $this->unresolved)
			{
				return $this->gameView->showUnresolved();
			}
			
			// Shows the resolved-page.
			if($statusFromFile == $this->resolved)
			{
				// Gets an array from the file, containing the first & second players hands & second players name.
				$components = $this->dataHandler->manageComponentsFromFile($playername);
				
				// Creates new objects from the components.
				$playerOneHand = new HandModel($components[0]);
				$playerTwoHand = new HandModel($components[1]);
				
				// Sets the playername for the new objects.
				$playerOneHand->setPlayerName($playername);
				$playerTwoHand->setPlayerName($components[2]);
				
				// Compares the hands and saves the outcome.
				// Will be 1 if player won, 2 if player lost or 3 if its a draw.
				$outcome = $playerOneHand->compareHands($playerTwoHand);
				
				// Get the result HTML.
				$resultHTML = $this->gameView->getResult($outcome, $playerOneHand, $playerTwoHand);
				
				// Adds the players current score to the resultHTML.
				$resultHTML .= $this->gameView->getPlayerScore($playerOneHand);
				
				// Removes the playername-session.
				$this->dataHandler->removeSessionPlayername();
				
				// Removes the challenge from the textfile.
				$this->dataHandler->manageComponentsFromFile($playername, $components[0], $components[1], $components[2]);
				
				// Show the resultpage.
				return $this->gameView->showGame($resultHTML);
			}
		}
			
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
				$this->dataHandler->validatePlayerInput($playerOneName);
				
				// Generate a new URL for the player to send to his/her opponent.
				$uniqueURL = $this->dataHandler->generateUniqueURL($playerOneName);
				
				// Add status to URL.
				$uniqueURL = $this->dataHandler->addUnresolvedToURL($uniqueURL);
				
				// Append the handType to the data.
				$dataToSave = $this->dataHandler->appendToData($uniqueURL, $playerOneHand->getHandType());
				
				// Save the URL to textfile.
				$this->dataHandler->saveDataToFile($dataToSave);
				
				// Sets the session variables.
				$this->dataHandler->setSessionPlayername($playerOneName);
				
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
					$playerTwoHandType = $playerTwoHand->getHandType();
					
					// Get the players selected username.
					$playerTwoName = $this->gameView->getPlayername();
					$playerTwoHand->setPlayerName($playerTwoName);
					
					// Validate player name.
					$this->dataHandler->validatePlayerInput($playerTwoName);
					
					//Gets the first player's hand from the data file.
					$handFromFile = $this->dataHandler->handleData($this->actualURL, $getHandType = TRUE, $getPlayernameAndStatus = FALSE);
					
					// Creates a new hand-object based on the filedata.
					$playerOneHand = new HandModel($handFromFile);
					
					// Sets the playername for the new object.
					$playerOneName = $this->dataHandler->getNameFromURL();
					$playerOneHand->setPlayerName($playerOneName);
					
					// Compares the hands and saves the outcome.
					// Will be 1 if player won, 2 if player lost or 3 if its a draw.
					$outcome = $playerTwoHand->compareHands($playerOneHand, $playerTwoHand);
					
					// Get the result HTML.
					$resultHTML = $this->gameView->getResult($outcome, $playerTwoHand, $playerOneHand);
					
					// Adds the players current score to the resultHTML.
					$resultHTML .= $this->gameView->getPlayerScore($playerTwoHand);
					
					// Changes the URL in the file to resolved, adds second player hand & name.
					$dataFromFile = $this->dataHandler->handleData($this->actualURL, $getHandType = TRUE, $getPlayernameAndStatus = FALSE, $getRowData = TRUE);
					$this->dataHandler->saveDataToFile($dataFromFile, $playerTwoHandType, $playerTwoName);
					
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