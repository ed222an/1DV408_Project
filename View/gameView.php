<?php

require_once("./model/handModel.php");

class GameView
{
	private $handImages = array();
	private $messages = array();
	
	// String dependencies.
	private $play = "play";
	private $instructions = "instructions";
	private $imageDirectory = "./Images/";
	private $imageFileType = ".png";
	private $instructionsImage = "rpsls";
	private $rock = "rock";
	private $paper = "paper";
	private $scissors = "scissors";
	private $lizard = "lizard";
	private $spock = "spock";
	private $postSuffixX = "_x";
	private $postSuffixY = "_y";
	
	public function __construct()
	{
		$this->handImages = array($this->rock, $this->paper, $this->scissors, $this->lizard, $this->spock);
	}
	
	public function userClickedPlay()
	{
		return isset($_GET[$this->play]);
	}
	
	public function userClickedInstructions()
	{
		return isset($_GET[$this->instructions]);
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
	
	public function getChosenHand()
	{
		// Gets the chosen hand from the $_POST-array and strips it of its _x/_y suffix.
		reset($_POST);
		$chosenHand = key($_POST);
		$chosenHand = str_replace($this->postSuffixX, "", $chosenHand);
		$chosenHand = str_replace($this->postSuffixY, "", $chosenHand);
		
		return $chosenHand;
	}
	
	public function showContents($newContents = NULL)
	{
		// Overrides the default menu if there are other contents to be shown.
		if(isset($newContents) === TRUE && $newContents != "")
		{
			$contents = $newContents;
		}
		else
		{
			// Default contents, shows menu.
			$contents =	"<h3><a href=?$this->play>Play game</a></h3>
					<h3><a href=?$this->instructions>Instructions</a></h3>";
		}
			
		// Returnstring.
		$ret = "<h1>Rock, Paper, Scissors, Lizard, Spock!</h1>
				<h2>A PHP-game by Emil Dannberger</h2>";
				
		// Iterates through the messages-array and adds the messages to the return-string.
		foreach($this->messages as $message)
		{
			$ret .= '<p>' . $message . '</p>';
		}
		
		$ret .= $contents;
		
		return $ret;
	}
	
	// Gets the play HTML.
	public function getPlayHTML()
	{
		$playHTML = "<h3>CHOOSE A HAND!</h3><table><tr>";
				
		foreach($this->handImages as $hand)
		{	
			$playHTML .= "<form METHOD='post' action=''>
							<td><input name='$hand' type='image' src='" . $this->imageDirectory . $hand . $this->imageFileType . "' alt='Submit Form, image of the $hand hand' /></td>
						</form>";
		}
				
		$playHTML .= "</tr></table><h3><a href=?>Return</a></h3>";
		
		return $playHTML;
	}
	
	// Gets the instructions HTML.
	public function getInstructionsHTML()
	{	
		$instructionsHTML = "<h3>INSTRUCTIONS</h3>
						<div><ol>
								<li>Press the 'Play'-button to start a new game.</li>
								<li>Choose the hand you would like to play. Each hand has its strengths and weaknesses, see image below.</li>
								<li>Your opponent then chooses a hand to play against you, not knowing what you picked.</li>
								<li>The battle takes place! Both hands are revealed and the result is calculated.</li>
								<li>The winner is announced!</li>
								<li>Play again or return to the start page.</li>
							</ol></div>
						<h3><a href=?>Return</a></h3>
						<div>" . $this->generateImageTag($this->instructionsImage) . "</div>";
		
		return $instructionsHTML;
	}
	
	// Returns the players score.
	public function getPlayerScoreHTML(HandModel $playerHand)
	{
		return "<h3>Wins: " . $playerHand->getWins() . " Losses: " . $playerHand->getLosses() . "</h3>";
	}

	public function getResultHTML($outcome, $handType1, $handType2)
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
		
		$ret .= "<h3><a href=?$this->play>Play again</a> <a href=?>Return</a></h3>";
		
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