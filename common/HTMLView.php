<?php
	
	class HTMLView
	{
		// Strängberoende, använd samma ord som startsidan av applikationen.
		private $startURL = "Start";
		
		public function echoHTML($body)
		{
			if($body == NULL)
			{
				$body = "An unknown error has occured!<br />
				<a href='?$this->$startURL'>Click here to return to start page</a>";
			}
			
			echo "
				<!DOCTYPE html>
				<html>
					<head>
						<title>Rock, Paper, Scissors, Lizard, Spock - A PHP game by Emil Dannberger</title>
						<meta charset='utf-8'>
						<link href='common/css/style.css' rel='stylesheet'>
					</head>
					<body>
						$body
					</body>
				</html>";
		}
	}
?>