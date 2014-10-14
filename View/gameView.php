<?php

	class GameView
	{
		private $gameModel;
		private $handImages = array();
		
		// String dependencies.
		private $play = "play";
		private $instructions = "instructions";
		private $instructionsImageDirectory = "./Images/rpsls.jpeg";
		private $rockImageDirectory = "./Images/rock.png";
		private $paperImageDirectory = "./Images/paper.png";
		private $scissorsImageDirectory = "./Images/scissors.png";
		private $lizardImageDirectory = "./Images/lizard.png";
		private $spockImageDirectory = "./Images/spock.png";
		
		public function __construct(GameModel $gameModel)
		{
			$this->gameModel = $gameModel;
			$this->handImages = array($this->rockImageDirectory, $this->paperImageDirectory, $this->scissorsImageDirectory, $this->lizardImageDirectory, $this->spockImageDirectory);
		}
		
		private function userClickedPlay()
		{
			if(isset($_GET[$this->play]))
			{
				return TRUE;
			}
		}
		
		private function userClickedInstructions()
		{
			if(isset($_GET[$this->instructions]))
			{
				return TRUE;
			}
		}
		
		public function showContents()
		{
			// Default contents, shows menu.
			$contents =	"
							<div>
								<a href=?$this->play>Play game</a>
							</div>
							<div>
								<a href=?$this->instructions>Instructions</a>
							</div>
						";
				
			// Checks if user pressed "Play".
			if($this->userClickedPlay())
			{
				// Show the gamepage.
				$contents = "<h3>PLAY THE GAME</h3>";
				
				foreach($this->handImages as $hand)
				{
					$contents .= "<img src='$hand' alt='Image of the $hand hand'/>";
				}
				
				$contents .= "
								<div>
									<a href=?>Return</a>
								</div>
							";
			}
			
			// Checks if user pressed "Instructions".
			if($this->userClickedInstructions())
			{
				// Show the instructions.	
				$contents = "
								<h3>INSTRUCTIONS</h3>
								<div>
									<img src='$this->instructionsImageDirectory' alt='Image of the gamerules'/>
								</div>
								<div>
									<ol>
										<li>Press the 'Play'-button to start a new game.</li>
										<li>Choose the hand you would like to play. Each hand has its strengths and weaknesses, see image above.</li>
										<li>Your opponent then chooses a hand to play against you, not knowing what you picked.</li>
										<li>The battle takes place! Both hands are revealed and the result is calculated.</li>
										<li>The winner is announced!</li>
										<li>Play again or return to the start page.</li>
									</ol>
								</div>
								<div>
									<a href=?>Return</a>
								</div>
							";
			}
				
			// Returnstring.
			$ret = "";
			
			$ret .= "	
						<h1>Rock, Paper, Scissors, Lizard, Spock!</h1>
						<h2>A PHP-game by Emil Dannberger</h2>
						$contents
					";
			
			return $ret;
		}
	}
?>