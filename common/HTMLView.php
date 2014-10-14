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
						<meta charset='utf-8'>
					</head>
					<body>
						$body
					</body>
				</html>";
		}
	}
?>