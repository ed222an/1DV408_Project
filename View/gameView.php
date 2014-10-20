<?php

require_once("./model/handModel.php");
require_once("./model/handTypes.php");

class GameView
{
	private $handImages = array();
	private $messages = array();
	private $gameType = "";
	private $rock = HandTypes::rock;
	private $paper = HandTypes::paper;
	private $scissors = HandTypes::scissors;
	private $lizard = HandTypes::lizard;
	private $spock = HandTypes::spock;
	
	// String dependencies.
	private $multiplayerGame = "multiplayerGame";
	private $playerName = "playerName";
	private $imageDirectory = "./Images/";
	private $imageFileType = ".png";
	private $postSuffixX = "_x";
	private $postSuffixY = "_y";
	
	public function __construct($gameType)
	{
		$this->gameType = $gameType;
		$this->handImages = array($this->rock, $this->paper, $this->scissors, $this->lizard, $this->spock);
	}
	
	public function userChoseHand()
	{
		foreach($this->handImages as $hand)
		{			
			if(isset($_POST[$hand . $this->postSuffixX], $_POST[$hand . $this->postSuffixY]))
			{
				return TRUE;
			}
		}
	}
	
	// Gets the players selected name.
	public function getPlayername()
	{
		if(isset($_POST[$this->playerName]))
		{
			return $_POST[$this->playerName];
		}
	}
	
	// Gets the users chosen hand.
	public function getChosenHand()
	{
		// Checks the gametype.
		if($this->isMultiplayerGame())	
		{
			// Different search if the game is a multiplayer game due to the textfield.
			foreach($this->handImages as $hand)
			{	
				if(array_key_exists($hand . $this->postSuffixX, $_POST) || array_key_exists($hand . $this->postSuffixY, $_POST))
				{
					return $hand;
				}
			}
		}
		else
		{
			// Gets the chosen hand from the $_POST-array and strips it of its _x/_y suffix.
			reset($_POST);
			$hand = key($_POST);
			$hand = str_replace($this->postSuffixX, "", $hand);
			$hand = str_replace($this->postSuffixY, "", $hand);
			
			return $hand;
		}
	}
	
	// Checks the gametype.
	private function isMultiplayerGame()
	{
		if($this->gameType == $this->multiplayerGame)
		{
			return TRUE;
		}
	}
	
	// Shows the computergame page.
	public function showGame($newContents = NULL)
	{
		$gameHTML = "<h1>Rock, Paper, Scissors, Lizard, Spock!</h1>
				<h2>A PHP-game by Emil Dannberger</h2>";
		
		// Prints out eventual messages.		
		foreach($this->messages as $message)
		{
			$gameHTML .= "<p>$message</p>";
		}
		
		// Adds new contents if there are any.
		if($newContents != NULL)
		{
			$gameHTML .= $newContents;
		}
		else
		{			
			$gameHTML .= "<h3>CHOOSE A HAND!</h3><table><tr>
							<form METHOD='post' action=''>";
			
			// Different output for multiplayer game.				
			if($this->isMultiplayerGame())
			{
				$gameHTML .= "<h3><label for='$this->playerName'>Choose your player name: </label></h3><input type='text' name='$this->playerName' value=''/>";
			}
			
			foreach($this->handImages as $hand)
			{	
				$gameHTML .= "<td><input name='$hand' type='image' src='" . $this->imageDirectory . $hand . $this->imageFileType . "' alt='Submit Form, image of the $hand hand' /></td>";
			}
		}
				
		$gameHTML .= "</form></tr></table><h3><a href=?>Return</a></h3>";
		
		return $gameHTML;
	}
	
	// Returns the players score.
	public function getPlayerScore(HandModel $playerHand)
	{
		return "<h3>Wins: " . $playerHand->getWins() . " Losses: " . $playerHand->getLosses() . "</h3>";
	}

