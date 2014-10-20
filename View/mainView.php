<?php

class MainView
{	
	// String dependencies.
	private $multiplayerGame = "multiplayerGame";
	private $computerGame = "computerGame";
	private $instructions = "instructions";
	private $imageDirectory = "./Images/";
	private $imageFileType = ".png";
	private $instructionsImage = "rpsls";
	
	public function userClickedMultiplayerGame()
	{
		return isset($_GET[$this->multiplayerGame]);
	}
	
	public function userClickedComputerGame()
	{
		return isset($_GET[$this->computerGame]);
	}
	
	public function userClickedInstructions()
	{
		return isset($_GET[$this->instructions]);
	}
	
	public function showMain()
	{		
		// Returnstring.
		$ret = "<h1>Rock, Paper, Scissors, Lizard, Spock!</h1>
				<h2>A PHP-game by Emil Dannberger</h2>
				<h3><a href=?$this->multiplayerGame>Player vs. Player</a></h3>
				<h3><a href=?$this->computerGame>Player vs. Computer</a></h3>
				<h3><a href=?$this->instructions>Instructions</a></h3>";
		
		return $ret;
	}
	
	// Gets the instructions HTML.
	public function showInstructions()
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
						<div><img src='" . $this->imageDirectory . $this->instructionsImage . $this->imageFileType . "' alt='Image of the instructions'></div>";
		
		return $instructionsHTML;
	}
}

?>