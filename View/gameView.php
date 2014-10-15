<?php

class GameView
{
	private $handImages = array();
	
	// String dependencies.
	private $play = "play";
	private $instructions = "instructions";
	private $imageDirectory = "./Images/";
	private $imageFileType = ".png";
	private $instructionsImage = "rpsls";
	private $rockImage = "rock";
	private $paperImage = "paper";
	private $scissorsImage = "scissors";
	private $lizardImage = "lizard";
	private $spockImage = "spock";
	private $postSuffixX = "_x";
	private $postSuffixY = "_y";
	
	public function __construct()
	{
		$this->handImages = array($this->rockImage, $this->paperImage, $this->scissorsImage, $this->lizardImage, $this->spockImage);
	}
	
	public function userClickedPlay()
	{
		if(isset($_GET[$this->play]))
		{
			return TRUE;
		}
	}
	
	public function userClickedInstructions()
	{
		if(isset($_GET[$this->instructions]))
		{
			return TRUE;
		}
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
			$contents =	"<div><a href=?$this->play>Play game</a></div>
					<div><a href=?$this->instructions>Instructions</a></div>";
		}
			
		// Returnstring.
		$ret = "<h1>Rock, Paper, Scissors, Lizard, Spock!</h1>
				<h2>A PHP-game by Emil Dannberger</h2>
				$contents";
		
		return $ret;
	}
	
	// Gets the play HTML.
	public function getPlayHTML()
	{
		$playHTML = "<h3>CHOOSE A HAND!</h3>";
				
		foreach($this->handImages as $hand)
		{	
			$playHTML .= "<form METHOD='post' action=''>
							<input name='$hand' type='image' src='" . $this->imageDirectory . $hand . $this->imageFileType . "' alt='Submit Form, image of the $hand hand' />
						</form>";
		}
				
		$playHTML .= "<div><a href=?>Return</a></div>";
		
		return $playHTML;
	}
	
	// Gets the instructions HTML.
	public function getInstructionsHTML()
	{	
		$instructionsHTML = "<h3>INSTRUCTIONS</h3>
						<div><img src='" . $this->imageDirectory . $this->instructionsImage . $this->imageFileType ."' alt='Image of the gamerules'/></div>
						<div><ol>
								<li>Press the 'Play'-button to start a new game.</li>
								<li>Choose the hand you would like to play. Each hand has its strengths and weaknesses, see image above.</li>
								<li>Your opponent then chooses a hand to play against you, not knowing what you picked.</li>
								<li>The battle takes place! Both hands are revealed and the result is calculated.</li>
								<li>The winner is announced!</li>
								<li>Play again or return to the start page.</li>
							</ol></div>
						<div><a href=?>Return</a></div>";
		
		return $instructionsHTML;
	}
}

?>