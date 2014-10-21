<?php

require_once("model/handTypes.php");

class HandModel
{
	private $isPlayer;
	private $playername;
	private $handType;
	private $strengths;
	private $weaknesses;
	private $wins;
	private $losses;
	private $rock = HandTypes::rock;
	private $paper = HandTypes::paper;
	private $scissors = HandTypes::scissors;
	private $lizard = HandTypes::lizard;
	private $spock = HandTypes::spock;
	
	// String dependencies.
	private $winsSessionString = "wins";
	private $lossesSessionString = "losses";
	
	public function __construct($chosenHand = NULL, $isPlayer = TRUE)
	{
		// Checks if the hand created is a playerhand.
		if($chosenHand != NULL && $isPlayer === TRUE)
		{
			// The object is a player.
			$this->isPlayer = TRUE;
			
			// Sets the attributes for the players hand.
			$this->setAttributes($chosenHand);
			
			// Creates a score variable in the session if the new object is a player.
			if($this->winsSessionExists() === FALSE)
			{
				$_SESSION[$this->winsSessionString] = 0;
			}
			
			if($this->lossesSessionExists() === FALSE)
			{
				$_SESSION[$this->lossesSessionString] = 0;
			}
			
			// Adds the session score-values to the player's win / score variables.
			$this->wins = $_SESSION[$this->winsSessionString];
			$this->losses = $_SESSION[$this->lossesSessionString];
		}
		
		// Checks if the hand created is a computerhand.
		if($chosenHand == NULL && $isPlayer === FALSE)
		{
			// The object is a computer.
			$this->isPlayer = FALSE;
			
			// Creates a hand for the computer.
			$computerHand = $this->generateComputerHand();
			$this->setAttributes($computerHand);
		}
	}
	
	// Checks if the wins-Sessionvariable exists.
	private function winsSessionExists()
	{
		return isset($_SESSION[$this->winsSessionString]);
	}
	
	// Checks if the losses-Sessionvariable exists.
	private function lossesSessionExists()
	{
		return isset($_SESSION[$this->lossesSessionString]);
	}
	
	// Sets the playername.
	public function setPlayerName($playername)
	{
		if(!preg_match('/^[A-Za-z][A-Za-z0-9]{2,31}$/', $playername))
		{
			throw new Exception("Chosen name is not valid. Must contain at least 3 characters, starting with a letter. Only use letters and numbers!");
		}
		
		$this->playername = $playername;
	}
	
	// Gets the player name.
	public function getPlayerName()
	{
		if(isset($this->playername) && $this->playername != NULL && $this->playername != "")
		{
			return $this->playername;
		}
		else
		{
			throw new Exception("Error while trying to get playername.");
		}
	}
	
	// Gets the handtype of the object.
	public function getHandType()
	{
		if(isset($this->handType) && $this->handType != NULL && $this->handType != "")
		{
			return $this->handType;
		}
		else
		{
			throw new Exception("Error while trying to get handtype.");
		}
	}
	
	// Gets the strengths of the object.
	public function getStrengths()
	{
		if(!count($this->strengths) <= 0)
		{
			return $this->strengths;
		}
		else
		{
			throw new Exception("Error while trying to get strengths.");
		}
	}
	
	// Gets the weaknesses of the object.
	public function getWeaknesses()
	{
		if(!count($this->weaknesses) <= 0)
		{
			return $this->weaknesses;
		}
		else
		{
			throw new Exception("Error while trying to get weaknesses.");
		}
	}
	
	// Gets the total amount of wins.
	public function getWins()
	{
		if(isset($this->wins))
		{
			return $this->wins;
		}
		else
		{
			throw new Exception("Error while trying to get the wins.");
		}
	}
	
	// Gets the total amount of losses.
	public function getLosses()
	{
		if(isset($this->losses))
		{
			return $this->losses;
		}
		else
		{
			throw new Exception("Error while trying to get the losses.");
		}
	}
	
	// Sets the handtype, strenghts & weaknesses.
	private function setAttributes($chosenHand)
	{
		try
		{
			if($chosenHand == NULL || $chosenHand == '')
			{
				throw new Exception("An error occured while creating a new hand object.");
			}
			
			switch($chosenHand)
			{
				// Create rock.
				case $this->rock:
					$this->handType = $this->rock;
					$this->strengths = array($this->lizard, $this->scissors);
					$this->weaknesses = array($this->paper, $this->spock);
					break;
				
				// Create paper.	
				case $this->paper:
					$this->handType = $this->paper;
					$this->strengths = array($this->rock, $this->spock);
					$this->weaknesses = array($this->scissors, $this->lizard);
					break;
				
				// Create scissors.	
				case $this->scissors:
					$this->handType = $this->scissors;
					$this->strengths = array($this->paper, $this->lizard);
					$this->weaknesses = array($this->rock, $this->spock);
					break;
				
				// Create lizard.
				case $this->lizard:
					$this->handType = $this->lizard;
					$this->strengths = array($this->spock, $this->paper);
					$this->weaknesses = array($this->rock, $this->scissors);
					break;
				
				// Create spock.
				case $this->spock:
					$this->handType = $this->spock;
					$this->strengths = array($this->scissors, $this->rock);
					$this->weaknesses = array($this->lizard, $this->paper);
					break;
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occured while trying to set the hand attributes.");
		}
	}
	
	// Updates the score.
	private function updateScore($win)
	{
		try
		{
			if($win === TRUE)
			{
				// Increments the wins-session variable.
				$_SESSION[$this->winsSessionString]++;
				$this->wins = $_SESSION[$this->winsSessionString];
			}
			elseif($win === FALSE)
			{
				// Increments the losses-session variable.
				$_SESSION[$this->lossesSessionString]++;
				$this->losses = $_SESSION[$this->lossesSessionString];
			}
		}
		catch(Exception $e)
		{
			throw new Exception("Something went wrong while trying to update the score.");
		}
	}
	
	// Generates a random hand for the computer.
	public function generateComputerHand()
	{
		try
		{
			$computerHand = "";
			
			// Creates an array of the different handtypes.
			$handTypes = array($this->rock, $this->paper, $this->scissors, $this->lizard, $this->spock);
			$randomType = array_rand($handTypes);
			
			// Assigns the random type to the computer hand.
			$computerHand = $handTypes[$randomType];
			
			return $computerHand;
		}
		catch(Exception $e)
		{
			throw new Exception("Something went wrong while trying to generate the computer's hand.");
		}
	}
	
	// Compares the strenghts and weaknesses between two hands.
	public function compareHands(HandModel $otherHand)
	{	
		try
		{
			if($otherHand == NULL)
			{
				throw new Exception("An error occured while trying to compare hands.");
			}
					
			// If the secondHand's type is this objects strength...
			if(in_array($otherHand->getHandType(), $this->getStrengths()))
			{
				// Check if object is a player.
				if($this->isPlayer === TRUE)
				{
					$this->updateScore(TRUE);
				}
				
				// ...this object won.
				return 1;
			}
			// If the secondHand's type is this objects weakness...
			else if(in_array($otherHand->getHandType(), $this->getWeaknesses()))
			{
				// Check if object is a player.
				if($this->isPlayer === TRUE)
				{
					$this->updateScore(FALSE);
				}
				
				// ...this object lost.
				return 2;
			}
			else
			{
				// It's a draw!
				return 3;
			}
		}
		catch(Exception $e)
		{
			throw new Exception("Something went wrong while trying to compare the hands.");
		}
	}
}

?>