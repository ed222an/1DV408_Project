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
	private $continueMultiplayerGame = "continueMultiplayerGame";
	private $playerName = "playerName";
	private $imageDirectory = "./Images/";
	private $imageFileType = ".png";
	private $postSuffixX = "_x";
	private $postSuffixY = "_y";
	
	public function __construct($gameType)
	{
		if(!isset($gameType))
		{
			throw new Exception("Gametype is NULL.");
		}
		
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
		if($this->isGameType($this->multiplayerGame) || $this->isGameType($this->continueMultiplayerGame))	
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
	private function isGameType($gameType)
	{
		if($this->gameType == $gameType)
		{
			return TRUE;
		}
	}
	
	// Shows the computergame page.
	public function showGame($newContents = NULL)
	{
		$gameHTML = $this->getHeaderHTML();
		
		// Prints out eventual messages.		
		foreach($this->messages as $message)
		{
			$gameHTML .= "<div id='message'><p>$message</p></div>";
		}
		
		// Adds new contents if there are any.
		if($newContents != NULL)
		{
			$gameHTML .= $newContents;
		}
		else
		{			
			$gameHTML .= "<h3>CHOOSE A HAND!</h3>
							<form METHOD='post'>";
			
			// Different output for multiplayer & continueMultiplayer.				
			if($this->isGameType($this->continueMultiplayerGame))
			{
				$gameHTML .= "<div id='opponentChose'><h3>Your opponent has chosen! Now it's your time!</h3></div>";
			}
			
			if($this->isGameType($this->multiplayerGame) || $this->isGameType($this->continueMultiplayerGame))
			{
				$gameHTML .= "<div id='choosePlayername'><h3>Choose your player name: </h3></div><input class='textbox' type='text' name='$this->playerName' value=''/>";
			}
			
			foreach($this->handImages as $hand)
			{	
				$gameHTML .= "<input name='$hand' type='image' src='" . $this->imageDirectory . $hand . $this->imageFileType . "' alt='Submit Form, image of the $hand hand' />";
			}
		}
				
		$gameHTML .= $this->getFooterHTML();
		
		return $gameHTML;
	}

	// Shows the unresolved page.
	public function showUnresolved()
	{	
		return $this->getHeaderHTML() . "<div id='unresolvedTop'><h3>You still have an unresolved game, try reloading the page when your opponent has chosen both name and hand.</h3></div>
										<div id='unresolvedBottom'><h4>If your opponent isn't responding, restart your browser to enable another challenge.</h4></div>" . $this->getFooterHTML();	
	}
	
	// Gets the page header.
	private function getHeaderHTML()
	{
		return "<h1>Rock, Paper, Scissors, Lizard, Spock!</h1><h2>A PHP-game by Emil Dannberger</h2>";
	}
	
	private function getFooterHTML()
	{
		return "</form><div id='footer'><h3><a href=?>Return</a></h3></div>";
	}
	
	// Returns the HTML for multiplayer URL.
	public function getURLHTML($uniqueURL)
	{
		return "<div id='sendText'><h3>Send this URL to your opponent:</h3>
				<div id='url'><h4>$uniqueURL</h4></div>
				<h3>Reload the page once you know he/she made her selections!</h3></div>";
	}
	
	// Returns the players score.
	public function getPlayerScore(HandModel $playerHand)
	{
		return "<h3>Wins: " . $playerHand->getWins() . " Losses: " . $playerHand->getLosses() . "</h3>";
	}

	public function getResult($outcome, HandModel $hand1, HandModel $hand2)
	{
		// Get each object's handtype.
		$handType1 = $hand1->getHandType();
		$handType2 = $hand2->getHandType();
		
		// Default names for the players.
		$playername = "Player";
		$otherPlayername = "Computer";
		
		// If the game is a multiplayergame, get the players chosen names.
		if($this->gameType == $this->continueMultiplayerGame || $this->gameType == $this->multiplayerGame)
		{
			$playername = $hand1->getPlayerName();
			$otherPlayername = $hand2->getPlayerName();
		}
		
		$battleText = "<div id='battleImages'>" .$this->generateImageTag($handType1) . " VS. " . $this->generateImageTag($handType2) . "</div><br/><div id='battleText'><h2>" . $this->getBattleText($outcome, $handType1, $handType2) . "!</div></h2>";
		
		$ret = "<div id='outcome'>";
		
		switch($outcome)
		{		
			case 1:
				// Present player as winner.
				$ret .= "<h2>$playername won vs. $otherPlayername!</h2>$battleText";
				break;
				
			case 2:
				// Present player as looser.
				$ret .= "<h2>$playername lost vs. $otherPlayername!</h2>$battleText";
				break;
				
			case 3:
				// Present draw.
				$ret .= "<div id='draw'><h2>Draw!</h2></div>$battleText";
				break;
		}
		
		// Play Again-button.
		$ret .= "</div><div id='playAgain'><h3><a href=?$this->gameType>Play again</a></h3></div>";
		
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
		// Stringvariables.
		$crushes = " crushes ";
		$covers = " covers ";
		$cuts = " cuts ";
		$eats = " eats ";
		$decapitates = " decapitates ";
		$poisons = " poisons ";
		$smashes = " smashes ";
		$vaporizes = " vaporizes ";
		$disproves = " disproves ";
		
		// If its a draw...	
		if($outcome == 3)
		{
			// ...if two Spocks duel, present a special text.
			if($handType1 == $this->spock)
			{
				return "Two " . ucfirst($this->spock) . "s?! How is that even possible?";
			}
			
			// Return generic draw battletext as default.
			return $this->generateBattleTextContent($handType1, " can't do sh*t against another ", $handType2);
		}
		
		// Rock outcomes.
		if($handType1 == $this->rock)
		{
			if($handType2 == $this->lizard || $handType2 == $this->scissors)
			{
				return $this->generateBattleTextContent($handType1, $crushes, $handType2);
			}
			if($handType2 == $this->paper)
			{
				return $this->generateBattleTextContent($handType2, $covers, $handType1);
			}
			if($handType2 == $this->spock)
			{
				return $this->generateBattleTextContent($handType2, $vaporizes, $handType1);
			}
		}
		
		// Paper outcomes.
		if($handType1 == $this->paper)
		{
			if($handType2 == $this->rock)
			{
				return $this->generateBattleTextContent($handType1, $covers, $handType2);
			}
			if($handType2 == $this->spock)
			{
				return $this->generateBattleTextContent($handType1, $disproves, $handType2);
			}
			if($handType2 == $this->lizard)
			{
				return $this->generateBattleTextContent($handType2, $eats, $handType1);
			}
			if($handType2 == $this->scissors)
			{
				return $this->generateBattleTextContent($handType2, $cuts, $handType1);
			}
		}
		
		// Scissors outcomes.
		if($handType1 == $this->scissors)
		{
			if($handType2 == $this->paper)
			{
				return $this->generateBattleTextContent($handType1, $cuts, $handType2);
			}
			if($handType2 == $this->lizard)
			{
				return $this->generateBattleTextContent($handType1, $decapitates, $handType2);
			}
			if($handType2 == $this->spock)
			{
				return $this->generateBattleTextContent($handType2, $smashes, $handType1);
			}
			if($handType2 == $this->rock)
			{
				return $this->generateBattleTextContent($handType2, $crushes, $handType1);
			}
		}
		
		// Lizard outcomes.
		if($handType1 == $this->lizard)
		{
			if($handType2 == $this->spock)
			{
				return $this->generateBattleTextContent($handType1, $poisons, $handType2);
			}
			if($handType2 == $this->paper)
			{
				return $this->generateBattleTextContent($handType1, $eats, $handType2);
			}
			if($handType2 == $this->rock)
			{
				return $this->generateBattleTextContent($handType2, $crushes, $handType1);
			}
			if($handType2 == $this->scissors)
			{
				return $this->generateBattleTextContent($handType2, $decapitates, $handType1);
			}
		}
		
		// Spock outcomes.
		if($handType1 == $this->spock)
		{
			if($handType2 == $this->scissors)
			{
				return $this->generateBattleTextContent($handType1, $smashes, $handType2);
			}
			if($handType2 == $this->rock)
			{
				return $this->generateBattleTextContent($handType1, $vaporizes, $handType2);
			}
			if($handType2 == $this->lizard)
			{
				return $this->generateBattleTextContent($handType2, $poisons, $handType1);
			}
			if($handType2 == $this->paper)
			{
				return $this->generateBattleTextContent($handType2, $disproves, $handType1);
			}
		}
	}

	private function generateBattleTextContent($handType1, $contentString, $handType2)
	{
		return ucfirst($handType1) . $contentString . ucfirst($handType2);
	}

	// Adds message to the messages-array.
	public function addMessage($message)
	{
		array_push($this->messages, $message);
	}
}

?>