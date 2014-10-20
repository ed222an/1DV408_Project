<?php

require_once("./model/handTypes.php");

class URLList
{
	private $textFileName = "uniqueURLList.txt";
	private $handTypes = array();
	
	public function __construct()
	{
		$this->handTypes = array(HandTypes::rock, HandTypes::paper, HandTypes::scissors, HandTypes::lizard, HandTypes::spock);
	}
	
	// Generates a unique url.
	public function generateUniqueURL($playername, $handType)
	{
		$this->validatePlayername($playername);
		$this->validateHandType($handType);
		
		$uniqueURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/" . $playername . "/" . $handType . "/" . md5(uniqid(rand(), true));
		
		return $uniqueURL;
	}
	
	// Validates the player's name.
	private function validatePlayername($playername)
	{
		if(!preg_match('/^[A-Za-z][A-Za-z0-9]{2,31}$/', $playername))
		{
			throw new Exception("Chosen name is not valid. Must contain at least 3 characters, starting with a letter. Only use letters and numbers!");
		}
	}
	
	// Validates the player's handtype.
	private function validateHandType($handType)
	{
		if(!in_array($handType, $this->handTypes))
		{
			throw new Exception("Handtype not valid.");
		}
	}
	
	// Returns true if the url exists in the file.
	public function urlExists($urlToCheck)
	{
		// Controls if the file exists.
		if($this->checkForFile($this->textFileName))
		{
			// Explodes the file contents at each rowbreak.
			$file = file_get_contents($this->textFileName);
			$result = explode(PHP_EOL, $file);
			
			foreach($result as $url)
			{
				// Checks if the url in the file is equal to the url-parameter.
				if($url == $urlToCheck)
				{
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
	
	// Search for a file with given name.
	private function checkForFile($fileName)
	{
		if(file_exists($fileName) === TRUE)
		{
			return TRUE;
		}
		
		return FALSE;	
	}
	
	// Saves the new url to a file.
	public function saveURLToFile($uniqueURL)
	{
		$stringToSave = $uniqueURL . PHP_EOL;

		// If the file doesn't exists, create it and fill it with the new contents.
		if($this->checkForFile($this->textFileName) === FALSE)
		{
			$this->createNewFile($stringToSave, $this->textFileName);
		}
		else
		{	
			// Get file contents.
			$current = file_get_contents($this->textFileName);
			
			// Append new contents to file.
			$current .= $stringToSave;
			file_put_contents($this->textFileName, $current);
		}
	}
	
	// Create a new file with parameters as contents & name.
	private function createNewFile($newContent, $fileName)
	{
		// Skapar och öppnar en fil.
		$file = fopen($fileName, "w") or die("Unable to open file!");
		
		fwrite($file, $newContent);
		
		// Stänger filen.
		fclose($file);
	}
}

?>