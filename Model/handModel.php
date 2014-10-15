<?php

class HandModel
{
	private $handType;
	private $strengths;
	private $weaknesses;
	
	// String dependencies.
	private $rock = "rock";
	private $paper = "paper";
	private $scissors = "scissors";
	private $lizard = "lizard";
	private $spock = "spock";
	
	public function __construct($chosenHand = NULL, $isPlayer = TRUE)
	{
		// Checks if the hand created is a playerhand.
		if($chosenHand != NULL && $isPlayer === TRUE)
		{
			// Sets the attributes for the players hand.
			$this->setAttributes($chosenHand);
		}
		
		// Checks if the hand created is a computerhand.
		if($chosenHand == NULL && $isPlayer === FALSE)
		{
			// Creates a hand for the computer.
			$computerHand = $this->generateComputerHand();
			$this->setAttributes($computerHand);
		}
	}
	
	// Sets the handtype, strenghts & weaknesses.
	private function setAttributes($chosenHand)
	{
		if($chosenHand == NULL || $chosenHand == '')
		{
			throw new Exception("An error occured while creating a new hand object");
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
	
	// Generates a random hand for the computer.
	public function generateComputerHand()
	{
		$computerHand = "";
		
		// Creates an array of the different handtypes.
		$handTypes = array($this->rock, $this->paper, $this->scissors, $this->lizard, $this->spock);
		$randomType = array_rand($handTypes);
		
		// Assigns the random type to the computer hand.
		$computerHand = $handTypes[$randomType];
		
		return $computerHand;
	}
	
	// Compares the strenghts and weaknesses between two hands.
	public function compareHands(HandModel $firstHand, HandModel $secondHand)
	{
		if($firstHand == NULL || $secondHand == NULL)
		{
			throw new Exception("An error occured while trying to compare hands!");
		}
				
		// If the secondHand's type is a firstHand strength...
		if(in_array($secondHand->getHandType(), $firstHand->getStrengths()))
		{
			// ...first hand won.
			return TRUE;
		}
		// If the secondHand's type is a firstHand weakness...
		else if(in_array($secondHand->getHandType(), $firstHand->getWeaknesses()))
		{
			// ...first hand lost.
			return FALSE;
		}
		else
		{
			// It's a draw!
			return NULL;
		}
	}
}

?>