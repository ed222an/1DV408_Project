<?php

require_once("./model/handTypes.php");

class DataList
{
	private $textFileName = "dataList.txt";
	private $actualURL;
	private $handTypes = array();
	
	public function __construct($actualURL)
	{
		$this->actualURL = $actualURL;
		$this->handTypes = array(HandTypes::rock, HandTypes::paper, HandTypes::scissors, HandTypes::lizard, HandTypes::spock);
	}
	
	public function getNameFromURL()
	{
		$urlArray = explode("=", $this->actualURL);
		$urlArray = explode("/", $urlArray[1]);
		$playername = $urlArray[0];
		
		return $playername;
	}
	
	// Generates a unique url.
	public function generateUniqueURL($playername)
	{
		$modifiedURL = $this->actualURL . "=";
		
		$uniqueURL = str_replace("multiplayerGame", "continueMultiplayerGame", $modifiedURL);
		
		$uniqueURL .= $playername . "/" . md5(uniqid(rand(), true));
		
		return $uniqueURL;
	}
	
	// Appends a string to the existing data.
	public function appendToData($data, $stringToAppend)
	{
		return $data . ";" . $stringToAppend;
	}
	
	// Validates the player's input.
	public function validatePlayerInput($playername)
	{
		if(!preg_match('/^[A-Za-z][A-Za-z0-9]{2,31}$/', $playername))
		{
			throw new Exception("Chosen name is not valid. Must contain at least 3 characters, starting with a letter. Only use letters and numbers!");
		}
	}
	
	// Returns true if the url exists in the file.
	public function dataExists($dataToCheck, $getHandType = FALSE)
	{
		// Controls if the file exists.
		if($this->checkForFile($this->textFileName))
		{
			// Explodes the file contents at each rowbreak.
			$file = file_get_contents($this->textFileName);
			$result = explode(PHP_EOL, $file);
			
			foreach($result as $data)
			{
				// Sparate URL from handtype.
				$urlAndHandType = explode(";", $data);
				
				// Checks for correct URL.
				if($urlAndHandType[0] == $dataToCheck)
				{
					// Gets the handtype.
					if($getHandType === TRUE)
					{
						return $urlAndHandType[1];
					}
					
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
	public function saveDataToFile($data)
	{
		$stringToSave = $data . PHP_EOL;

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