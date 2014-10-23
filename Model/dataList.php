<?php

require_once("./model/handTypes.php");

class DataList
{
	private $textFileName = "dataList.txt";
	private $actualURL;
	private $handTypes = array();
	private $gameType;
	
	// String dependencies
	private $continueMultiplayerGame = "continueMultiplayerGame";
	private $unresolved = "unresolved";
	private $resolved = "resolved";
	private $playernameSession = "playername";
	
	public function __construct($actualURL, $gameType)
	{
		$this->validateParam($actualURL);
		$this->validateParam($gameType);
		
		$this->actualURL = $actualURL;
		$this->gameType = $gameType;
		$this->handTypes = array(HandTypes::rock, HandTypes::paper, HandTypes::scissors, HandTypes::lizard, HandTypes::spock);
	}
	
	// Validation of parameter.
	private function validateParam($param)
	{
		if(!isset($param))
		{
			throw new Exception("Something went wrong when trying to access the datalist.");
		}
	}
	
	// Validates the player's input.
	public function validatePlayerInput($playername)
	{
		if(!preg_match('/^[A-Za-z][A-Za-z0-9]{2,31}$/', $playername))
		{
			throw new Exception("Chosen name is not valid. Must contain at least 3 characters, starting with a letter. Only use letters and numbers!");
		}
	}
	
	// Checks for the playername-sessionvariable.
	public function sessionExists()
	{
		return isset($_SESSION[$this->playernameSession]);
	}
	
	// Sets the playername in the session.
	public function setSessionPlayername($playername)
	{
		$_SESSION[$this->playernameSession] = $playername;
	}
	
	public function getSessionPlayername()
	{
		return $_SESSION[$this->playernameSession];
	}
	
	public function removeSessionPlayername()
	{
		unset($_SESSION[$this->playernameSession]);
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
		
		$uniqueURL = str_replace($this->gameType, $this->continueMultiplayerGame, $modifiedURL);
		
		$uniqueURL .= $playername . "/" . md5(uniqid(rand(), true));
		
		return $uniqueURL;
	}
	
	// Adds unresolved to the url.
	public function addUnresolvedToURL($uniqueURL)
	{
		$uniqueURL .= "=" . $this->unresolved;
		
		return $uniqueURL;
	}
	
	// Adds resolved to the url.
	private function addResolvedToURL($uniqueURL)
	{
		if(strpos($uniqueURL, $this->unresolved) !== FALSE)
		{
			$uniqueURL = str_replace($this->unresolved, $this->resolved, $uniqueURL);
		}
		else
		{
			$uniqueURL .= $this->resolved;
		}
		
		return $uniqueURL;
	}
	
	// Appends a string to the existing data.
	public function appendToData($data, $stringToAppend)
	{
		return $data . ";" . $stringToAppend;
	}
	
	// Returns true if the url exists in the file.
	public function dataExists($dataToCheck, $getHandType = FALSE, $getPlayernameAndStatus = FALSE, $getRowData = FALSE)
	{
		// Controls if the file exists.
		if($this->checkForFile($this->textFileName))
		{
			// Explodes the file contents at each rowbreak.
			$file = file_get_contents($this->textFileName);		
			$result = explode(PHP_EOL, $file);
			
			foreach($result as $data)
			{
				// Is search for playername and status.
				if($getPlayernameAndStatus === TRUE)
				{
					// Searches file for playername.
					if(strpos($data, $dataToCheck) !== false)
					{
						// Searches for the status.					    
						if(strpos($data, $this->unresolved))
						{
							return $this->unresolved;
						}
						
						if(strpos($data, $this->resolved))
						{
							return $this->resolved;
						}
					}
				}
				else
				{
					// Separate URL from handtype.
					$urlAndHandType = explode(";", $data);
					
					// Checks for correct URL.
					if($urlAndHandType[0] == $dataToCheck)
					{
						// Gets all the data in the row.
						if($getRowData === TRUE)
						{
							return $data;
						}
												
						// Gets the handtype.
						if($getHandType === TRUE)
						{	
							return $urlAndHandType[1];
						}
						
						return TRUE;
					}
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
	public function saveDataToFile($data, $playerHandType = NULL, $playername = NULL)
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
			
			if(strpos($current, $data) !== false)
			{
				// Replaces the old string with an updated one.
				if(isset($playerHandType) && isset($playername))
				{
					$stringToSave = $data . ";" . $playerHandType . ";" . $playername;
				}
				
				$stringToSave = $this->addResolvedToURL($stringToSave);
				$current = str_replace($data, $stringToSave, $current);
			}
			else
			{
				// Append new contents to file.
				$current .= $stringToSave;
			}
			
			// Update file.
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
	
	public function manageComponentsFromFile($playername, $playerHandType = NULL, $secondPlayername = NULL, $secondPlayerHandType = NULL)
	{
		// Controls if the file exists.
		if($this->checkForFile($this->textFileName))
		{
			// Explodes the file contents at each rowbreak.
			$file = file_get_contents($this->textFileName);		
			$result = explode(PHP_EOL, $file);
			
			foreach($result as $data)
			{
				if(strpos($data, $playername) !== false)
				{
					// If second player is set, remove the url from file.
					if(isset($playerHandType) && isset($secondPlayername) && isset($secondPlayerHandType))
					{
						if(strpos($data, $playerHandType) !== false && strpos($data, $secondPlayername) !== false && strpos($data, $secondPlayerHandType) !== false)
						{
							$file = str_replace($data, "", $file);
							$file = str_replace(PHP_EOL, "", $file);
							file_put_contents($this->textFileName, $file);
						}
					}
					else
					{
						// Separate the necessary components.
						$dataFromFile = explode(";", $data);
	
						return array($dataFromFile[1], $dataFromFile[2], $dataFromFile[3]);
					}
				}
			}
		}
	}
}

?>