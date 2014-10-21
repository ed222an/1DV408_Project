<?php

require_once("./model/handTypes.php");

class DataList
{
	private $textFileName = "dataList.txt";
	private $handTypes = array();
	
	public function __construct()
	{
		$this->handTypes = array(HandTypes::rock, HandTypes::paper, HandTypes::scissors, HandTypes::lizard, HandTypes::spock);
	}
	
	// Generates a unique url.
	public function generateUniqueURL($playername)
	{
		$actualURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]=";
		
		// FIXA STRÄNGBEROENDE.	
		$uniqueURL = str_replace("multiplayerGame", "continueMultiplayerGame", $actualURL);
		
		$uniqueURL .= $playername . "/" . md5(uniqid(rand(), true));
		
		return $uniqueURL;
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
	public function dataExists($dataToCheck)
	{
		// Controls if the file exists.
		if($this->checkForFile($this->textFileName))
		{
			// Explodes the file contents at each rowbreak.
			$file = file_get_contents($this->textFileName);
			$result = explode(PHP_EOL, $file);
			
			foreach($result as $data)
			{
				// Checks if the data in the file is equal to the dataToCheck-parameter.
				if($data == $dataToCheck)
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