	public function getResult($outcome, $handType1, $handType2)
	{
		
		$battleText = $this->generateImageTag($handType1) . " VS. " . $this->generateImageTag($handType2) . "<br/><h2>" . $this->getBattleText($outcome, $handType1, $handType2) . "!</h2>";
		
		switch($outcome)
		{		
			case 1:
				// Present player as winner.
				$ret = "<h2>Player won!</h2>$battleText";
				break;
				
			case 2:
				// Present player as looser.
				$ret = "<h2>Player lost!</h2>$battleText";
				break;
				
			case 3:
				// Present draw.
				$ret = "<h2>Draw!</h2>$battleText";
				break;
		}
		
		$ret .= "<h3><a href=?$this->gameType>Play again</a></h3>";
		
		return $ret;
	}
	
	// Generates an specific image of the requested imagename.
	private function generateImageTag($imageName)
	{
		return "<img src='" . $this->imageDirectory . $imageName . $this->imageFileType . "' alt='Image of $imageName'>";
	}
	
	// Generates battletext depending on which hands do battle.
	private function getBattleText($outcome, $handType1, $handType2)
	{
		// If its a draw...	
		if($outcome == 3)
		{
			// ...if two spocks duel, present a special text.
			if($handType1 == $this->spock)
			{
				return "Two " . ucfirst($this->spock) . "s?! How is that even possible?";
			}
			
			// Return generic draw battletext as default.
			return ucfirst($handType1) . " can't do sh*t against another " . ucfirst($handType2);
		}
		
		// Rock outcomes.
		if($handType1 == $this->rock)
		{
			if($handType2 == $this->lizard || $handType2 == $this->scissors)
			{
				return ucfirst($handType1)." crushes " . ucfirst($handType2);
			}
			if($handType2 == $this->paper)
			{
				return ucfirst($handType2)." covers " . ucfirst($handType1);
			}
			if($handType2 == $this->spock)
			{
				return ucfirst($handType2)." vaporizes " . ucfirst($handType1);
			}
		}
		
		// Paper outcomes.
		if($handType1 == $this->paper)
		{
			if($handType2 == $this->rock)
			{
				return ucfirst($handType1)." covers " . ucfirst($handType2);
			}
			if($handType2 == $this->spock)
			{
				return ucfirst($handType1)." disproves " . ucfirst($handType2);
			}
			if($handType2 == $this->lizard)
			{
				return ucfirst($handType2)." eats " . ucfirst($handType1);
			}
			if($handType2 == $this->scissors)
			{
				return ucfirst($handType2)." cuts " . ucfirst($handType1);
			}
		}
		
		// Scissors outcomes.
		if($handType1 == $this->scissors)
		{
			if($handType2 == $this->paper)
			{
				return ucfirst($handType1)." cuts " . ucfirst($handType2);
			}
			if($handType2 == $this->lizard)
			{
				return ucfirst($handType1)." decapitates " . ucfirst($handType2);
			}
			if($handType2 == $this->spock)
			{
				return ucfirst($handType2)." smashes " . ucfirst($handType1);
			}
			if($handType2 == $this->rock)
			{
				return ucfirst($handType2)." crushes " . ucfirst($handType1);
			}
		}
		
		// Lizard outcomes.
		if($handType1 == $this->lizard)
		{
			if($handType2 == $this->spock)
			{
				return ucfirst($handType1)." poisons " . ucfirst($handType2);
			}
			if($handType2 == $this->paper)
			{
				return ucfirst($handType1)." eats " . ucfirst($handType2);
			}
			if($handType2 == $this->rock)
			{
				return ucfirst($handType2)." crushes " . ucfirst($handType1);
			}
			if($handType2 == $this->scissors)
			{
				return ucfirst($handType2)." decapitates " . ucfirst($handType1);
			}
		}
		
		// Spock outcomes.
		if($handType1 == $this->spock)
		{
			if($handType2 == $this->scissors)
			{
				return ucfirst($handType1)." smashes " . ucfirst($handType2);
			}
			if($handType2 == $this->rock)
			{
				return ucfirst($handType1)." vaporizes " . ucfirst($handType2);
			}
			if($handType2 == $this->lizard)
			{
				return ucfirst($handType2)." poisons " . ucfirst($handType1);
			}
			if($handType2 == $this->paper)
			{
				return ucfirst($handType2)." disproves " . ucfirst($handType1);
			}
		}
	}

	// Adds message to the messages-array.
	public function addMessage($message)
	{
		array_push($this->messages, $message);
	}
}

?>